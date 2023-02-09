<?php

namespace App\File;

/**
 * Gère une Les fichiers.
 * 
 * @category Category
 * @package  App\backend
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class File
{
    /**
     * Nom du fichier.
     * 
     * @var string
     */
    protected $name;

    /**
     * Le nom du dossier.
     * 
     * @var string
     */
    protected $dirName;

    /**
     * La taille du fichier uploadé
     * 
     * @var int
     */
    protected $size;
    
    /**
     * Extension du fichier uploadé.
     * 
     * @var string
     */
    protected $extension;

    /**
     * Date du fichier.
     * 
     * @var string
     */
    protected $date;

    /**
     * Le chemin du dossier des fichiers.
     */
    const FILES_DIR_PATH = PUBLIC_PATH . "files" . DIRECTORY_SEPARATOR;

    const FILES_DIR_URL = APP_URL . "/files";

    use FileFinder;

    /**
     * Constructeur d'un fichier.
     * 
     * @param $path Tableau $_FILES qui contient les informations relatives
     *                      à l'image.
     */
    public function __construct($path = null)
    {
        if (null !== $path) {
            $fileInfos = pathinfo($path);

            $this->name = $fileInfos['filename'];
            $this->dirName = $fileInfos["dirname"];
            $this->extension = $fileInfos['extension'];
            $this->size = $fileInfos['size'];
        }
        
    }

    /**
     * Retourne le nom du fichier.
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Retourne le nom du dossier.
     * 
     * @return string
     */
    public function getDirName()
    {
        return $this->dirName;
    }

    /**
     * Retourne la taille du fichier.
     * 
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }
    
    /**
     * Retourne l'extension du fichier uploadé.
     * 
     * @author Joel
     * @return string [[Description]]
     */
    public function getExtension()
    {
        return '.' . mb_strtolower($this->extension);
    }

    /**
     * Retourne la date.
     * 
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Permet de vérifier qu'un ou plusieurs fichiers ont été uploadés.
     * 
     * @param string $key La clé dans le tableau.
     * 
     * @return bool
     */
    public static function fileIsUploaded(string $key)
    {
        return !empty($_FILES[$key]["name"][0]);
    }

}
