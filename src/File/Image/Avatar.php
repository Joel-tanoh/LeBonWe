<?php

namespace App\File\Image;

class Avatar extends Image
{
    /**
     * Chemin du dossier contenant les avatars des users.
     * @var string
     */
    const AVATARS_DIR_PATH =  parent::IMG_DIR_PATH . "author" . DIRECTORY_SEPARATOR;

    /**
     * Url du dossier contenant les avatars des users.
     * @var string
     */
    const AVATARS_DIR_URL = parent::IMG_DIR_URL . "/author";

    /** 
     * L'avatar par défaut.
     * @var string
     */
    const DEFAULT = self::AVATARS_DIR_URL . "/default.jpg";

}