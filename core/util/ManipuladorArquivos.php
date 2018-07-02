<?php

namespace core\util;

// use PDO;
use Exception;
use core\AppConfig;
use \Psr\Http\Message\ServerRequestInterface as Request;
use core\util\{Logger, ThumbnailGenerator};
use MongoDB\BSON\ObjectID;

class ManipuladorArquivos {

    public $files;

    public function __construct(Request $request ) {
        $this->files = $request->getUploadedFiles();
        // var_dump($this->files);
    }

    // public function getFileNames()
    // {
    //     $filenames = [];
    //     foreach ($this->files as $k->$input) {
    //         $filenames[]


    //         if(!is_array($input))
    //         {
    //             $input = [$input];
    //         }
    //         foreach ($input as $uploadedFile) {
    //             if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
    //                 $filenames[] = $uploadedFile->getClientFilename();
    //             }
    //         }
    //     }
    //     return $filenames;
    // }

    public function getFileNamesPorParam(string $param)
    {
        $filenames = [];
        $files = $this->files[$param];
        // var_dump($files);
        if(!is_array($files))
        {
            if ($files->getError() === UPLOAD_ERR_OK) {
                $filenames[] = $files->getClientFilename();
            }
        }
        else
        {
            foreach ($files as $uploadedFile) {
                if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                    $filenames[] = $uploadedFile->getClientFilename();
                }
            }
        }
        // var_dump($filenames);
        return $filenames;
    }

    /**
     * Moves the uploaded file to the upload directory and assigns it a unique name
     * to avoid overwriting an existing uploaded file.
     *
     * @param string $directory directory to which the file is moved
     * @param UploadedFile $uploaded file uploaded file to move
     * @return string filename of moved file
     */
    public function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
        // $filename_parts = pathinfo('/www/htdocs/index.html');

        // $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        // $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        // $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

    /**
     * Removes the specified file represented by the full qualified domain name
     *
     * @param string $fqdn Full Qualified Domain Name of the file to be removed
     */
    public function removerArquivo(string $fqdn)
    {
        if(file_exists($fqdn)){
            @unlink($fqdn);
        }
    }

    /**
     * Saves the logo of the business
     *
     * @param string $fqdn Full Qualified Domain Name of the file to be removed
     */
    public function saveImage(string $param, string $dominio_id, $destination, int $width, int $height)
    {
        // var_dump($this->files[$param]->file);

        if(!empty($this->files[$param]))
        {
            $idImg = new ObjectID();
            $idImg = (string) $idImg;

            // var_dump($destination.$dominio_id."-".$idImg.".jpg");
            return ThumbnailGenerator::createAndSaveThumbnailFromPath($this->files[$param]->file, $destination, $dominio_id."-".$idImg, $width, $height);
        }
    }
    
    public function saveImageClientName(string $param, $destination, int $width, int $height)
    {
        if(!empty($this->files[$param]))
        {
            $aux = $this->getFileNamesPorParam($param);
            
            if(!empty($aux)){
                $nameFile = $this->getFileNamesPorParam($param)[0];
            
                return ThumbnailGenerator::createAndSaveThumbnailFromPath($this->files[$param]->file, $destination, $nameFile, $width, $height);
            }
        }
    }
    
}