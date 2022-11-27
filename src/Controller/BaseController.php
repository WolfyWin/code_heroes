<?php

namespace App\Controller;

use App\Repository\DungeonRepository;
use App\Repository\QuestionRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use Twig\Environment;

class BaseController extends AbstractController
{
    public static $session;

    public function __construct( Environment $twig )
    {
        if( session_status() === PHP_SESSION_NONE )
        {
            session_start();
        }

        $twig->addGlobal('connected', $_SESSION['connected'] ?? false );
        $twig->addGlobal('user',      $_SESSION['user']      ?? [] );
    }
}