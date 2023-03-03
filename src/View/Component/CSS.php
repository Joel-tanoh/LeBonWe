<?php

namespace App\View\Component;

class CSS extends Component
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

    /**
     * Permet d'ajouter un fichier css à cette page.
     * 
     * @param string $href      L'url du fichier Css.
     */
    public static function addCss(string $href)
    {
        // return [
        //     "href" => $href
        // ];

        return $href;
    }

}