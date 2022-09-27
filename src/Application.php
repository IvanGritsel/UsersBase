<?php

namespace App;

use App\Listener\Listener;

require_once __DIR__ . '/../vendor/autoload.php';

class Application
{
    public static function launch(): void
    {
        $listener = new Listener();
        $listener->startListening();
    }
}

Application::launch();
