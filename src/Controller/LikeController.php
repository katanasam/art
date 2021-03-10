<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Post;
use App\Repository\LikeRepository;
use App\Services\LikeServices;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
class LikeController extends AbstractController
{
    private $likeRepository ;

    private $likeService;

    public function __construct(LikeRepository $likeRepository,
                                LikeServices $likeServices)
    {
        $this->likeRepository = $likeRepository;
        $this->likeService = $likeServices;
    }



    /**
     * Retourne lintegralite des likes peut importe le contenue
     * @Route("like/lists", name="like", methods={"GET"})
     */
    public function getAllLikes(): Response
    {


        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/LikeController.php',
        ]);
    }


    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param $type
     * @param $content_id
     * @return JsonResponse
     * @Route ("like/{type}/{content_id}",name ="like_up", methods={"GET"})
     * @ParamConverter("type", converter="RouteParaConverter")
     */
    public function likeOn(
        Request $request,
        ValidatorInterface $validator,
        $type,$content_id
    )
    : JsonResponse
    {
        try {
//            $like = $this->likeService->registerlike($this->getUser(),
//                    $request,$validator,$type,$content_id);

            $like = new  Like();
            $like->setAuthor($this->getUser());
            $post = $this->getDoctrine()->getRepository(Post::class)->find($content_id);

            $like->setPost($post);
            $like->setType($type);
            $like->setCreatedAt(new \DateTimeImmutable());

            $this->getDoctrine()->getManager()->persist($like);
            $this->getDoctrine()->getManager()->flush();


            return $this->json([$post,$like],200,[
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
     * @param $type
     * @param $content_id
     * @return JsonResponse
     * @Route ("like//{type}/{content_id}",name ="like_Down", methods={"DELETE"})
     */
    public function likeDown(
        $type,$content_id
    )
    : JsonResponse
    {
        try {

            // quelle type de content correspond
            $post = $this->getDoctrine()->getRepository(Post::class)->find($content_id);

            if ($post->getLikes()){
                foreach ( $post->getLikes()->getValues() as $like){

                   // dd($like->getAuthor()->getUsername());
                    if ($like->getAuthor()->getUsername() == $this->getUser()->getUsername()){
                        $post->removeLike($like);
                        $this->getDoctrine()->getManager()->remove($like);
                        $this->getDoctrine()->getManager()->flush();
                    }
                }
            }

            return $this->json([$post],200,[
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
