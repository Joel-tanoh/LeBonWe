<?php

/**
 * Fichier de classe.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "CVS: cvs_id"
 * @link     Link
 */

namespace App\File;

use App\File\Image\Image;

/**
 * Gère une Les fichier uploadés.
 * 
 * @category Category
 * @package  App\backend
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class FileUploaded extends File
{
    private $errors;
    
    private $tmpName;

    /**
     * Constructeur d'une image.
     * 
     * @param $fileUplaoded Tableau $_FILES qui contient les informations relatives
     *                      à l'image.
     */
    public function __construct($fileUplaoded, string $nameKey = "name", string $errKey = "error", string $tmpName = "tmp_name", string $sizeKey = "size")
    {
        $this->fileUploadedInfos = pathinfo($fileUplaoded[$nameKey]);
        $this->errors            = $fileUplaoded[$errKey];
        $this->tmpName           = $fileUplaoded[$tmpName];
        $this->size              = $fileUplaoded[$sizeKey];
        $this->name              = $this->fileUploadedInfos["filename"];
        $this->extension         = $this->fileUploadedInfos["extension"];
    }

    /**
     * Retourne le nom du fichier temporaire, c'est à dire le chemin temporaire du
     * fichier sur le serveur.
     * 
     * @return string
     */
    public function getTempName()
    {
        return $this->tmpName;
    }
    
    /**
     * Retourne les erreurs relatives au serveur sur le fichier.
     * 
     * @author Joel
     * @return string
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Retourne true si la taille du fichier uploadé est inférieure ou égale à 2 Mo.
     * 
     * @author Joel
     * @return boolean [[Description]]
     */
    public function hasValidSize() : bool
    {
        return $this->size <= Image::MAX_VALID_SIZE;
    }

    /**
     * Vérifie qu'il n'y a pas d'erreur sur le fichier.
     * Retourne true s'il n'y a pas d'erreur.
     * 
     * @author Joel
     * @return bool
     */
    public function hasNoError() : bool
    {
        return $this->errors == 0;
    }

    /**
     * Retourne true si le fichier uploadé est une image.
     * 
     * @author Joel
     * @return boolean [[Description]]
     */
    public function isAnImage() : bool
    {
        return in_array(mb_strtolower($this->extension), Image::VALID_EXTENSIONS);
    }
    
    /**
     * Vérifie que le fichier uploadé est une image, qu'il n'y a pas d'erreur et
     * que la taille du fichier est valide.
     * Retourne true si le fichier Uploadé est une image, a une bonne taille
     * et n'a aucune erreur.
     * 
     * @author Joel
     * @return boolean [[Description]]
     */
    public function isAnImageHasValidSizeAndNoError() : bool
    {
        return $this->hasValidSize() && $this->isAnImage() && $this->hasNoError();
    }

    /**
     * Retourne true si le fichier appelant la méthode est un fichier pdf.
     * 
     * @return bool
     */
    public function isPdfFile()
    {
        return $this->extension == "pdf";
    }

    /**
     * Permet de vérifier que le fichier uploadé est une vidéo.
     * 
     * @return bool
     */
    public function isVideo()
    {

    }

}
