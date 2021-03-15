<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Services\PostServices;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class PostController
 * @package App\Controller
 * @Route("api/post")
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
        PostRepository $postRepository,
        PostServices $postServices)
    {
        $this->postRepository = $postRepository;
        $this->postService = $postServices;
    }


    /**
     * Renvoie les posts d'un seul user
     * @return JsonResponse
     * @Route ("/lists/user",name ="list_user_posts", methods={"GET"})
     */
    public function getAllUserPosts( ) : JsonResponse
    {
        // renvoie les post du user connecter , manque les posts d'un user en particulier. $user

            // récupération des données
            $all_user_posts = $this->postService->AllUserPosts($this->getUser());


        return $this->json($all_user_posts,200,[],["groups"=>"post_read"]);
    }

    /**
     *  creation d un post par un user
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     * @Route ("/user",name ="create_user_post", methods={"POST"})
     */
    public function createUserPost(
        Request $request,
        ValidatorInterface $validator)
    : JsonResponse
    {
        try {
            // enregistre le post et controle Validation
            $post = $this->postService->registerUserPost($this->getUser(),$request,$validator);

            return $this->json($post,200,[
                'Content-type' => 'Application/json',
            ],['groups' => 'post_read']);

        }catch (NotEncodableValueException $notEncodableValueException){

            return $this->json([
                'status' => 400,
                'message' => $notEncodableValueException
            ],400);
        }
    }

    /**
     * Modifiaction d'un post existant
     * @param Post $post
     * @param Request $request
     * @return JsonResponse
     * @Route("/user/edit/{post_id<[0-9]+>}",name ="edit_user_post", methods={"PUT"})
     * @Entity("post", expr="repository.find(post_id)")
     */
    public function EditUserPost(
        Post $post,
        Request $request)
    : JsonResponse
    {

        try {

            $post = $this->postService->registerModifUserPost($request,$post,$this->getUser());

            // si n'i le poste récuperer est en base de données
            // si n'i l'utilisateur a l'authorisation de modifier le post
            if(!$post instanceof Post){
                return $this->json([
                    'status' =>  400,
                    'message' => 'ceci n-est pas votre POST !  ACCEES DENIED'
                ],400);
            }

            return $this->json($post,200,
                ['Content-type' => 'Application/json',
                ],['groups' => 'post_read']
            );


        }catch (NotEncodableValueException $notEncodableValueException){

            return $this->json([
                'status' =>  400,
                'message' => $notEncodableValueException
            ],400);
        }
    }

    /**
     * suprime le post , les images , les commentaires ,les likes
     * @param Post $post
     * @return JsonResponse
     * @Route("/user/{post_id<[0-9]+>}",name ="delete_user_post", methods={"DELETE"})
     * @Entity("post", expr="repository.find(post_id)")
     */
    public function deleteUserPost(
        Post $post)
    : JsonResponse
    {

        //--- autorisation suprimer le post et les image lier ainsi quue les commentaires et les likes
        $this->postService->deletePostUserAndAllLinks($post,$this->getUser());

        return $this->json(["Supression du post id "],200);

    }
}
