<?php

declare(strict_types=1);

namespace CarePassport\Controllers;

use CarePassport\Http\Request;
use CarePassport\Http\Response;
use CarePassport\Http\Session;
use CarePassport\Repositories\ResidentRepository;
use CarePassport\Repositories\SupportContextRepository;
use CarePassport\Repositories\TemporarySessionRepository;
use CarePassport\View\View;

final class ResidentController
{
    public function __construct(
        private readonly View $view,
        private readonly Request $request,
        private readonly TemporarySessionRepository $temporarySessions,
        private readonly SupportContextRepository $supportContexts,
        private readonly ResidentRepository $residents,
    ) {
    }

    public function create(): Response
    {
        $temporarySessionId = $this->temporarySessionId();

        if ($temporarySessionId === null) {
            return Response::redirect('/');
        }

        return new Response($this->view->render('resident/form', [
            'title' => 'Create a profile',
            'action' => '/resident',
            'resident' => $this->blankResident(),
            'supportContexts' => $this->supportContexts->active(),
            'errors' => [],
            'flash' => Session::pullFlash('status'),
        ]));
    }

    public function store(): Response
    {
        $temporarySessionId = $this->temporarySessionId();

        if ($temporarySessionId === null) {
            return Response::redirect('/');
        }

        [$data, $errors] = $this->validatedData();

        if ($errors !== []) {
            return new Response($this->view->render('resident/form', [
                'title' => 'Create a profile',
                'action' => '/resident',
                'resident' => $data,
                'supportContexts' => $this->supportContexts->active(),
                'errors' => $errors,
                'flash' => null,
            ]), 422);
        }

        $residentId = $this->residents->create($temporarySessionId, $data);
        Session::put('resident_id', $residentId);
        Session::flash('status', 'Profile saved.');

        return Response::redirect('/intro');
    }

    public function edit(): Response
    {
        $resident = $this->currentResident();

        if ($resident === null) {
            return Response::redirect('/resident/new');
        }

        return new Response($this->view->render('resident/form', [
            'title' => 'Edit profile',
            'action' => '/resident/update',
            'resident' => $resident,
            'supportContexts' => $this->supportContexts->active(),
            'errors' => [],
            'flash' => Session::pullFlash('status'),
        ]));
    }

    public function update(): Response
    {
        $temporarySessionId = $this->temporarySessionId();
        $residentId = (int) Session::get('resident_id', 0);

        if ($temporarySessionId === null || $residentId === 0) {
            return Response::redirect('/resident/new');
        }

        [$data, $errors] = $this->validatedData();

        if ($errors !== []) {
            return new Response($this->view->render('resident/form', [
                'title' => 'Edit profile',
                'action' => '/resident/update',
                'resident' => array_merge(['id' => $residentId], $data),
                'supportContexts' => $this->supportContexts->active(),
                'errors' => $errors,
                'flash' => null,
            ]), 422);
        }

        $this->residents->update($residentId, $temporarySessionId, $data);
        Session::flash('status', 'Profile updated.');

        return Response::redirect('/resident/edit');
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

    /**
     * @return array{0:array<string, string>,1:array<string, string>}
     */
    private function validatedData(): array
    {
        $data = [
            'full_name' => $this->request->input('full_name'),
            'preferred_name' => $this->request->input('preferred_name'),
            'support_context' => $this->request->input('support_context'),
            'service_location_name' => $this->request->input('service_location_name'),
            'location_reference' => $this->request->input('location_reference'),
            'primary_supporter_name' => $this->request->input('primary_supporter_name'),
            'notes' => $this->request->input('notes'),
        ];

        $errors = [];

        if ($data['full_name'] === '') {
            $errors['full_name'] = 'Enter the person\'s full name.';
        } elseif (mb_strlen($data['full_name']) > 255) {
            $errors['full_name'] = 'Full name must be 255 characters or fewer.';
        }

        if ($data['preferred_name'] !== '' && mb_strlen($data['preferred_name']) > 255) {
            $errors['preferred_name'] = 'Preferred name must be 255 characters or fewer.';
        }

        if ($data['support_context'] === '') {
            $errors['support_context'] = 'Choose a support context.';
        } elseif (! $this->supportContexts->exists($data['support_context'])) {
            $errors['support_context'] = 'Choose one of the available support contexts.';
        }

        foreach ([
            'service_location_name' => 'Service, support setting or location name',
            'location_reference' => 'Room, location or reference',
            'primary_supporter_name' => 'Primary supporter or contact name',
        ] as $field => $label) {
            if ($data[$field] !== '' && mb_strlen($data[$field]) > 255) {
                $errors[$field] = $label . ' must be 255 characters or fewer.';
            }
        }

        if ($data['notes'] !== '' && mb_strlen($data['notes']) > 2000) {
            $errors['notes'] = 'Notes must be 2000 characters or fewer.';
        }

        return [$data, $errors];
    }

    /**
     * @return array<string, string>
     */
    private function blankResident(): array
    {
        return [
            'full_name' => '',
            'preferred_name' => '',
            'support_context' => '',
            'service_location_name' => '',
            'location_reference' => '',
            'primary_supporter_name' => '',
            'notes' => '',
        ];
    }
}
