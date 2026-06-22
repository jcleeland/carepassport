<?php

declare(strict_types=1);

namespace CarePassport\Controllers;

use CarePassport\Http\Request;
use CarePassport\Http\Response;
use CarePassport\Http\Session;
use CarePassport\Repositories\ResidentRepository;
use CarePassport\View\View;

final class DashboardController
{
    public function __construct(
        private readonly View $view,
        private readonly Request $request,
        private readonly ResidentRepository $residents,
    ) {
    }

    public function show(): Response
    {
        $userId = $this->userId();

        if ($userId === null) {
            return Response::redirect('/login');
        }

        return new Response($this->view->render('dashboard/index', [
            'title' => 'Dashboard',
            'residentCards' => array_map(
                fn (array $resident): array => $this->residentCard($resident),
                $this->residents->dashboardForUser($userId),
            ),
            'status' => Session::pullFlash('status'),
        ]));
    }

    public function useResident(): Response
    {
        $userId = $this->userId();

        if ($userId === null) {
            return Response::redirect('/login');
        }

        $residentId = (int) $this->request->input('id');
        $resident = $residentId > 0 ? $this->residents->findAccessible($residentId, null, $userId) : null;

        if ($resident === null) {
            Session::flash('status', 'That profile was not found for your account.');
            return Response::redirect('/dashboard');
        }

        Session::put('resident_id', (int) $resident['id']);

        return Response::redirect($this->targetPath($this->request->input('target'), (int) $this->request->input('position', '1')));
    }

    private function userId(): ?int
    {
        $userId = Session::get('user_id');

        return is_int($userId) && $userId > 0 ? $userId : null;
    }

    private function targetPath(string $target, int $position = 1): string
    {
        return match ($target) {
            'intro' => '/intro',
            'consent' => '/consent',
            'questionnaire' => '/questionnaire/select',
            'questionnaire_question' => '/questionnaire/question?position=' . max(1, $position),
            'review' => '/questionnaire/review',
            'photo' => '/photo/portrait',
            'poster_a' => '/output/poster-a',
            'poster_b' => '/output/poster-b',
            default => '/resident/edit',
        };
    }

    /**
     * @param array<string, mixed> $resident
     * @return array<string, mixed>
     */
    private function residentCard(array $resident): array
    {
        $questionPathSelected = (int) ($resident['question_path_id'] ?? 0) > 0;
        $questionCount = (int) ($resident['question_count'] ?? 0);
        $answerCount = (int) ($resident['answer_count'] ?? 0);
        $hasConsent = (int) ($resident['consent_count'] ?? 0) > 0;
        $hasPortrait = (int) ($resident['has_portrait'] ?? 0) === 1;
        $hasPosterContent = (int) ($resident['poster_answer_count'] ?? 0) > 0 || $hasPortrait;
        $questionnaireComplete = $questionCount > 0 && $answerCount >= $questionCount;
        $reviewAvailable = $answerCount > 0;
        $nextQuestionPosition = max(1, min($answerCount + 1, max(1, $questionCount)));

        if (! $hasConsent) {
            $status = 'Intro and consent incomplete';
            $detail = 'Profile created. Continue with the intro and consent step.';
            $continueTarget = 'intro';
        } elseif (! $questionPathSelected) {
            $status = 'Ready to choose questions';
            $detail = 'Consent is saved. Choose a question path.';
            $continueTarget = 'questionnaire';
        } elseif (! $questionnaireComplete) {
            $status = $answerCount > 0 ? 'Questionnaire in progress' : 'Question path selected';
            $detail = $answerCount . ' of ' . $questionCount . ' questions saved or skipped.';
            $continueTarget = 'questionnaire_question';
        } elseif (! $reviewAvailable) {
            $status = 'Questionnaire complete';
            $detail = 'Review answers and visibility next.';
            $continueTarget = 'review';
        } elseif (! $hasPortrait) {
            $status = 'Review available, photo not uploaded';
            $detail = 'Answers can be reviewed. Add a portrait photo when ready.';
            $continueTarget = 'photo';
        } elseif ($hasPosterContent) {
            $status = 'Poster A preview available';
            $detail = 'Portrait and poster-visible content are available for preview.';
            $continueTarget = 'poster_a';
        } else {
            $status = 'Portrait photo uploaded';
            $detail = 'Review visibility to choose what can appear on Poster A.';
            $continueTarget = 'review';
        }

        return [
            'resident' => $resident,
            'status' => $status,
            'detail' => $detail,
            'continue_target' => $continueTarget,
            'continue_position' => $nextQuestionPosition,
            'question_count' => $questionCount,
            'answer_count' => $answerCount,
            'has_consent' => $hasConsent,
            'has_portrait' => $hasPortrait,
            'has_poster_preview' => $hasPosterContent,
        ];
    }
}
