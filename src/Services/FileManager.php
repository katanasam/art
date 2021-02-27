<?php
/**
 * Created by PhpStorm.
 * User: cheik
 * Date: 12/02/2020
 * Time: 05:16
 */
namespace App\Services;
use App\Entity\User;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

class FileManager extends Filesystem
{

    public function getDirectory(){
        $directory = '../hopcare-data/PIECES_JOINTES/RAPPORT/FNE';
        return $directory;
    }

    public function uploadFile (UploadedFile $uploadedFile){

        $directory = $this->getDirectory();
        $filename = md5(uniqid()).'.'.$uploadedFile->guessExtension();
        dump($filename);

        // deplacement
        $uploadedFile->move($directory,$filename);

        return $filename;
    }


    public function renameFile(UploadedFile $uploadedFile,UserInterface $user,$type){
        //  nommage du file
        //return $filename = $user->getUsername().'_'.$type.'_'.time().'-'.$uploadedFile->getClientOriginalName().'.'.$uploadedFile->guessClientExtension();
        return $filename = $user->getUsername().'_'.$type.'_'.time().'.'.$uploadedFile->guessClientExtension();

    }

    public function fileLocation($filename,UserInterface $user,$type){
        //  nommage du file
        //return $filename = $user->getUsername().'_'.$type.'_'.time().'-'.$uploadedFile->getClientOriginalName().'.'.$uploadedFile->guessClientExtension();
        return $location = 'public/'.$user->getUsername().'/'.$type.'/'.$filename;

    }
    public function deleteFile($filename){
        unlink($filename);

    }
}