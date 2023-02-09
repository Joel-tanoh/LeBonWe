<?php

namespace App\File;

/**
 * Fichier de classe gestionnaire des fichiers de type vidéo.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class Video extends File
{
    /**
     * Tableau contenant les extensions de ficiers de type
     * vidéo autorisées.
     * 
     * @var array
     */
    const VALID_EXTENSIONS = ["mp4", "m4a", "webm"];

}