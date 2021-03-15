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
use App\Repository\CommentRepository;
use App\Repository\ImageRepository;
use App\Repository\LikeRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;


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
     * @var ImageRepository
     */
    private $imageRP;

    /**
     * @var CommentRepository
     */
    private $commnentRP;

    /**
     * @var LikeRepository
     */
    private $likeRP;

    /**
     * PostServices constructor.
     * @param NormalizerInterface $normalizer
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param ManagerRegistry $managerRegistry
     * @param PostRepository $postRepository
     * @param ImageRepository $imageRepository
     */
    public function __construct(NormalizerInterface $normalizer,
                                EntityManagerInterface $entityManager,
                                ValidatorInterface $validator,
                                ManagerRegistry $managerRegistry,
                                PostRepository $postRepository,
                                ImageRepository $imageRepository,
                                CommentRepository $commentRepository,
                                LikeRepository $likeRepository)
    {
        parent::__construct($normalizer, $entityManager, $validator, $managerRegistry);


        $this->postRP = $postRepository;
        $this->imageRP = $imageRepository;
        $this->commnentRP = $commentRepository;
        $this->likeRP = $likeRepository;

    }

    /**
     * Enregistre le post dun user
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function registerPost(Request $request,ValidatorInterface $validator ){

        //--- récupération du contenu
        $post = $this->getSerializer($request,Post::class);

        //--- validation
        $errors = $validator->validate($post);
        $this->countAllErrors($errors);

        $this->PersistAndFlush($post);

        return $post;

    }

    /**
     * @param Request $request
     * @param Post $post
     * @return bool|mixed
     */
    public  function  registerModifPost(Request $request,Post $post){

        //--- récuperation du post concerner

        //--- déserialize
        $post_modify = $this->getSerializer($request,Post::class,'json',[AbstractNormalizer::OBJECT_TO_POPULATE => $post]);

        //--- autorisation d'édition user

            $this->entityManager->flush();
            return $post_modify;
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
     * Enregistre le post dun user
     * @param object $user
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function registerUserPost(object $user,Request $request,ValidatorInterface $validator ){

        //--- récupération du contenu
        $post = $this->getSerializer($request,Post::class);

        //--- validation
        //-- validation basée sur les @Assert dans lentité
        $errors = $validator->validate($post);

        //--  compte juste les erreurs
        $this->countAllErrors($errors);

        //-- ajout le l'author du post
        $post->setAuthor($user);
        $this->PersistAndFlush($post);

        return $post;

    }

    /**
     * @param Request $request
     * @param Post $post
     * @param User $user
     * @return bool|mixed
     */
    public  function  registerModifUserPost(Request $request,Post $post,User $user){

        //--- récuperation du post concerner

        //--- déserialize
        $post_modify = $this->getSerializer($request,Post::class,'json',[AbstractNormalizer::OBJECT_TO_POPULATE => $post]);

        //--- autorisation d'édition user

        if($user->getid() === $post_modify->getAuthor()->getid()){
            $this->entityManager->flush();
            return $post_modify;
        }

        //--- return une erreur
        return  false;
    }

    /**
     * @param Post $post
     * @param User $user
     */
    public function deletePostUserAndAllLinks(Post $post,User $user){


        //supréssion des images associer au post en base de donneées et dans le dossier
        if($user->getId() === $post->getAuthor()->getId()) {

            // 1- supressions des images
            while ( !empty($post->getImages()->getValues())){

                $img = $this->imageRP->findBy(['id' =>$post->getImages()->first()->getId()])[0];
                unlink($post->getImages()->current()->getLocation());

                $post->removeImage($post->getImages()->current());

                 $this->entityManager->remove($img);
            }

            // 2- supressions des commentaires

            while (!empty($post->getComment()->getValues())){

                $commment = $this->commnentRP->findBy(['id' =>$post->getComment()->first()->getId()])[0];

                $post->removeComment($post->getComment()->current());

                $this->entityManager->remove($commment);
            }

            // 3- supressions des likes
            while ( !empty($post->getLikes()->getValues())){

                $like = $this->likeRP->findBy(['id' =>$post->getLikes()->first()->getId()])[0];

                $post->removeLike($post->getLikes()->current());

                $this->entityManager->remove($like);
            }

            $this->RemoveAndFlush($post);

        }

    }

}