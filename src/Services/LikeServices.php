<?php
/**
 * Created by PhpStorm.
 * User: cheik
 * Date: 05/03/2021
 * Time: 20:23
 */

namespace App\Services;


use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LikeServices extends GeneralServices
{

    private $likeRepo;

    /**
     * LikeServices constructor.
     * @param NormalizerInterface $normalizer
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param ManagerRegistry $managerRegistry
     * @param LikeRepository $likeRepository
     */
    public function __construct(NormalizerInterface $normalizer,
                                EntityManagerInterface $entityManager,
                                ValidatorInterface $validator,
                                ManagerRegistry $managerRegistry,
                                LikeRepository $likeRepository)
    {
        parent::__construct($normalizer, $entityManager, $validator, $managerRegistry);

        $this->likeRepo = $likeRepository;
    }


    /**
     * @param object $user
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return mixed
     */
    public function registerlike(object $user,
                                    Request $request,
                                    ValidatorInterface $validator,
                                    $type,$content_id){

        //--- récupération du contenu
        $like = $this->getSerializer($request,Like::class);

        //--- validation
        $errors = $validator->validate($like);
        $this->countAllErrors($errors);

        $this->setTypeContent($like,  $type,  $content_id);
        $like->setAuthor($user);
        //  dd($comment);

        $this->PersistAndFlush($like);

        return $like;

    }

    public function setTypeContent(Comment $comment,string  $type, int $content_id){
//
//        switch ($type){
//
//            case 'post':
//
//                $post = $this->managerRegistry->getRepository(Post::class)->find($content_id);
//                return $comment->setPost($post);
//                break;
//
//
//            default :
//                return false;
//        }
//
//
    }



}