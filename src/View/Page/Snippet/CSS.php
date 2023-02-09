<?php

namespace App\View\Page\Snippet;

class CSS extends Snippet
{
    /**
     * Retourne une balise link pour le fichiers css.
     * 
     * @param string $href Url du fichier css.
     * 
     * @return string
     */
    public static function cssTag(string $href)
    {
        return <<<HTML
        <link rel="stylesheet" type="text/css" href="{$href}">
HTML;
    }

}