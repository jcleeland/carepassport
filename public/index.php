<?php

declare(strict_types=1);

use CarePassport\Controllers\IntroConsentController;
use CarePassport\Controllers\ResidentController;
use CarePassport\Controllers\StartController;
use CarePassport\Database\Connection;
use CarePassport\Http\Request;
use CarePassport\Http\Router;
use CarePassport\Http\Session;
use CarePassport\Repositories\CompletionModeRepository;
use CarePassport\Repositories\ConsentRecordRepository;
use CarePassport\Repositories\IntroPageRepository;
use CarePassport\Repositories\ResidentRepository;
use CarePassport\Repositories\SupportContextRepository;
use CarePassport\Repositories\TemporarySessionRepository;
use CarePassport\View\View;

$config = require dirname(__DIR__) . '/bootstrap/app.php';

Session::start((string) $config['app']['session_name']);

$request = Request::capture();
$pdo = Connection::make($config['database']);
$view = new View();

$temporarySessions = new TemporarySessionRepository($pdo);
$supportContexts = new SupportContextRepository($pdo);
$residents = new ResidentRepository($pdo);
$introPages = new IntroPageRepository($pdo);
$completionModes = new CompletionModeRepository($pdo);
$consentRecords = new ConsentRecordRepository($pdo);

$startController = new StartController($view, $temporarySessions);
$residentController = new ResidentController($view, $request, $temporarySessions, $supportContexts, $residents);
$introConsentController = new IntroConsentController(
    $view,
    $request,
    $temporarySessions,
    $residents,
    $introPages,
    $completionModes,
    $consentRecords,
);

$router = new Router();
$router->get('/', fn () => $startController->show());
$router->post('/start', fn () => $startController->start());
$router->get('/resident/new', fn () => $residentController->create());
$router->post('/resident', fn () => $residentController->store());
$router->get('/resident/edit', fn () => $residentController->edit());
$router->post('/resident/update', fn () => $residentController->update());
$router->get('/intro', fn () => $introConsentController->intro());
$router->get('/consent', fn () => $introConsentController->consent());
$router->post('/consent', fn () => $introConsentController->storeConsent());
$router->get('/next-steps', fn () => $introConsentController->nextSteps());

$router->dispatch($request)->send();
