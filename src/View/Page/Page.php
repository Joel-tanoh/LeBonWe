<?php

/**
 * Description
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  GIT: Joel_tanoh
 * @link     Link
 */

namespace App\View\Page;

use App\File\Image\Logo;
use App\View\Component\CSS;
use App\View\View;
use App\View\Component\JS;

/**
 * Classe qui gère tout ce qui est en rapport à une page.
 *  
 * @category Category
 * @package  App
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  Release: 1
 * @link     Link
 */
class Page extends View
{
    private $metaTitle;
    private $view;
    private $description;
    private $cssFiles = [];
    private $jsFiles = [];

    /**
     * @param string   $metaTitle   Le titre qui sera affiché dans la page.
     * @param string   $view        La vue à afficher.
     * @param string   $description La description de la page qui est affichée dans les données métas.
     *                              Cette donnée aide pour le référencement.
     * @param string[] $cssFiles    Un tableau contenant les chemins des fichiers css.
     * @param string[] $jsFiles     Un tableau contenant les chemins des fichiers css.
     * @param boolean  $navbarState Permet d'afficher la barre de navigation Top.
     * @param boolean  $footerState Permet d'afficher le pied de page.
     */
    public function __construct(
        string $metaTitle,
        string $view,
        string $description,
        array $cssFiles = [], 
        array $jsFiles = []
    ) {
        $this->metaTitle = $metaTitle;
        $this->view = $view;
        $this->description = $description;
        $this->cssFiles = $cssFiles;
        $this->jsFiles = $jsFiles;
    }

    /**
     * Affiche le contenu HMTL de la page
     **/
    public function show()
    {
        echo <<<HTML
        {$this->debutDePage("fr")}
        <head>
            {$this->metaData()}
            {$this->getCssTags()}
        </head>
        <body>
            {$this->view}
            {$this->getJsTag()}
        </body>
        </html>
HTML;
    }

    /**
     * Permet de modifier le metaTitle de la page.
     * 
     * @param string $metaTitle
     * 
     * @return void
     */
    public function setMetaTitle(string $metaTitle)
    {
        $this->metaTitle = $metaTitle;
    }

    /**
     * Permet de modifier la meta description de la page.
     * 
     * @param string $description
     * 
     * @return void
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * Permet de modifier le contenu de la page.
     * 
     * @param $view
     * 
     * @return void
     */
    public function setView($view)
    {
        $this->view = $view;
    }
    
    /**
     * Retourne le code pour les icones.
     * 
     * @return string
     */
    public function setFavicon(string $logosDir = Logo::LOGOS_DIR_URL)
    {
        return <<<HTML
        <link rel="icon" href="{$logosDir}/faviconx2.png" type="image/png">
        <link rel="shortcut icon" href="{$logosDir}/faviconx2.png" type="image/png">
HTML;
    }

    /**
     * Permet d'ajouter un fichier css à la page.
     * 
     * @param $cssFile L'url du fichier Css.
     */
    public function addCss(string $href)
    {
        $this->cssFiles[] = [
            "href" => $href
        ];
    }

    /**
     * Permet d'ajouter un fichier js à cette page.
     * 
     * @param string $jsFileUrl L'url du fichier Js.
     * @param string $async     Pour dire si le fichier est uploadé de façon asynchrone ou pas.
     */
    public function addJs(string $src, string $async = null)
    {
        $this->jsFiles[] = [
            "src" => $src,
            "async" => $async,
        ];
    }

    /**
     * Code du début de la page.
     * 
     * @param string $htmlLanguage
     * 
     * @return string
     */
    private function debutDePage($htmlLanguage = "fr")
    {
        return <<<HTML
        <!DOCTYPE html>
        <html lang="{$htmlLanguage}">
HTML;
    }

    /**
     * Retourne les balises meta
     * 
     * @return string
     */
    private function metaData()
    {
        $base = APP_URL;

        return <<<HTML
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="description" content="{$this->description}">
        <base href="{$base}">
        <title>{$this->metaTitle}</title>
        {$this->setFavicon()}
HTML;
    }

    /**
     * Retourne les fichiers css selon le thème passé en paramètre.
     *  
     * @return string
     */
    private function getCssTags()
    {
        $cssTags = null;
        $this->cssFiles();

        if (!empty($this->cssFiles)) { 
            foreach($this->cssFiles as $cssFile) {
                $cssTags .= CSS::cssTag($cssFile["href"]) . "\n";
            }
        }

        return $cssTags;
    }

    /**
     * Retourne les fichiers JS appelés.
     * 
     * @return string
     */
    private function getJsTag()
    {
        $jsTags = null;
        $this->jsFiles();

        if (!empty($this->jsFiles)) {
            foreach($this->jsFiles as $jsFile) {
                $jsTags .= JS::jsTag($jsFile["src"], $jsFile["async"]) . "\n";
            }
        }

        return $jsTags;
    }

    /**
     * Retourne les fichiers CSS utilisés sur toutes les pages.
     * 
     * @return string
     */
    private function cssFiles()
    {
        // Bootstrap CSS
        $this->addCss("https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css");
        // Fontawesome
        $this->addCss("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css");
        
        // // Summernote
        // $this->addCss("https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.css");
        // // Icon
        // $this->addCss(ASSETS_DIR_URL."/fonts/line-icons.css");
        // Main Style
        // $this->addCss(ASSETS_DIR_URL."/css/main.css");
    }

    /**
     * Retourne les fichiers JS appelés sur toutes les pages.
     * @return string
     */
    private function jsFiles()
    {
        // // Jquery
        $this->addJs(ASSETS_DIR_URL."/js/jquery-min.js");
        // // Popper
        $this->addJs("https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.8.6/umd/popper.min.js");
        // // Fontawesome
        $this->addJs("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/js/all.min.js");    
        // // Bootstrap
        $this->addJs("https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js");       
        
        // // Summernote
        // JS::addJs("https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.js");
        // JS::addJs("https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/lang/summernote-fr-FR.min.js"); 
        // Main Js
        // JS::addJs(ASSETS_DIR_URL."/js/main.js");
    }

}