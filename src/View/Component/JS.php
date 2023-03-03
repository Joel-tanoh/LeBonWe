<?php

namespace App\View\Component;

class JS extends Component
{
    /**
     * Retourne une balise script de type JS pour appeler le fichier javascript passé
     * en paramètre.
     * 
     * @param string $jsFileUrl Url du fichier javascript.
     * 
     * @return string
     */
    public static function jsTag($jsFileUrl, string $async = null)
    {
        if (null !== $async) {
            $async = "async = " . $async;
        }

        return <<<HTML
        <script src="{$jsFileUrl}" {$async}></script>
HTML;
    }

    /**
     * Permet d'ajouter un fichier js à cette page.
     * 
     * @param array  $jsFiles   Le tableau qui doit contenir les fichiers Js.
     * @param string $jsFileUrl L'url du fichier Js.
     * @param string $async     Pour dire si le fichier est uploadé de façon asynchrone ou pas.
     */
    public static function addJs(string $src, string $async = null)
    {
        $jsFiles[] = [
            "src" => $src,
            "async" => $async,
        ];

        return $jsFiles;
    }

}