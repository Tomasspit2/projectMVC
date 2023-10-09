<?php

namespace App\Controller;

use App\Model\UserManager;

class UserController extends AbstractController
{
    public function login(): mixed
    {
        return $this->twig->render('login.html.twig');
    }

    public function register(): mixed
    {
        return $this->twig->render('register.html.twig');
    }

    public function disconnect(): void
    {
        session_unset();
        session_destroy();
    }
}
