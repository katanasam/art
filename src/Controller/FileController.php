<?php

namespace App\Controller;

use App\Services\FileManager;
use App\Services\FileServices;
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
     * @var FileManager
     */
    private  $fileManager;

    /**
     * @var FileServices
     */
    private  $fileServices;


    public function __construct(FileManager $fileManager, FileServices $fileServices)
    {
        $this->fileManager = $fileManager;
        $this->fileServices = $fileServices;
    }


    /**
     * @Route("file", name="get_file",methods={"GET"})
     */
    public function getFile(Request $request): JsonResponse
    {

        return $this->json([
            'message' => 'le fichier a bien été enregistrer!',
        ],200);
    }

    /**
     * enregistre un fichier ou un ensemble de fichier
     * @param Request $request
     * @Route("file", name="add_file",methods={"POST"})
     * @return JsonResponse
     */
    public function AddFile(Request $request): JsonResponse
    {

        $file = $this->fileServices->addFileOnLibrary($request);

        return $this->json([
            'message' => 'le fichier a ['.$file->getClientOriginalName().']! SUCCESS.',
        ],200);
    }


    /**
     * @param Request $request
     * @Route("file/{type}/{content_id}", name="add_file_on",methods={"POST"})
     * @return JsonResponse
     */
    public function AddFileOn(Request $request, $type,$content_id): JsonResponse
    {

        // lowercase et controle
        // verification que le contenu soit le sien avant d'ajouter


        // récup images
        $file =$request->files->get('image');
//         $file = $request->files->all();

//        dd($file);
//        if(is_array($file)){
//            dd($file);
//        }
//
//        // dump($request->files->all());
//        // dd(count($file));
//
//        // nommage du file
//
//        $output = [];
//        $content = '' ;
//
//        while ($file  ){

            $filename = $this->fileManager->renameFile($file,$this->getUser(),'post');
            $location = $this->fileManager->fileLocation($filename,$this->getUser(),'post');
            $content = $this->fileServices->linkImgToContent('Post',$content_id, $filename,$location);

            // déplacement
            $file->move( $this->fileServices->gessDirectory($this->getUser(),$type),$filename);

//            $output[] = $content;
//        }


        return $this->json($content,200,[
            'Content-type' => 'Application/json',
        ],['groups' => 'post_read']);
    }


    /**
     * @param Request $request
     * @Route("file/{type}/{content_id}", name="del_file_on",methods={"DELETE"})
     * @return JsonResponse
     */
    public function deleteFileOn(Request $request, $type,$content_id): JsonResponse
    {

        // lowercase et controle
        // verification que le contenu soit le sien avant d'ajouter


        // récup images
        $file =$request->files->get('image');

        // dump($request->files->all());
        // dd(count($file));

        // nommage du file
        // $filename = time().'-'.$file->getClientOriginalName().'.'.$file->guessClientExtension();
        $filename = $this->fileManager->renameFile($file,$this->getUser(),'post');
        $location = $this->fileManager->fileLocation($filename,$this->getUser(),'post');

        $content = $this->fileServices->linkImgToContent('Post',$content_id, $filename,$location);

        // déplacement
        $file->move( $this->fileServices->gessDirectory($this->getUser(),$type),$filename);


        return $this->json($content,200,[
            'Content-type' => 'Application/json',
        ],['groups' => 'post_read']);
    }
}
