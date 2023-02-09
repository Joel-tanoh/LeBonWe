<?php

/**
 * Fichier de classe.
 * 
 * @author Joel Tanoh <joel.developpeur@gmail.com>
 */

namespace App\View;

/**
 * Gère toutes les vues de type cartes.
 * 
 * @author Joel Tanoh <joel.developpeur@gmail.com>
 */
class Card extends View
{
    /**
     * Une carte ressemblant à celle de Youtube pour les vidéos.
     * 
     * @param string $imgSrc      La source de l'image à afficher dans la carte.
     * @param string $title        Le titre de la carte.
     * @param string $href         Le lien ou on va en cliquant sur la carte.
     * @param string $createdAt La date de création de l'élément.
     * 
     * @return string
     */
    public static function card(string $imgSrc = null, string $title = null, string $href = null, string $createdAt = null)
    {
        $img = null;

        if (null !== $imgSrc) {
            $img = <<<HTML
            <img src="{$imgSrc}" alt="une photo de {$title}" class="img-fluid">
HTML;
        }

        if (null !== $createdAt) {
            $createdAt = <<<HTML
            <div class="text-small">
                <i class="far fa-clock"></i> <span>Ajoutée le {$createdAt} </span>
            </div>
HTML;
        }

        return <<<HTML
        <div class="col-12 col-sm-6 col-md-3 mb-3">
            <a href="{$href}" class="text-black">
                <div class="border">
                    {$img}
                    <div class="p-3 bg-white">
                        <h6>{$title}</h6>
                        {$createdAt}
                    </div>
                </div>
            </a>
        </div>
HTML;
    }

    /**
     * Retourne une box pour les informations de types chiffres avec un
     * petit texte.
     * 
     * @param  $number  Le chiffre à afficher. Peut être une chaine de
     *                        caractère ou un nombre.
     * @param string $text    Le petit texte à afficher en dessous du chiffre.
     * @param string $href    Le lien vers lequel l'on est dirigé en cliquant sur
     *                        la box.
     * @param string $bgColor La couleur d'arrière plan de la box.
     * 
     * @return string
     */
    public static function boxInfo($number, string $text, string $href = null, string $bgColor = "info")
    {
        $href = null !== $href 
            ? '<a href="' . $href. '" class="small-box-footer text-white">Plus d\'info <i class="fas fa-arrow-circle-right"></i></a>'
            : null
        ;
        
        return <<<HTML
        <div class="col-4 col-md-12 mb-3">
            <div class="small-box text-small text-white bg-{$bgColor} rounded p-2">
                <div class="inner">
                    <h3>{$number}</h3>
                    <p>{$text}</p>
                    {$href}
                </div>
            </div>
        </div>
HTML;
    }

}