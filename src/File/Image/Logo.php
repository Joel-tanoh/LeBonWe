<?php

namespace App\File\Image;

/**
 * Classe gestionnaire d'un logo.
 */
class Logo extends Image
{
    /**
     * Url du dossier des logos.
     * 
     * @var string
     */
    const LOGOS_DIR_URL = Image::IMG_DIR_URL . "/logo";

    /** Le texte qui s'affiche lorsque le logo n'est pas disponible */
    const ALT_TEXT = APP_NAME; 

    /**
     * Chemin du dossier des logos.
     * 
     * @var string
     */
    const LOGOS_DIR_PATH = parent::IMG_DIR_PATH . "logos" . DIRECTORY_SEPARATOR;
}