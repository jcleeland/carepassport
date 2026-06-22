<?php

declare(strict_types=1);

namespace CarePassport\Controllers;

use CarePassport\Http\Response;
use CarePassport\Http\Session;
use CarePassport\Repositories\TemporarySessionRepository;
use CarePassport\View\View;

final class StartController
{
    public function __construct(
        private readonly View $view,
        private readonly TemporarySessionRepository $temporarySessions,
    ) {
    }

    public function show(): Response
    {
        return new Response($this->view->render('start'));
    }

    public function start(): Response
    {
        $temporarySession = $this->temporarySessions->create();

        Session::put('temporary_session_token', $temporarySession['token']);
        Session::put('temporary_session_id', $temporarySession['id']);

        return Response::redirect('/resident/new');
    }
}
