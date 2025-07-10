<?php

namespace Mindshot\Kiyozumicf\Helpers;

class AuthMiddleware
{
    public static function check(): void
    {
        session_start();
        if (!isset($_SESSION['usuario'])) {
            header('Location: /');
            exit;
        }
    }
}
