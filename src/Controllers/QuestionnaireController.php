<?php

declare(strict_types=1);

namespace CarePassport\Controllers;

use CarePassport\Http\Request;
use CarePassport\Http\Response;
use CarePassport\Http\Session;
use CarePassport\Repositories\QuestionnaireRepository;
use CarePassport\Repositories\ResidentRepository;
use CarePassport\Repositories\TemporarySessionRepository;
use CarePassport\View\View;

final class QuestionnaireController
{
    public function __construct(
        private readonly View $view,
        private readonly Request $request,
        private readonly TemporarySessionRepository $temporarySessions,
        private readonly ResidentRepository $residents,
        private readonly QuestionnaireRepository $questionnaire,
    ) {
    }

    public function selectPath(): Response
    {
        $resident = $this->currentResident();

        if ($resident === null) {
            return Response::redirect('/resident/new');
        }

        return new Response($this->view->render('questionnaire/select', [
            'title' => 'Choose a question path',
            'resident' => $resident,
            'paths' => $this->questionnaire->activePaths(),
            'selectedPathId' => isset($resident['question_path_id']) ? (int) $resident['question_path_id'] : null,
            'errors' => [],
        ]));
    }

    public function storePath(): Response
    {
        $resident = $this->currentResident();

        if ($resident === null) {
            return Response::redirect('/resident/new');
        }

        $pathSlug = $this->request->input('question_path');
        $path = $pathSlug !== '' ? $this->questionnaire->findActivePathBySlug($pathSlug) : null;
        $errors = [];

        if ($path === null) {
            $errors['question_path'] = 'Choose one of the available question paths.';
        }

        if ($errors !== []) {
            return new Response($this->view->render('questionnaire/select', [
                'title' => 'Choose a question path',
                'resident' => $resident,
                'paths' => $this->questionnaire->activePaths(),
                'selectedPathId' => isset($resident['question_path_id']) ? (int) $resident['question_path_id'] : null,
                'errors' => $errors,
            ]), 422);
        }

        $temporarySessionId = $this->temporarySessionId();
        $this->residents->setQuestionPath((int) $resident['id'], (int) $temporarySessionId, $path['id']);

        return Response::redirect('/questionnaire/question?position=1');
    }

    public function showQuestion(): Response
    {
        $context = $this->questionContext();

        if ($context instanceof Response) {
            return $context;
        }

        return new Response($this->view->render('questionnaire/question', $context + [
            'title' => 'Question ' . $context['position'] . ' of ' . $context['total'],
        ]));
    }

    public function saveQuestion(): Response
    {
        $context = $this->questionContext();

        if ($context instanceof Response) {
            return $context;
        }

        $action = $this->request->input('action', 'next');
        $answerText = $this->request->input('answer_text');
        $isSkipAction = $action === 'skip';
        $skipped = $isSkipAction || $answerText === '';
        $visibility = (string) ($context['question']['default_visibility'] ?: 'booklet');

        $this->questionnaire->saveAnswer(
            (int) $context['resident']['id'],
            (int) $context['question']['id'],
            $answerText !== '' ? $answerText : null,
            $skipped,
            $visibility,
        );

        if ($action === 'back') {
            return Response::redirect('/questionnaire/question?position=' . max(1, $context['position'] - 1));
        }

        if ($context['position'] >= $context['total']) {
            return Response::redirect('/questionnaire/complete');
        }

        return Response::redirect('/questionnaire/question?position=' . ($context['position'] + 1));
    }

    public function complete(): Response
    {
        $resident = $this->currentResident();

        if ($resident === null) {
            return Response::redirect('/resident/new');
        }

        $path = $this->selectedPath($resident);

        if ($path === null) {
            return Response::redirect('/questionnaire/select');
        }

        $total = $this->questionnaire->questionCount($path['id']);

        return new Response($this->view->render('questionnaire/complete', [
            'title' => 'Question path complete',
            'resident' => $resident,
            'path' => $path,
            'total' => $total,
            'completed' => $this->questionnaire->completedCount((int) $resident['id'], $path['id']),
        ]));
    }

    /**
     * @return array<string,mixed>|Response
     */
    private function questionContext(): array|Response
    {
        $resident = $this->currentResident();

        if ($resident === null) {
            return Response::redirect('/resident/new');
        }

        $path = $this->selectedPath($resident);

        if ($path === null) {
            return Response::redirect('/questionnaire/select');
        }

        $total = $this->questionnaire->questionCount($path['id']);
        $position = (int) $this->request->input('position', '1');
        $position = max(1, min($position, max(1, $total)));
        $question = $this->questionnaire->questionAt($path['id'], $position);

        if ($question === null) {
            return Response::redirect('/questionnaire/select');
        }

        return [
            'resident' => $resident,
            'path' => $path,
            'question' => $question,
            'answer' => $this->questionnaire->answerForQuestion((int) $resident['id'], $question['id']),
            'position' => $position,
            'total' => $total,
            'completed' => $this->questionnaire->completedCount((int) $resident['id'], $path['id']),
        ];
    }

    /**
     * @param array<string,mixed> $resident
     * @return array{id:int,slug:string,title:string,description:string|null}|null
     */
    private function selectedPath(array $resident): ?array
    {
        $pathId = isset($resident['question_path_id']) ? (int) $resident['question_path_id'] : 0;

        return $pathId > 0 ? $this->questionnaire->findPathById($pathId) : null;
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
