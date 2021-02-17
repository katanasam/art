<?php

namespace App\Controller;

use App\Entity\Post;
use App\Controller\Hellper\TokenInsider;
use App\Repository\PostRepository;
use App\Services\PostServices;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class PostController
 * @package App\Controller
 * @Route("api/")
 */
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
    public function __construct(
        PostRepository $postRepository ,
        PostServices $postServices)
    {
        $this->postRepository = $postRepository;
        $this->postService = $postServices;

    }

    /**
     * recuperation de tous les posts dans la table
     * @Route("posts/lists",name ="list_posts", methods={"GET"})
     */
    public function getAllPosts( )
    : JsonResponse
    {
        return $this->json($this->postRepository->findAll(),200,[],["groups"=>"post_read"]);

    }


    /**
     * display un post unique avec images,commentaires,user
     * @Route("posts/{post_id<[0-9]+>}",name ="show_post", methods={"GET","POST"})
     * @Entity("post", expr="repository.find(post_id)")
     */
    public function showPost(
        Post $post)
    : JsonResponse
    {


        return $this->json($post,200,[
            'Content-type' => 'Application/json',
        ],['groups' => 'post_read']);
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @Route ("posts/",name ="create_post", methods={"POST"})
     */
    public function createPost(
        Request $request,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        EntityManagerInterface $em)
    : JsonResponse
    {
        // il faut etre un user pour creer un post
        $user =$this->getUser();


        try {

            //--- récupération du contenu
            $json_request = $request->getContent();

            //--- deserialize
            $post = $serializer->deserialize($json_request,Post::class,'json');


            //--- validation
            $errors = $validator->validate($post);
            if(count($errors) > 0){

                // envoie des erreur en json
                return $this->json( $errors,400);
            }

            $post->setAuthor($user);

            //--- register
            $em->persist($post);
            $em->flush();

            return $this->json($post,200,[
                'Content-type' => 'Application/json',
            ],['groups' => 'post_read']);




        }catch (NotEncodableValueException $notEncodableValueException){

            return $this->json([
                'statue' =>  400,
                'message' => $notEncodableValueException
            ],400);
        }
    }

    /**
     * @param Post $post
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @Route("posts/edit/{post_id<[0-9]+>}",name ="edit_post", methods={"PUT"})
     * @Entity("post", expr="repository.find(post_id)")
     */
    public function EditPost(
        Post $post,
        Request $request,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        EntityManagerInterface $em )
    : JsonResponse
    {
        //--- récuperation du post concerner
        //autorisation d'edition user

        try {

            //--- récupération du contenu
            $json_request = $request->getContent();
            //($json_request);

            //--- déserialize
            $serializer->deserialize($json_request,Post::class,'json',[AbstractNormalizer::OBJECT_TO_POPULATE => $post]);

            //--- register
            $em->flush();

            return $this->json($post,200,
                ['Content-type' => 'Application/json',
                ],['groups' => 'post_read']);

        }catch (NotEncodableValueException $notEncodableValueException){

            return $this->json([
                'statue' =>  400,
                'message' => $notEncodableValueException
            ],400);
        }
    }

    /**
     * @param Post $post
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @return JsonResponse
     * @Route("posts/{post_id<[0-9]+>}",name ="delete_post", methods={"DELETE"})
     * @Entity("post", expr="repository.find(post_id)")
     */
    public function deletePost(
        Post $post,
        EntityManagerInterface $em )
    : JsonResponse
    {

        //autorisation dedition user peut suprimer le post

        try {
            if ($post){

                //supression des images associer au post en base de donneées et dans le dossier

                //--- deregister
                $em->remove($post);
                $em->flush();

                return $this->json(["supression du post id {$post->getId()} "],200);
            }

        }catch (NotEncodableValueException $notEncodableValueException){

            return $this->json([
                'statue' =>  400,
                'message' => $notEncodableValueException
            ],400);
        }
    }

    /**
     * @return JsonResponse
     * @Route ("posts/user/lists",name ="list_user_posts", methods={"GET"})
     */
    public function getAllUserPosts()
    : JsonResponse
    {

        // récupération des données
        $all_user_posts = $this->postService->AllUserPosts($this->getUser());

        return $this->json($all_user_posts,200,[],["groups"=>"post_read"]);
    }


    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     * @Route ("posts/user",name ="create_user_post", methods={"POST"})
     */
    public function createUserPost(
        Request $request,
        ValidatorInterface $validator)
    : JsonResponse
    {
        try {
            $post = $this->postService->registerUserPost($this->getUser(),$request,$validator);

            return $this->json($post,200,[
                'Content-type' => 'Application/json',
            ],['groups' => 'post_read']);

        }catch (NotEncodableValueException $notEncodableValueException){

            return $this->json([
                'statue' => 400,
                'message' => $notEncodableValueException
            ],400);
        }
    }

    /**
     * @param Post $post
     * @param Request $request
     * @return JsonResponse
     * @Route("posts/user/edit/{post_id<[0-9]+>}",name ="edit_user_post", methods={"PUT"})
     * @Entity("post", expr="repository.find(post_id)")
     */
    public function EditUserPost(
        Post $post,
        Request $request)
    : JsonResponse
    {

        try {

            $post = $this->postService->registerModifUserPost($request,$post,$this->getUser());

            if(!$post instanceof Post){
                return $this->json([
                    'statue' =>  400,
                    'message' => 'ceci nest pas votre post !  ACCEES DENIED'
                ],400);
            }

            return $this->json($post,200,
                ['Content-type' => 'Application/json',
                ],['groups' => 'post_read']
            );


        }catch (NotEncodableValueException $notEncodableValueException){

            return $this->json([
                'statue' =>  400,
                'message' => $notEncodableValueException
            ],400);
        }
    }

    /**
     * @param Post $post
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @return JsonResponse
     * @Route("posts/user/{post_id<[0-9]+>}",name ="delete_user_post", methods={"DELETE"})
     * @Entity("post", expr="repository.find(post_id)")
     */
    public function deleteUserPost(
        Post $post,
        EntityManagerInterface $em )
    : JsonResponse
    {

        //autorisation dedition user peut suprimer le post


            if ($post){

                //supression des images associer au post en base de donneées et dans le dossier

                //--- deregister
                $em->remove($post);
                $em->flush();

                return $this->json(["supression du post id {$post->getId()} "],200);
            }

    }
}
