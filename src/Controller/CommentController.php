<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use App\Services\CommentServices;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class PostController
 * @package App\Controller
 * @Route("api/")
 */
class CommentController extends AbstractController
{
    private $comRepository;


    private $comService;

    /**
     * CommentController constructor.
     * @param CommentRepository $commentRepository
     * @param CommentServices $commentServices
     */
    public function __construct(CommentRepository $commentRepository,
    CommentServices $commentServices)
    {
        $this->comRepository = $commentRepository;

        $this->comService =  $commentServices;
    }

    /**
     * Retourne lintegraliter des comments peut importe le type de contenus
     * @Route("comment/lists", name="comment", methods={"GET"})
     */
    public function getAllComments(): Response
    {

        return $this->json($this->comRepository->findAll(),200,[],["groups"=>"com_read"]);

    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     * @Route ("comment/{type}/{content_id}",name ="create_comment", methods={"POST"})
     */
    public function createCommentOn(
        Request $request,
        ValidatorInterface $validator,
        $type,$content_id
        )
    : JsonResponse
    {
       // dd($content_id,$type);
        try {
            $comment = $this->comService->registerComment($this->getUser(),$request,$validator,$type,$content_id);

            return $this->json($comment,200,[
                'Content-type' => 'Application/json',
            ],['groups' => 'post_read']);

        }catch (NotEncodableValueException $notEncodableValueException){

            return $this->json([
                'statue' => 400,
                'message' => $notEncodableValueException
            ],400);
        }
    }
}
