<?php
namespace App\Services;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class FileServices
 */
class FileServices extends \App\Services\GeneralServices {

    const PUBLIC = 'public/';

    public function __construct(\Symfony\Component\Serializer\Normalizer\NormalizerInterface $normalizer, \Doctrine\ORM\EntityManagerInterface $entityManager, \Symfony\Component\Validator\Validator\ValidatorInterface $validator, ManagerRegistry $managerRegistry)
    {
        parent::__construct($normalizer, $entityManager, $validator, $managerRegistry);
    }

    public function linkImgToContent($type,$content_id, $filename) {


        // recuperation du repository en fonction du type
        // ajout de limage au contenue
        // sauvegarde du contenue
        // renvoi dans la reponse
        // le user doit etre le creator

        $content = $this->managerRegistry->getRepository($this->returnClassName($type))->find($content_id);

       $image = new \App\Entity\Image();
       $image->setImageName($filename);
       $image->setTypeContent($type);
       //

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

