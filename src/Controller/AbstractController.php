<?php

namespace App\Controller;

use App\Model\UserManager;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Initialized some Controller common features (Twig...)
 */
abstract class AbstractController
{
    protected Environment $twig;


    public function __construct()
    {
        $loader = new FilesystemLoader(APP_VIEW_PATH);
        $this->twig = new Environment(
            $loader,
            [
                'cache' => false,
                'debug' => true,
            ]
        );
        $userManager = new UserManager();
        $this->userData = isset($_SESSION['user_id']) ? $userManager->selectOneById($_SESSION['user_id']) : false;

        $this->twig->addGlobal('user', $this->userData);

        $this->twig->addExtension(new DebugExtension());
    }
}
