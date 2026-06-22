<?php

declare(strict_types=1);

namespace CarePassport\Controllers;

use CarePassport\Http\Request;
use CarePassport\Http\Response;
use CarePassport\Http\Session;
use CarePassport\Repositories\PhotoRepository;
use CarePassport\Repositories\ResidentRepository;
use CarePassport\Repositories\TemporarySessionRepository;
use CarePassport\Support\PortraitImageProcessor;
use CarePassport\View\View;

final class PhotoController
{
    public function __construct(
        private readonly View $view,
        private readonly Request $request,
        private readonly TemporarySessionRepository $temporarySessions,
        private readonly ResidentRepository $residents,
        private readonly PhotoRepository $photos,
        private readonly PortraitImageProcessor $processor,
    ) {
    }

    public function showPortrait(): Response
    {
        $resident = $this->currentResident();

        if ($resident === null) {
            return Response::redirect('/resident/new');
        }

        return new Response($this->view->render('photo/portrait', [
            'title' => 'Portrait photo',
            'resident' => $resident,
            'photo' => $this->photos->portraitForResident((int) $resident['id']),
            'errors' => [],
            'status' => Session::pullFlash('status'),
        ]));
    }

    public function uploadPortrait(): Response
    {
        $resident = $this->currentResident();

        if ($resident === null) {
            return Response::redirect('/resident/new');
        }

        $existingPhoto = $this->photos->portraitForResident((int) $resident['id']);

        try {
            $paths = $this->processor->storeUploadedPortrait((int) $resident['id'], $this->request->file('portrait_photo') ?? []);
            $this->photos->replacePortrait((int) $resident['id'], $paths['original_path'], $paths['processed_path']);
            $this->deletePhotoFiles($existingPhoto);
        } catch (\InvalidArgumentException | \RuntimeException $exception) {
            return new Response($this->view->render('photo/portrait', [
                'title' => 'Portrait photo',
                'resident' => $resident,
                'photo' => $existingPhoto,
                'errors' => ['portrait_photo' => $exception->getMessage()],
                'status' => null,
            ]), 422);
        }

        Session::flash('status', 'Portrait photo saved.');

        return Response::redirect('/output/poster-a');
    }

    public function previewPortrait(): Response
    {
        $resident = $this->currentResident();

        if ($resident === null) {
            return new Response('Not found', 404);
        }

        $photo = $this->photos->portraitForResident((int) $resident['id']);
        $path = $photo['processed_file_path'] ?? null;

        if (! is_string($path) || $path === '') {
            return new Response('Not found', 404);
        }

        $absolutePath = base_path($path);

        if (! is_file($absolutePath)) {
            return new Response('Not found', 404);
        }

        $contents = file_get_contents($absolutePath);

        if ($contents === false) {
            return new Response('Not found', 404);
        }

        return new Response($contents, 200, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'no-store, private',
        ]);
    }

    public function skipPortrait(): Response
    {
        if ($this->currentResident() === null) {
            return Response::redirect('/resident/new');
        }

        return Response::redirect('/output/poster-a');
    }

    /**
     * @return array<string, mixed>|null
     */
    private function currentResident(): ?array
    {
        $temporarySessionId = $this->temporarySessionId();
        $residentId = (int) Session::get('resident_id', 0);

        if ($temporarySessionId === null || $residentId === 0) {
            return null;
        }

        return $this->residents->findForTemporarySession($residentId, $temporarySessionId);
    }

    private function temporarySessionId(): ?int
    {
        $sessionId = Session::get('temporary_session_id');

        if (is_int($sessionId) && $sessionId > 0) {
            return $sessionId;
        }

        $id = $this->temporarySessions->findValidIdByToken(Session::get('temporary_session_token'));

        if ($id !== null) {
            Session::put('temporary_session_id', $id);
        }

        return $id;
    }

    /**
     * @param array{id:int,resident_id:int,type:string,original_file_path:string,processed_file_path:string|null,caption:string|null}|null $photo
     */
    private function deletePhotoFiles(?array $photo): void
    {
        if ($photo === null) {
            return;
        }

        foreach ([$photo['original_file_path'], $photo['processed_file_path']] as $path) {
            if (! is_string($path) || $path === '') {
                continue;
            }

            $absolutePath = base_path($path);

            if (is_file($absolutePath)) {
                @unlink($absolutePath);
            }
        }
    }
}
