<?php

namespace App\Controller;

use App\Entity\Post;
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
        PostRepository $postRepository,
        PostServices $postServices)
    {
        $this->postRepository = $postRepository;
        $this->postService = $postServices;
    }

//    /**
//     * Récupération de tous les posts dans la table
//     * @Route("posts/lists",name ="list_posts", methods={"GET"})
//     */
//    public function getAllPosts( )
//    : JsonResponse
//    {
//        return $this->json($this->postRepository->findAll(),200,[],["groups"=>"post_read"]);
//    }
//
//
//    /**
//     * display un post unique avec images,commentaires,user
//     * @Route("posts/{post_id<[0-9]+>}",name ="show_post", methods={"GET","POST"})
//     * @Entity("post", expr="repository.find(post_id)")
//     */
//    public function showPost(
//        Post $post)
//    : JsonResponse
//    {
//
//        return $this->json($post,200,[
//            'Content-type' => 'Application/json',
//        ],['groups' => 'post_read']);
//    }
//
//    /**
//     * Creation dun post unique
//     * @param Request $request
//     * @param ValidatorInterface $validator
//     * @return JsonResponse
//     * @Route ("posts/",name ="create_post", methods={"POST"})
//     */
//    public function createPost(
//        Request $request,
//        ValidatorInterface $validator
//    )
//    : JsonResponse
//    {
//
//        try {
//
//            $post = $this->postService->registerPost($request,$validator);
//
//            return $this->json($post,200,[
//                'Content-type' => 'Application/json',
//            ],['groups' => 'post_read']);
//
//
//        }catch (NotEncodableValueException $notEncodableValueException){
//
//            return $this->json([
//                'statue' =>  400,
//                'message' => $notEncodableValueException
//            ],400);
//        }
//    }
//
//    /**
//     * Modifications du post
//     * @param Post $post
//     * @param Request $request
//     * @return JsonResponse
//     * @Route ("posts/edit/{post_id<[0-9]+>}",name ="edit_post", methods={"PUT"})
//     * @Entity ("post", expr="repository.find(post_id)")
//     */
//    public function EditPost(
//        Post $post,
//        Request $request)
//    : JsonResponse
//    {
//        //--- récuperation du post concerner
//        //autorisation d'edition user
//
//        try {
//
//            $post = $this->postService->registerModifPost($request,$post);
//
//
//
//            return $this->json($post,200,
//                ['Content-type' => 'Application/json',
//                ],['groups' => 'post_read']);
//
//        }catch (NotEncodableValueException $notEncodableValueException){
//
//            return $this->json([
//                'statue' =>  400,
//                'message' => $notEncodableValueException
//            ],400);
//        }
//    }
//
//    /**
//     * Delete un seul post
//     * @param Post $post
//     * @return JsonResponse
//     * @Route("posts/{post_id<[0-9]+>}",name ="delete_post", methods={"DELETE"})
//     * @Entity("post", expr="repository.find(post_id)")
//     */
//    public function deletePost(
//        Post $post)
//    : JsonResponse
//    {
//
//        //autorisation dedition user peut suprimer le post
//
//        try {
//            if ($post){
//
//                //supression des images associer au post en base de donneées et dans le dossier
//
//                //--- deregister
//               $this->postService->RemoveAndFlush($post);
//
//                return $this->json(["Supression du post id {$post->getId()} "],200);
//            }
//
//        }catch (NotEncodableValueException $notEncodableValueException){
//
//            return $this->json([
//                'statue' =>  400,
//                'message' => $notEncodableValueException
//            ],400);
//        }
//    }

    /**
     * Renvoie les posts d'un seul user
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
     * @return JsonResponse
     * @Route("posts/user/{post_id<[0-9]+>}",name ="delete_user_post", methods={"DELETE"})
     * @Entity("post", expr="repository.find(post_id)")
     */
    public function deleteUserPost(
        Post $post)
    : JsonResponse
    {

        //--- autorisation dedition user peut suprimer le post
        $this->postService->deletePostUserAndAllImage($post,$this->getUser());

        return $this->json(["Supression du post id "],200);

    }
}
