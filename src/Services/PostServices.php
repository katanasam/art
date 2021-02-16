<?php
/**
 * Created by PhpStorm.
 * User: cheik
 * Date: 04/02/2021
 * Time: 19:43
 */

namespace App\Services;


use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class PostServices
 * @package App\Services
 */
class PostServices extends GeneralServices
{
    /**
     * post repository
     */
    private $postRP;

    /**
     * Entity manager
     */
    protected $entityManager;

    /**
     * PostServices constructor.
     * @param PostRepository $postRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        PostRepository $postRepository,
        EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->postRP = $postRepository;

    }



    /**
     * Récuperation de tous les posts d'un user
     * @param Object $user
     * @return EntityManagerInterface
     */
    public function AllUserPosts(Object $user)
    {

        $all_user_posts = $this->postRP->findPostByUser($user->getId());

        return $all_user_posts;
    }

    /**
     * Enrefistre le post dun user
     * @param object $user
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function registerUserPost(object $user,Request $request,ValidatorInterface $validator ){


        //--- récupération du contenu
        $post = $this->getSerializer($request,Post::class);

        //--- validation
        $errors = $validator->validate($post);
        $this->countAllErrors($errors);

        $post->setAuthor($user);
        $this->PersistAndFlush($post);

        return $post;

    }
}