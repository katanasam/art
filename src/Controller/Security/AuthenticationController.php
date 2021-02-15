<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthenticationController
 * @package App\Controller\Security
 * @Route("/api/auth/")
 */
class AuthenticationController extends AbstractController
{
    /**
     * @Route("login_check", name="user_login")
     */
    public function login(): JsonResponse
    {
        dd($this);
        return $this->json([
            'message' => 'login route',
        ]);
    }

    /**
     * @Route("logout", name="user_logout" , methods={"GET"})
     */
    public function Logout(): Response
    {
        return $this->json([
            'message' => 'log out',
        ]);
    }

    /**
     * @Route("register", name="user_register", methods={"POST"})
     */
    public function registerUser(): Response
    {
        return $this->json([
            'message' => ' register USER',
        ]);
    }
}
