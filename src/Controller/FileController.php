<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends AbstractController
{
    /**
    * @Route("/createdirectory", name="my_file")
    */
    public function createdir(): Response
    {   
        $fsObject= new Filesystem();
        $current_dir_path = getcwd();

        try {
            $new_dir_path = $current_dir_path . "/foo";
         
            if (!$fsObject->exists($new_dir_path))
            {
                $old = umask(0);
                $fsObject->mkdir($new_dir_path, 0775);
                $fsObject->chown($new_dir_path, "www-data");
                $fsObject->chgrp($new_dir_path, "www-data");
                umask($old);
            }
            return new Response("created");
        } catch (IOExceptionInterface $exception) {
            return new Response("Error creating directory at". $exception->getPath());
        }
    }
    /**
    * @Route("/addcontent", name="my_file1")
    */
    public function addcontent(): Response
    {   
        $fsObject= new Filesystem();
        $current_dir_path = getcwd();

        try {
            $new_file_path = $current_dir_path . "/foo/bar.txt";
         
            if (!$fsObject->exists($new_file_path))
            {
                $fsObject->touch($new_file_path);
                $fsObject->chmod($new_file_path, 0777);
                $fsObject->dumpFile($new_file_path, "Adding dummy content to bar.txt file.\n");
                $fsObject->appendToFile($new_file_path, "This should be added to the end of the file.\n");
            }
            return new Response("created");
        } catch (IOExceptionInterface $exception) {
            return new Response( "Error creating file at". $exception->getPath());
        }
    }
    /**
    * @Route("/copydir", name="my_file2")
    */
    public function copydir(): Response
    {   
        $fsObject= new Filesystem();
        $current_dir_path = getcwd();
        try {
            $src_dir_path = $current_dir_path . "/foo";
            $dest_dir_path = $current_dir_path . "/foo_copy";
         
            if (!$fsObject->exists($dest_dir_path))
            {
                $fsObject->mirror($src_dir_path, $dest_dir_path);
            }   return new Response("created");
        } catch (IOExceptionInterface $exception) {
            return new Response( "Error copying directory at". $exception->getPath());
        }
    }
    /**
    * @Route("/removedir", name="my_file3")
    */
    public function removedir(): Response
    {   
        $fsObject= new Filesystem();
        $current_dir_path = getcwd();
            //remove a directory
        try {
            $arr_dirs = array(
                $current_dir_path . "/foo_copy"
            );
            $fsObject->remove($arr_dirs);
            return new Response("removed");
        } catch (IOExceptionInterface $exception) {
            return new Response( "Error deleting directory at". $exception->getPath());
}
    }
}
?>