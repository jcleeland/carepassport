<?php

declare(strict_types=1);

use CarePassport\Controllers\AuthController;
use CarePassport\Controllers\DashboardController;
use CarePassport\Controllers\IntroConsentController;
use CarePassport\Controllers\OutputController;
use CarePassport\Controllers\PhotoController;
use CarePassport\Controllers\QuestionnaireController;
use CarePassport\Controllers\ResidentController;
use CarePassport\Controllers\StartController;
use CarePassport\Database\Connection;
use CarePassport\Http\Request;
use CarePassport\Http\Router;
use CarePassport\Http\Session;
use CarePassport\Repositories\CompletionModeRepository;
use CarePassport\Repositories\ConsentRecordRepository;
use CarePassport\Repositories\IntroPageRepository;
use CarePassport\Repositories\OutputRepository;
use CarePassport\Repositories\PhotoRepository;
use CarePassport\Repositories\QuestionnaireRepository;
use CarePassport\Repositories\ResidentRepository;
use CarePassport\Repositories\SupportContextRepository;
use CarePassport\Repositories\TemporarySessionRepository;
use CarePassport\Repositories\UserRepository;
use CarePassport\Support\PortraitImageProcessor;
use CarePassport\View\View;

$config = require dirname(__DIR__) . '/bootstrap/app.php';

Session::start((string) $config['app']['session_name']);

$request = Request::capture();
$pdo = Connection::make($config['database']);
$view = new View();

$temporarySessions = new TemporarySessionRepository($pdo);
$users = new UserRepository($pdo);
$supportContexts = new SupportContextRepository($pdo);
$residents = new ResidentRepository($pdo);
$introPages = new IntroPageRepository($pdo);
$completionModes = new CompletionModeRepository($pdo);
$consentRecords = new ConsentRecordRepository($pdo);
$questionnaire = new QuestionnaireRepository($pdo);
$photos = new PhotoRepository($pdo);
$outputs = new OutputRepository($pdo);
$portraitProcessor = new PortraitImageProcessor($config['photo']);

$startController = new StartController($view, $temporarySessions);
$residentController = new ResidentController($view, $request, $temporarySessions, $supportContexts, $residents);
$authController = new AuthController($view, $request, $users, $residents, $temporarySessions);
$dashboardController = new DashboardController($view, $request, $residents);
$introConsentController = new IntroConsentController(
    $view,
    $request,
    $temporarySessions,
    $residents,
    $introPages,
    $completionModes,
    $consentRecords,
);
$questionnaireController = new QuestionnaireController(
    $view,
    $request,
    $temporarySessions,
    $residents,
    $questionnaire,
    $config['visibility'],
);
$photoController = new PhotoController(
    $view,
    $request,
    $temporarySessions,
    $residents,
    $photos,
    $portraitProcessor,
);
$outputController = new OutputController(
    $view,
    $temporarySessions,
    $residents,
    $outputs,
    $photos,
);

$router = new Router();
$router->get('/', fn () => $startController->show());
$router->post('/start', fn () => $startController->start());
$router->get('/register', fn () => $authController->registerForm());
$router->post('/register', fn () => $authController->register());
$router->get('/login', fn () => $authController->loginForm());
$router->post('/login', fn () => $authController->login());
$router->post('/logout', fn () => $authController->logout());
$router->get('/dashboard', fn () => $dashboardController->show());
$router->get('/resident/use', fn () => $dashboardController->useResident());
$router->get('/resident/new', fn () => $residentController->create());
$router->post('/resident', fn () => $residentController->store());
$router->get('/resident/edit', fn () => $residentController->edit());
$router->post('/resident/update', fn () => $residentController->update());
$router->get('/intro', fn () => $introConsentController->intro());
$router->get('/consent', fn () => $introConsentController->consent());
$router->post('/consent', fn () => $introConsentController->storeConsent());
$router->get('/next-steps', fn () => $introConsentController->nextSteps());
$router->get('/questionnaire/select', fn () => $questionnaireController->selectPath());
$router->post('/questionnaire/select', fn () => $questionnaireController->storePath());
$router->get('/questionnaire/question', fn () => $questionnaireController->showQuestion());
$router->post('/questionnaire/question', fn () => $questionnaireController->saveQuestion());
$router->get('/questionnaire/complete', fn () => $questionnaireController->complete());
$router->get('/questionnaire/review', fn () => $questionnaireController->review());
$router->post('/questionnaire/review', fn () => $questionnaireController->updateReview());
$router->get('/photo/portrait', fn () => $photoController->showPortrait());
$router->post('/photo/portrait', fn () => $photoController->uploadPortrait());
$router->get('/photo/portrait/preview', fn () => $photoController->previewPortrait());
$router->get('/photo/portrait/skip', fn () => $photoController->skipPortrait());
$router->get('/output', fn () => $outputController->hub());
$router->get('/output/poster-a', fn () => $outputController->posterA());
$router->get('/output/poster-b', fn () => $outputController->posterB());
$router->get('/output/booklet', fn () => $outputController->booklet());

$router->dispatch($request)->send();
