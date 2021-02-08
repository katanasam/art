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
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
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
    public function getAllPost(): Response
    {

        // récuperation des données
        $all_posts = $this->postRepository->findAll();

        // il manque le champ pour l'auteur du post
        //dd($all_posts);

        // envoie d'une réponse à l'Api
        return $this->json($all_posts,200,[],["groups"=>"post_read"]);

    }

    /**
     * recuperation de tous les posts dans la table
     * @Route("/posts/{id<[0-9]+>}",name ="show_post", methods={"GET","POST"})
     */
    public function showPost(Post $post): Response
    {
        $post = $this->postRepository->find($post->getId());
//        /dump($post->getImages()->getValues());

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
        // recup images
//        dd($request);
//        $file =$request->files->get('image');
//
//        //  nomage du file
//        $filename = time().'-'.$file->getClientOriginalName().'.'.$file->guessClientExtension();
//
//        // et deplacement
//        $file->move( 'public',$filename);





        try {

            //--- récupération du contenu
            $json_request = $request->getContent();

            //--- deserialize
            $post = $serializer->deserialize($json_request,Post::class,'json');


            //--- validation
            $errors = $validator->validate($post);
            if(count($errors) < 0){

                // envoie des erreur en json
                return $this->json([
                    'statue' =>  400,
                    'message' => $errors
                ],400);
            }


            //--- register
            $em->persist($post);
            $em->flush();

            return $this->json($post,200,[
                'Content-type' => 'Application/json',
            ],['groups' => 'post:read']);

        }catch (NotEncodableValueException $notEncodableValueException){

            return $this->json([
                'statue' =>  400,
                'message' => $notEncodableValueException
             ],400);
        }


    }
}
