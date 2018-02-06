<?php
/**
 * Created by PhpStorm.
 * User: digital
 * Date: 06/02/2018
 * Time: 15:11
 */

namespace AppBundle\File;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $pathToProject;
    private $uploadDirectoryFile;

    public function __construct($pathToProject, $uploadDirectoryFile)
    {
        $this->pathToProject = $pathToProject;
        $this->uploadDirectoryFile = $uploadDirectoryFile;
    }

    public function upload(UploadedFile $file, $salt){

        $generatedFileName = time().'_'.$salt.'.'.$file->guessClientExtension();
        $path = $this->pathToProject.'/web'.$this->uploadDirectoryFile;

        $file->move($path, $generatedFileName);

        return $generatedFileName;
    }

}