<?php
/**
 * Created by PhpStorm.
 * User: cheik
 * Date: 05/03/2021
 * Time: 20:22
 */

namespace App\Services;


use App\Entity\Comment;
use App\Entity\Post;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentServices extends GeneralServices
{

    private $comRepo;

    public function __construct(NormalizerInterface $normalizer,
                                EntityManagerInterface $entityManager,
                                ValidatorInterface $validator,
                                ManagerRegistry $managerRegistry,
                                CommentRepository $commentRepository)
    {
        parent::__construct($normalizer, $entityManager, $validator, $managerRegistry);

        $this->comRepo = $commentRepository;
    }

    /**
     * @param object $user
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return mixed
     */
    public function registerComment(object $user,
                                    Request $request,
                                    ValidatorInterface $validator,
                                    $type,$content_id){

        //--- récupération du contenu
        $comment = $this->getSerializer($request,Comment::class);

        //--- validation
        $errors = $validator->validate($comment);
        $this->countAllErrors($errors);

        $this->setTypeContent($comment,  $type,  $content_id);
        $comment->setAuthor($user);
     //  dd($comment);

        $this->PersistAndFlush($comment);

        return $comment;

    }

    public function setTypeContent(Comment $comment,string  $type, int $content_id){

        switch ($type){

            case 'post':

                $post = $this->managerRegistry->getRepository(Post::class)->find($content_id);
                return $comment->setPost($post);
              break;


            default :
                return false;
        }


    }


}