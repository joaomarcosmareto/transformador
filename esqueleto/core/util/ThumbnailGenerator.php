<?php

namespace core\util;

use core\AppConfig;
use Imagecraft\ImageBuilder;

class ThumbnailGenerator {

    static function convertImageToThumbnailBase64($source_image_path, $max_width, $max_height, $thumbnail_image_path = null, $source_image_data = null)
    {
        if($source_image_path == null)
            list($source_image_width, $source_image_height, $source_image_type) = getimagesizefromstring ($source_image_data);
        else
            list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);

        if($source_image_data == null)
        {
            switch ($source_image_type)
            {
                case IMAGETYPE_GIF:
                    $source_gd_image = \imagecreatefromgif($source_image_path);
                    break;
                case IMAGETYPE_JPEG:
                    $source_gd_image = \imagecreatefromjpeg($source_image_path);
                    break;
                case IMAGETYPE_PNG:
                    $source_gd_image = \imagecreatefrompng($source_image_path);
                    break;
            }
        }
        else
        {
            $source_gd_image = \imagecreatefromstring($source_image_data);
        }

        if (isset($source_gd_image) && $source_gd_image === false)
        {
            return false;
        }

        //definindo tamanho proporcional
        $source_aspect_ratio = $source_image_width / $source_image_height;
        $thumbnail_aspect_ratio = $max_width / $max_height;
        if ($source_image_width <= $max_width && $source_image_height <= $max_height) {
            $thumbnail_image_width = $source_image_width;
            $thumbnail_image_height = $source_image_height;
        } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
            $thumbnail_image_width = (int) ($max_height * $source_aspect_ratio);
            $thumbnail_image_height = $max_height;
        } else {
            $thumbnail_image_width = $max_width;
            $thumbnail_image_height = (int) ($max_width / $source_aspect_ratio);
        }

        $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);

        //criando imagem com o tamanho definido
        imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);
        imagedestroy($source_gd_image);

        //desenhando a imagem em um buffer separado
        ob_start();

            switch ($source_image_type)
            {
                case IMAGETYPE_GIF:
                    imagegif($thumbnail_gd_image);
                    $filetype = "gif";
                    break;
                case IMAGETYPE_JPEG:
                    imagejpeg($thumbnail_gd_image, null, 85);
                    $filetype = "jpeg";
                    break;
                case IMAGETYPE_PNG:
                    imagepng($thumbnail_gd_image);
                    $filetype = "png";
                    break;
            }

            imagedestroy($thumbnail_gd_image);

            $printedImage = ob_get_contents();

        ob_end_clean();

        if(isset($filetype) && empty($filetype) == false)
        {
            $result = 'data:image/' . $filetype . ';base64,' . base64_encode($printedImage);

            return $result;
        }
        else
        {
            return false;
        }
    }

    static function createAndSaveThumbnailFromPath($source_image_path, $destination, $fileName, $max_width, $max_height, $source_image_data = null)
    {
        if($source_image_path == null)
            list($source_image_width, $source_image_height, $source_image_type) = getimagesizefromstring ($source_image_data);
        else
            list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);

        $isJPEG = true;

        if($source_image_data == null)
        {
            switch ($source_image_type)
            {
                case IMAGETYPE_GIF:
                    //$source_gd_image = \imagecreatefromgif($source_image_path);
                    $isJPEG = false;
                    break;
                case IMAGETYPE_JPEG:
                    $source_gd_image = \imagecreatefromjpeg($source_image_path);
                    break;
                case IMAGETYPE_PNG:
                    //$source_gd_image = \imagecreatefrompng($source_image_path);
                    $isJPEG = false;
                    break;
            }
        }
        else
        {
            $source_gd_image = \imagecreatefromstring($source_image_data);
        }

        //caso de algum erro ao pegar os dados de imgs jped
        if ($isJPEG && isset($source_gd_image) && $source_gd_image === false)
        {
            return false;
        }

        //cria a pasta de destino caso não exista
        $result = null;
        if(!file_exists(AppConfig::getImgPath().$destination))
        {
            $result = mkdir(AppConfig::getImgPath().$destination, 0755, TRUE);
        }

        if($isJPEG)
        {
            //definindo tamanho proporcional
            $source_aspect_ratio = $source_image_width / $source_image_height;
            $thumbnail_aspect_ratio = $max_width / $max_height;

            if ($source_image_width <= $max_width && $source_image_height <= $max_height) {
                $thumbnail_image_width = $source_image_width;
                $thumbnail_image_height = $source_image_height;
            } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
                $thumbnail_image_width = (int) ($max_height * $source_aspect_ratio);
                $thumbnail_image_height = $max_height;
            } else {
                $thumbnail_image_width = $max_width;
                $thumbnail_image_height = (int) ($max_width / $source_aspect_ratio);
            }

            $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);

            //criando imagem com o tamanho definido
            imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);

            $destinationAndName = "";
            switch($source_image_type)
            {
//                case IMAGETYPE_GIF:
//                    $destinationAndName = $destination.$fileName.".gif";
//                    imagegif($thumbnail_gd_image, AppConfig::getImgPath().$destinationAndName);
//                    break;
                case IMAGETYPE_JPEG:
                    $destinationAndName = $destination.$fileName.".jpeg";
                    imagejpeg($thumbnail_gd_image, AppConfig::getImgPath().$destinationAndName, 100);
                    break;
//                case IMAGETYPE_PNG:
//                    $destinationAndName = $destination.$fileName.".png";
//                    imagepng($thumbnail_gd_image, AppConfig::getImgPath().$destinationAndName, 9);
//                    break;
            }

            imagedestroy($source_gd_image);
            imagedestroy($thumbnail_gd_image);

            return $destinationAndName;
        }
        else//caso for gif ou png é usado a biblioteca do imagecraft para manter animações, transparencia e redimensionar
        {
            $options = ['engine' => 'php_gd'];
            $builder = new ImageBuilder($options);

            $layer = $builder->addBackgroundLayer();
            $layer->filename($source_image_path);
            $layer->resize($max_width, $max_height);

            $image = $builder->save();

            if ($image->isValid())
            {
                $destinationAndName = $destination.$fileName.".".$image->getExtension();
                file_put_contents(AppConfig::getImgPath().$destinationAndName, $image->getContents());

                return $destinationAndName;
            }
            else
            {
                //echo $image->getMessage().PHP_EOL;
                return false;
            }
        }

    }//createAndSaveThumbnailFromPath

}