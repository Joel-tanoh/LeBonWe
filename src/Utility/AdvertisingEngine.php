<?php

namespace App\Utility;

use App\File\Image\Image;

/**
 * Classe de gestion de publicité.
 */
class AdvertisingEngine extends Utility
{
    /** Le dossier des images de publicités */
    const ADVERTISING_DIR_PATH = Image::IMG_DIR_PATH . "advertising" . DIRECTORY_SEPARATOR;

    /**
     * Permet de récupérer les images de la publicité.
     * 
     * @param string $dirName Le nom du dossier dans lequel se trouve les images.
     */
    public function getImages(string $dirName = null)
    {
        $imgSrcArray = [];

        if (null === $dirName) {
            $directory = self::ADVERTISING_DIR_PATH;
        } else {
            $directory = self::ADVERTISING_DIR_PATH . $dirName . DIRECTORY_SEPARATOR;
        }

        foreach(glob($directory. "*.jpg") as $imgPath) {
            $imgSrcArray[] = APP_URL . "/" . str_replace("\\", "/", strstr($imgPath, "assets"));
        }

        return $imgSrcArray;
    }

}