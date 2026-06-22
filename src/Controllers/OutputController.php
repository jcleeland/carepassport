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

    public function hub(): Response
    {
        $resident = $this->currentResident();

        if ($resident === null) {
            return Response::redirect('/resident/new');
        }

        $photo = $this->photos->portraitForResident((int) $resident['id']);
        $counts = $this->outputs->answerCountsForResident((int) $resident['id']);
        $templates = $this->outputs->activeOutputTemplates();

        return new Response($this->view->render('output/hub', [
            'title' => 'Output hub',
            'resident' => $resident,
            'photo' => $photo,
            'counts' => $counts,
            'cards' => $this->outputCards($templates, $counts, $photo !== null),
        ]));
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

    public function posterB(): Response
    {
        $resident = $this->currentResident();

        if ($resident === null) {
            return Response::redirect('/resident/new');
        }

        $poster = $this->outputs->posterTemplateForResident('poster_b', (int) $resident['id']);

        if ($poster === null) {
            return new Response('Poster template not found', 404);
        }

        return new Response($this->view->render('output/poster-b', [
            'title' => $poster['template']['title'],
            'resident' => $resident,
            'outputTemplate' => $poster['template'],
            'zones' => $poster['zones'],
        ]));
    }

    public function booklet(): Response
    {
        $resident = $this->currentResident();

        if ($resident === null) {
            return Response::redirect('/resident/new');
        }

        $booklet = $this->outputs->bookletTemplateForResident((int) $resident['id']);

        if ($booklet === null) {
            return new Response('Booklet template not found', 404);
        }

        $supportContext = null;

        if (isset($resident['support_context']) && is_string($resident['support_context']) && $resident['support_context'] !== '') {
            $supportContext = $this->outputs->supportContext($resident['support_context']);
        }

        return new Response($this->view->render('output/booklet', [
            'title' => $booklet['template']['title'],
            'resident' => $resident,
            'outputTemplate' => $booklet['template'],
            'sections' => $booklet['sections'],
            'photo' => $this->photos->portraitForResident((int) $resident['id']),
            'supportContext' => $supportContext,
            'completionContext' => $this->outputs->latestCompletionContextForResident((int) $resident['id']),
        ]));
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

    /**
     * @param array<string,array{id:int,slug:string,title:string,description:string|null}> $templates
     * @param array{poster_visible:int,booklet_visible:int,private:int,skipped:int} $counts
     * @return list<array{slug:string,title:string,description:string|null,href:string,status:string,detail:string}>
     */
    private function outputCards(array $templates, array $counts, bool $hasPhoto): array
    {
        $routes = [
            'poster_a' => '/output/poster-a',
            'poster_b' => '/output/poster-b',
            'full_booklet' => '/output/booklet',
        ];
        $cards = [];

        foreach ($routes as $slug => $href) {
            if (! isset($templates[$slug])) {
                continue;
            }

            $posterContent = $counts['poster_visible'];
            $bookletContent = $counts['poster_visible'] + $counts['booklet_visible'];

            if ($slug === 'full_booklet') {
                $status = $bookletContent > 0 ? 'Ready to preview' : 'No visible answers yet';
                $detail = $bookletContent . ' answer' . ($bookletContent === 1 ? '' : 's') . ' available for the booklet.';
            } else {
                $status = $posterContent > 0 || $hasPhoto ? 'Ready to preview' : 'No poster-visible answers yet';
                $detail = $posterContent . ' poster-visible answer' . ($posterContent === 1 ? '' : 's') . ($hasPhoto ? ', with portrait photo.' : ', no portrait photo uploaded.');
            }

            $cards[] = [
                'slug' => $slug,
                'title' => $templates[$slug]['title'],
                'description' => $templates[$slug]['description'],
                'href' => $href,
                'status' => $status,
                'detail' => $detail,
            ];
        }

        return $cards;
    }
}
