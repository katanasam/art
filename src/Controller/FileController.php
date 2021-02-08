<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends AbstractController
{
    /**
     * @Route("/file", name="file",methods={"GET"})
     */
    public function getfile(Request $request): Response
    {

        return $this->json([
            'message' => 'le fichier a bien été enregistrer!',
        ],200);
    }

    /**
     * @Route("/file", name="file",methods={"POST"})
     */
    public function Addfile(Request $request): Response
    {

        // recup images
//        /dd($request);
        $file =$request->files->get('image');

        //  nomage du file
        $filename = time().'-'.$file->getClientOriginalName().'.'.$file->guessClientExtension();

        // et deplacement
        $file->move( 'public',$filename);


        return $this->json([
            'message' => 'le fichier a ['.$file->getClientOriginalName().']!',
        ],200);
    }
}
