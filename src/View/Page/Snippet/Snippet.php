<?php

/**
 * Fichier de classe.
 * 
 * @author Joel <Joel.developpeur@gmail.com>
 */

namespace App\View\Page\Snippet;

use App\View\Effect\Animation\Slider\Slider;
use App\View\Page\Page;

/**
 * Gère les fragments de code.
 * 
 * @author Joel <Joel.developpeur@gmail.com>
 */
class Snippet extends Page
{

    /**
     * Affiche un slider qui fait défiler des images.
     */
    public function slider()
    {
        return (new Slider)->show();
    }

    /**
     * Retourne le vue pour lire la vidéo issue de Youtube.
     * 
     * @param string $youtubeVideoLink
     * 
     * @return string
     */
    public function youtubeIframe(string $youtubeVideoLink)
    {
        return <<<HTML
        <iframe src="https://www.youtube.com/embed/{$youtubeVideoLink}" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen class="w-100 video" style="height:25rem"></iframe>
HTML;
    }

    /**
     * Retourne un code HTML pour dire que tout s'est bien passé.
     * 
     * @param string $title   Cette partie s'affichera en grand.
     * @param string $content Le texte à afficher.
     * 
     * @return string 
     */
    public static function success(string $title, string $content, string $linkCaption = null, string $href = null, string $current = null)
    {
       
    }

    /**
     * Bloc de code qui s'affiche lorsque l'action menée a échoué.
     * 
     * @param string $title   Le titre pour indiquer en grand un notification.
     * @param string $content Le notification à afficher.
     * @param string $link    Peut être null.
     */
    public static function failed(string $title, string $content, string $linkCaption = null, string $href = null, string $current = null)
    {
        
    }

    /**
     * Retourne les icônes des réseaux sociaux dans le pied de page.
     * 
     * @return string
     */
    public function socialNetworksInfooter()
    {
        return <<<HTML
        <h3 class="block-title">Réseaux sociaux</h3>
        <ul class="footer-social">
            <li><a class="facebook" href="https://www.facebook.com/Lindice-101740878555286/"><i class="lni-facebook-filled"></i></a></li>
            <!-- <li><a class="twitter" href="#"><i class="lni-twitter-filled"></i></a></li>
            <li><a class="linkedin" href="#"><i class="lni-linkedin-fill"></i></a></li>
            <li><a class="google-plus" href="#"><i class="lni-google-plus"></i></a></li> -->
        </ul>
HTML;
    }
  
    /**
     * Un bloc de code HTML qui affiche aucune annonce lorqu'il n'y a pas 
     * d'annonce à afficher dans une partie de la page.
     * 
     * @return string
     */
    public static function noResultFound()
    {
        return <<<HTML
        <div class="col-12 text-muted text-center">
            <h2>Oup's</h2>
            <p>Nous n'avons trouvé aucun résultat.</p>
        </div>
HTML;
    }

}