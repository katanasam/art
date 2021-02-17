<?php

namespace App\Controller\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AuthenticationController
 * @package App\Controller\Security
 * @Route("/api/auth/")
 */
class AuthenticationController extends AbstractController
{
    /**
     * Entity manager
     */
    protected $entityManager;

    /**
     * @var ValidatorInterface
     */
    protected $validator;


    /**
     * GeneralServices constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator )
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @Route("login_check", name="user_login" , methods={"POST"})
     */
    public function login(): Response
    {
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
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return Response
     * @Route("register", name="user_register", methods={"POST"})
     */
    public function registerUser(
        Request $request ,
        SerializerInterface $serializer,
        UserPasswordEncoderInterface  $userPasswordEncoder): Response
    {

        $user = $serializer->deserialize($request->getContent(),User::class,'json');
        $user->setPassword($userPasswordEncoder->encodePassword($user,$user->getPassword()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json($user,200,
            [
                'Content-type' => 'Application/json',
            ]);
    }
}
