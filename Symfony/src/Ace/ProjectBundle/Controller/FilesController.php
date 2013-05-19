<?php
// src/Ace/ProjectBundle/Controller/DiskFilesController.php

namespace Ace\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


abstract class FilesController extends Controller
{

    public abstract function createAction();

    public abstract function deleteAction($id);

    public abstract function listFilesAction($id);

    public abstract function createFileAction($id, $filename, $code);

    public abstract function getFileAction($id, $filename);

    public abstract function setFileAction($id, $filename, $code);

    public abstract function deleteFileAction($id, $filename);

    public abstract function renameFileAction($id, $filename, $new_filename);

    protected abstract function listFiles($id);

    protected function fileExists($id, $filename)
    {
        $list = $this->listFiles($id);
        foreach($list as $file)
        {
            if($file["filename"] == $filename)
                return json_encode(array("success" => true));
        }

        return json_encode(array("success" => false, "filename" => $filename, "error" => "File ".$filename." does not exist."));
    }

    protected function canCreateFile($id, $filename)
    {
        $fileExists = json_decode($this->fileExists($id,$filename),true);
        if(!$fileExists["success"])
            return json_encode(array("success" => true));
        else
            return json_encode(array("success" => false, "id" => $id, "filename" => $filename, "error" => "This file already exists"));


    }

}

