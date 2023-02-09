<?php

namespace App\Communication\SocialNetwork;

abstract class SocialNetwork
{
    /**
     * Retourne le script.
     */
    public static function getShareThisId()
    {
        return 'https://platform-api.sharethis.com/js/sharethis.js#property=6068a0bb9269c20011a2a2ba&product=sop';
    }

    /**
     * Permet d'afficher les boutons pour partager sur les réseaux sociaux.
     * 
     * @return string
     */
    public static function shareThis()
    {
        return <<<HTML
        <div class="sharethis-inline-share-buttons"></div>
HTML;
    }

}