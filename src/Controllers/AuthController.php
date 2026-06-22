<?php

declare(strict_types=1);

namespace CarePassport\Controllers;

use CarePassport\Http\Request;
use CarePassport\Http\Response;
use CarePassport\Http\Session;
use CarePassport\Repositories\ResidentRepository;
use CarePassport\Repositories\TemporarySessionRepository;
use CarePassport\Repositories\UserRepository;
use CarePassport\View\View;

final class AuthController
{
    public function __construct(
        private readonly View $view,
        private readonly Request $request,
        private readonly UserRepository $users,
        private readonly ResidentRepository $residents,
        private readonly TemporarySessionRepository $temporarySessions,
    ) {
    }

    public function registerForm(): Response
    {
        return new Response($this->view->render('auth/register', [
            'title' => 'Create account',
            'data' => ['name' => '', 'email' => ''],
            'errors' => [],
            'status' => Session::pullFlash('status'),
        ]));
    }

    public function register(): Response
    {
        [$data, $errors] = $this->validatedRegisterData();

        if ($errors !== []) {
            return new Response($this->view->render('auth/register', [
                'title' => 'Create account',
                'data' => $data,
                'errors' => $errors,
                'status' => null,
            ]), 422);
        }

        if ($this->users->findByEmail($data['email']) !== null) {
            return new Response($this->view->render('auth/register', [
                'title' => 'Create account',
                'data' => $data,
                'errors' => ['email' => 'An account already exists for this email address.'],
                'status' => null,
            ]), 422);
        }

        $userId = $this->users->create(
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
        );

        Session::regenerate();
        Session::put('user_id', $userId);
        $this->attachCurrentResident($userId);
        Session::flash('status', 'Account created.');

        return Response::redirect('/dashboard');
    }

    public function loginForm(): Response
    {
        return new Response($this->view->render('auth/login', [
            'title' => 'Log in',
            'data' => ['email' => ''],
            'errors' => [],
            'status' => Session::pullFlash('status'),
        ]));
    }

    public function login(): Response
    {
        $email = self::normalizeEmail($this->request->input('email'));
        $password = $this->request->input('password');
        $errors = [];

        if ($email === '' || $password === '') {
            $errors['email'] = 'Enter your email and password.';
        }

        $user = $email !== '' ? $this->users->findByEmail($email) : null;

        if ($errors === [] && ($user === null || $user['password_hash'] === null || ! password_verify($password, $user['password_hash']))) {
            $errors['email'] = 'The email or password was not recognised.';
        }

        if ($errors !== []) {
            return new Response($this->view->render('auth/login', [
                'title' => 'Log in',
                'data' => ['email' => $email],
                'errors' => $errors,
                'status' => null,
            ]), 422);
        }

        Session::regenerate();
        Session::put('user_id', (int) $user['id']);
        $this->attachCurrentResident((int) $user['id']);
        Session::flash('status', 'Logged in.');

        return Response::redirect('/dashboard');
    }

    public function logout(): Response
    {
        Session::forget('user_id');
        Session::forget('resident_id');
        Session::regenerate();
        Session::flash('status', 'Logged out.');

        return Response::redirect('/login');
    }

    /**
     * @return array{0:array<string,string>,1:array<string,string>}
     */
    private function validatedRegisterData(): array
    {
        $data = [
            'name' => $this->request->input('name'),
            'email' => self::normalizeEmail($this->request->input('email')),
            'password' => $this->request->input('password'),
            'password_confirmation' => $this->request->input('password_confirmation'),
        ];
        $errors = [];

        if ($data['name'] === '') {
            $errors['name'] = 'Enter your name.';
        } elseif (mb_strlen($data['name']) > 255) {
            $errors['name'] = 'Name must be 255 characters or fewer.';
        }

        if ($data['email'] === '') {
            $errors['email'] = 'Enter your email address.';
        } elseif (! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid email address.';
        } elseif (mb_strlen($data['email']) > 255) {
            $errors['email'] = 'Email must be 255 characters or fewer.';
        }

        if (strlen($data['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }

        if ($data['password'] !== $data['password_confirmation']) {
            $errors['password_confirmation'] = 'Passwords must match.';
        }

        return [$data, $errors];
    }

    private function attachCurrentResident(int $userId): void
    {
        $temporarySessionId = $this->temporarySessionId();
        $residentId = (int) Session::get('resident_id', 0);

        if ($temporarySessionId === null || $residentId === 0) {
            return;
        }

        $this->residents->attachToUserIfSafe($residentId, $temporarySessionId, $userId);
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

    private static function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }
}
