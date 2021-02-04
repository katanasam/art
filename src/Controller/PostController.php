<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Services\PostServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostController extends AbstractController
{
    /**
     * @var PostRepository
     */
    private $postRepository;
    /**
     * @var PostServices
     */
    private  $postService;

    /**
     * PostController constructor.
     * @param PostRepository $postRepository
     * @param PostServices $postServices
     */
    public function __construct(PostRepository $postRepository ,PostServices $postServices)
    {
        $this->postRepository = $postRepository;
        $this->postService = $postServices;
    }

    /**
     * recuperation de tous les posts dans la table
     * @Route("/posts/",name ="list_posts", methods={"GET"})
     */
    public function index(): Response
    {
        // récuperation des données
        $all_posts = $this->postRepository->findAll();

        // il manque le champ pour l'auteur du post


        // envoie d'une reponse à l'Api
        return $this->json($all_posts,200,[
            'Content-type' => 'Application/json',
        ],['groups' => 'post:read']);
    }

    /**
     * recuperation de tous les posts dans la table
     * @Route("/posts/{id<[0-9]+>}",name ="show_post", methods={"GET","POST"})
     */
    public function showPost(Post $post): Response
    {
        $post = $this->postRepository->find($post->getId());

        return $this->json($post,200,[
            'Content-type' => 'Application/json',
        ],['groups' => 'post:read']);
    }


    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @return Response
     * @Route("/posts/",name ="create_post", methods={"POST"})
     */
    public function createPost(Request $request,ValidatorInterface $validator,SerializerInterface $serializer, EntityManagerInterface $em): Response
    {
        // recuperation du contenut
        // recup images
        // validation
        // register

        // récupération du contenu
        $json_request = $request->getContent();


        // deserialize
        // et transphormation en objet
        $post = $serializer->deserialize($json_request,Post::class,'json');

        $em->persist($post);
        $em->flush();


        return $this->json($post,200,[
            'Content-type' => 'Application/json',
        ]);
    }
}
