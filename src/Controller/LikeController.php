<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller
 * @Route("api/")
 */
class LikeController extends AbstractController
{
    /**
     * Retourne lintegralite des likes peut importe le contenue
     * @Route("like/lists", name="like", methods={"GET"})
     */
    public function getAllLikes(): Response
    {



        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/LikeController.php',
        ]);
    }
}
