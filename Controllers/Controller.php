<?php

namespace Controllers;

include('vendor/autoload.php');

use Symfony\Component\Dotenv\Dotenv;

class Controller
{
    public function __construct()
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__.'/../.env');

    }
}
