<?php

declare(strict_types=1);

namespace CarePassport\Controllers;

use CarePassport\Http\Request;
use CarePassport\Http\Response;
use CarePassport\Http\Session;
use CarePassport\Repositories\CompletionModeRepository;
use CarePassport\Repositories\ConsentRecordRepository;
use CarePassport\Repositories\IntroPageRepository;
use CarePassport\Repositories\ResidentRepository;
use CarePassport\Repositories\TemporarySessionRepository;
use CarePassport\View\View;

final class IntroConsentController
{
    private const CONSENT_TEXT_VERSION = 'mvp-2026-06-22';

    public function __construct(
        private readonly View $view,
        private readonly Request $request,
        private readonly TemporarySessionRepository $temporarySessions,
        private readonly ResidentRepository $residents,
        private readonly IntroPageRepository $introPages,
        private readonly CompletionModeRepository $completionModes,
        private readonly ConsentRecordRepository $consentRecords,
    ) {
    }

    public function intro(): Response
    {
        $resident = $this->currentResident();

        if ($resident === null) {
            return Response::redirect('/resident/new');
        }

        return new Response($this->view->render('intro/index', [
            'title' => 'Before you begin',
            'resident' => $resident,
            'introPages' => $this->introPages->active(),
        ]));
    }

    public function consent(): Response
    {
        $resident = $this->currentResident();

        if ($resident === null) {
            return Response::redirect('/resident/new');
        }

        return new Response($this->view->render('consent/form', [
            'title' => 'Consent and completion mode',
            'resident' => $resident,
            'completionModes' => $this->completionModes->active(),
            'data' => $this->blankData(),
            'errors' => [],
        ]));
    }

    public function storeConsent(): Response
    {
        $resident = $this->currentResident();

        if ($resident === null) {
            return Response::redirect('/resident/new');
        }

        [$data, $errors, $mode] = $this->validatedConsentData();

        if ($errors !== []) {
            return new Response($this->view->render('consent/form', [
                'title' => 'Consent and completion mode',
                'resident' => $resident,
                'completionModes' => $this->completionModes->active(),
                'data' => $data,
                'errors' => $errors,
            ]), 422);
        }

        $helperName = in_array($mode['slug'], ['assisted', 'proxy'], true) ? $data['helper_name'] : null;
        $helperRelationship = in_array($mode['slug'], ['assisted', 'proxy'], true) ? $data['helper_relationship'] : null;

        $this->consentRecords->create(
            (int) $resident['id'],
            (int) $mode['id'],
            $helperName !== '' ? $helperName : null,
            $helperRelationship !== '' ? $helperRelationship : null,
            self::CONSENT_TEXT_VERSION,
            [
                'completion_mode' => $mode['slug'],
                'acknowledged_optional_questions' => true,
                'acknowledged_editing_and_visibility' => true,
                'acknowledged_printed_outputs_may_be_seen' => true,
                'acknowledged_capacity_not_assessed' => true,
            ],
        );

        Session::flash('status', 'Consent saved.');

        return Response::redirect('/questionnaire/select');
    }

    public function nextSteps(): Response
    {
        $resident = $this->currentResident();

        if ($resident === null) {
            return Response::redirect('/resident/new');
        }

        return new Response($this->view->render('consent/next-steps', [
            'title' => 'Ready for the next step',
            'resident' => $resident,
            'flash' => Session::pullFlash('status'),
        ]));
    }

    /**
     * @return array{0:array<string, string>,1:array<string, string>,2:array{id:int,slug:string,label:string,description:string|null}|null}
     */
    private function validatedConsentData(): array
    {
        $data = [
            'completion_mode' => $this->request->input('completion_mode'),
            'helper_name' => $this->request->input('helper_name'),
            'helper_relationship' => $this->request->input('helper_relationship'),
            'acknowledged' => $this->request->input('acknowledged'),
        ];

        $errors = [];
        $mode = null;

        if ($data['completion_mode'] === '') {
            $errors['completion_mode'] = 'Choose who is completing this.';
        } else {
            $mode = $this->completionModes->findActiveBySlug($data['completion_mode']);

            if ($mode === null) {
                $errors['completion_mode'] = 'Choose one of the available completion options.';
            }
        }

        if ($mode !== null && in_array($mode['slug'], ['assisted', 'proxy'], true)) {
            if ($data['helper_name'] === '') {
                $errors['helper_name'] = $mode['slug'] === 'proxy'
                    ? 'Enter the proxy name.'
                    : 'Enter the helper name.';
            } elseif (mb_strlen($data['helper_name']) > 255) {
                $errors['helper_name'] = 'Name must be 255 characters or fewer.';
            }

            if ($data['helper_relationship'] === '') {
                $errors['helper_relationship'] = 'Enter the relationship to the person.';
            } elseif (mb_strlen($data['helper_relationship']) > 255) {
                $errors['helper_relationship'] = 'Relationship must be 255 characters or fewer.';
            }
        }

        if ($data['acknowledged'] !== '1') {
            $errors['acknowledged'] = 'Confirm that you understand the privacy and sharing notes.';
        }

        return [$data, $errors, $mode];
    }

    /**
     * @return array<string, string>
     */
    private function blankData(): array
    {
        return [
            'completion_mode' => '',
            'helper_name' => '',
            'helper_relationship' => '',
            'acknowledged' => '',
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function currentResident(): ?array
    {
        $temporarySessionId = $this->temporarySessionId();
        $userId = $this->userId();
        $residentId = (int) Session::get('resident_id', 0);

        if (($temporarySessionId === null && $userId === null) || $residentId === 0) {
            return null;
        }

        return $this->residents->findAccessible($residentId, $temporarySessionId, $userId);
    }

    private function userId(): ?int
    {
        $userId = Session::get('user_id');

        return is_int($userId) && $userId > 0 ? $userId : null;
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
