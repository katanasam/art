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
use phpDocumentor\Reflection\Types\Object_;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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
     * persist et fluch un objet en base de données
     * @param  $object
     */
    public  function PF($object){
        //--- register
        //dd($object);
        $this->entityManager->persist($object);
        $this->entityManager->flush();

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

        try {

            //--- récupération du contenu
            $post = $this->getSerializer($request,Post::class);

            //--- validation
//            $errors = $validator->validate($post);
//            if(count($errors) > 0){
//
//                // envoie des erreur en json
//                return $this->json( $errors,400);
//            }
            $this->getDataValidate($post);

            $post->setAuthor($user);
            $this->PF($post);





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


}