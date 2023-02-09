<?php

namespace App\View\Page\Snippet;

class JS extends Snippet
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

}