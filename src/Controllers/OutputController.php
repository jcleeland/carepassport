<?php

declare(strict_types=1);

namespace CarePassport\Controllers;

use CarePassport\Http\Response;
use CarePassport\Http\Session;
use CarePassport\Repositories\OutputRepository;
use CarePassport\Repositories\PhotoRepository;
use CarePassport\Repositories\ResidentRepository;
use CarePassport\Repositories\TemporarySessionRepository;
use CarePassport\View\View;

final class OutputController
{
    public function __construct(
        private readonly View $view,
        private readonly TemporarySessionRepository $temporarySessions,
        private readonly ResidentRepository $residents,
        private readonly OutputRepository $outputs,
        private readonly PhotoRepository $photos,
    ) {
    }

    public function posterA(): Response
    {
        $resident = $this->currentResident();

        if ($resident === null) {
            return Response::redirect('/resident/new');
        }

        $poster = $this->outputs->posterTemplateForResident('poster_a', (int) $resident['id']);

        if ($poster === null) {
            return new Response('Poster template not found', 404);
        }

        return new Response($this->view->render('output/poster-a', [
            'title' => $poster['template']['title'],
            'resident' => $resident,
            'outputTemplate' => $poster['template'],
            'zones' => $poster['zones'],
            'photo' => $this->photos->portraitForResident((int) $resident['id']),
        ]));
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
}
