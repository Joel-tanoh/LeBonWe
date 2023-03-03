<?php

namespace App\View;

use App\View\Component\Footer\Footer;
use App\View\Component\Navbar\Navbar;
use App\View\Page\SideBar;
use Faker\Guesser\Name;

/**
 * Classe View. Regroupe toutes les vues de l'application.
 */
class View
{
    /**
     * Vue de l'index de l'application.
     * 
     * @return string
     */
    public static function index()
    {
        $appName = APP_NAME;
        $navbar = new Navbar();
        $footer = new Footer();

        return <<<HTML
        {$navbar->getBootstrapNavbar()}
        <div class="container">Bienvenu sur {$appName}</div>
        {$footer->getFooter()}
HTML;
    }

    /**
     * La vue à afficher lorsqu'on ne trouve pas la ressource.
     * 
     * @return string
     */
    public static function pageNotFound()
    {
        $home = APP_URL;
    
        return <<<HTML
        <div class="container">
            <h3>Oup's ! Page non trouvée</h3>
            <p>Retour vers <a href="{$home}">l'accueil</a></p>
        </div>
HTML;
    }

    /**
     * La vue à afficher lorsqu'on rencontre une erreur de type exception.
     * 
     * @param \Error|\TypeError|\Exception|\PDOException $e
     */
    public static function exception($e)
    {
        return <<<HTML
        <div class="container">
            <div class="bg-white rounded my-3 p-3">
                <h1 class="text-primary">Exception capturée.</h1>
                <p class="h3 text-secondary">{$e->getMessage()}</p>
                <p>Excéption jetée dans {$e->getFile()} à la ligne {$e->getLine()}.</p>
            </div>
        </div>
HTML;
    }

    /**
     * La vue de "A propos".
     * 
     * @return string $content
     */
    public static function aboutUs()
    {
        return <<<HTML

HTML;
    }

    /**
     * La vue qui affiche le FAQ.
     * 
     * @return string
     */
    public static function FAQ()
    {
        return <<<HTML

HTML;
    }

    /**
     * Affiche la vue pour l'administration avec une sidebar. Cette vue est disposée
     * de façon responsive avec les class bootstrap.
     * 
     * @param string $content Le contenu de la page d'administration. Le contenu doit 
     *                        contenir des class de disposition (col) afin d'être
     *                        bien disposée en fonction des écrans.
     * @param string $title   Le titre qui va s'afficher dans le bannière du haut.
     * @param string $current Le texte qui sera affiché dans le
     * @return string
     */
    public static function administrationTemplate(string $content, string $notification = null)
    {
        $sidebar = new SideBar;

        return <<<HTML
        {$notification}
        <div class="container-fluid">
            <div class="row">
                <!-- Code de Sidebar -->
                <div class="col-sm-12 col-md-9">
                    {$content}
                </div>
            </div>
        </div>
HTML;
    }

}