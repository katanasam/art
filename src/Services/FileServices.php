<?php
namespace App\Services;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class FileServices
 */
class FileServices extends \App\Services\GeneralServices {


    const PUBLIC = 'public/';

    /**
     * @var FileManager
     */
    private  $fileManager;


    /**
     * FileServices constructor.
     * @param \Symfony\Component\Serializer\Normalizer\NormalizerInterface $normalizer
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     * @param ManagerRegistry $managerRegistry
     * @param FileManager $fileManager
     */
    public function __construct(\Symfony\Component\Serializer\Normalizer\NormalizerInterface $normalizer, \Doctrine\ORM\EntityManagerInterface $entityManager, \Symfony\Component\Validator\Validator\ValidatorInterface $validator, ManagerRegistry $managerRegistry,FileManager $fileManager)
    {
        parent::__construct($normalizer, $entityManager, $validator, $managerRegistry);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function  addFileOnLibrary(Request $request ){

        // create une  image instance
        // enregistre limage

        $file =$request->files->get('image');

        // nommage du file
        $original =str_replace(' ','_',$file->getClientOriginalName());

        $filename = 'library_'.time().'_'.$original;


        $file->move('public/library/',$filename);

        return $file;
    }


    public function linkImgToContent($type,$content_id, $filename,$location) {

        // recuperation du repository en fonction du type
        // ajout de limage au contenue
        // sauvegarde du contenue
        // renvoi dans la reponse
        // le user doit etre le creator

        $content = $this->managerRegistry->getRepository($this->returnClassName($type))->find($content_id);

       $image = new \App\Entity\Image();
       $image->setImageName($filename);
       $image->setTypeContent($type);
       $image->setLocation($location);

        // dump($content);
        $image->setPost($content);

        // dd($image);
        $this->PersistAndFlush($image);


        if (!$content) {
            return false;
        }

        return $content;
    }


    /**
     * @param string $className
     * @return string
     */
    public function returnClassName(string $className){
              return Post::class;
    }


    /**
     * @param User $user
     * @param string $type
     * @return string
     */
    public  function gessDirectory(User $user ,string $type){
        $directory = 'public/'.$user->getUsername();

        if (file_exists($directory)) {

            // en fonction du type

            $directory .= DIRECTORY_SEPARATOR.$type;

            if (file_exists($directory)){

                return $directory;
            }
            else{

               // $directory_2 = $directory.DIRECTORY_SEPARATOR.$type;
                // le segond directory

                mkdir($directory, 0700);

                return $directory;
            }

        } else {
           return $this->createDirectory($user,$type);
        }

    }


    /**
     * @param User $user
     * @param string $type
     * @return string
     */
    public  function createDirectory(User $user,string  $type){

        $directoryName = "public/" .$user->getUsername();

        // le premier directory
        mkdir($directoryName, 0700);

        $directoryName_2 = $directoryName.DIRECTORY_SEPARATOR.$type;
        // le segond directory
        mkdir($directoryName_2, 0700);

        return $directoryName_2;


    }


    public  function deleteDirectory(User $user){
        $directoryName = "public/" .$user->getUsername();
        if (!is_dir($directoryName)) {
            rmdir($directoryName);
        }
    }
}

