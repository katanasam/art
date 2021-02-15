<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller
 * @Route("api/")
 */
class FileController extends AbstractController
{
    /**
     * @Route("file", name="file",methods={"GET"})
     */
    public function getfile(Request $request): JsonResponse
    {

        return $this->json([
            'message' => 'le fichier a bien été enregistrer!',
        ],200);
    }

    /**
     * @param Request $request
     * @Route("file", name="file",methods={"POST"})
     * @return JsonResponse
     */
    public function Addfile(Request $request): JsonResponse
    {

        // récup images
        //dd($request);
        $file =$request->files->get('image');

        //  nommage du file
        $filename = time().'-'.$file->getClientOriginalName().'.'.$file->guessClientExtension();

        // et déplacement
        $file->move( 'public',$filename);


        return $this->json([
            'message' => 'le fichier a ['.$file->getClientOriginalName().']! SUCCESS.',
        ],200);
    }
}
