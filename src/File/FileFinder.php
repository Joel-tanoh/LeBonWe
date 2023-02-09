<?php

namespace App\File;

/**
 * Classe qui permet de chercher des images.
 */
trait FileFinder
{
    /**
     * Permet de récupérer les fichiers d'un dossier. On considère le chemin du
     * dossier à partir de la racine de l'application.
     * 
     * @param string $dirName Le nom du dossier dans lequel se trouve les fichiers.
     * 
     * @return array
     */
    public static function gets(string $pattern, string $dirName)
    {
        $filesArray = [];
        $toFind = rtrim($dirName, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $pattern;

        foreach(glob($toFind) as $imgPath) {
            $filesArray[] = APP_URL . "/" . str_replace("\\", "/", strstr($imgPath, "assets"));
        }

        return $filesArray;
    }

}