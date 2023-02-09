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
 * @version  "CVS: cvs_id"
 * @link     Link
 */

namespace App\View\Page;

use App\View\Card;
use App\View\View;

/**
 * Une template est un type de disposition du contenu d'une page.
 * Le contenu de la page est une template, et à cette template, on passera
 * donc les vues.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class Template extends View
{
    /**
     * Template avec une navbar fixe et une sidebar fixe.
     * 
     * @param $navbar
     * @param $sidebar
     * @param $content
     * 
     * @return string
     */
    public function navbarAndSidebarAndContainer($navbar = null, $sidebar = null, $content = null)
    {
        return <<<HTML
        {$navbar}
        {$sidebar}
        <div class="container-fluid mb-3" id="containerWithFixedSidebarNavbar">
            {$content}
        </div>
HTML;
    }

    /**
     * Disposition de page avec une navbar, un contenu et un footer.
     * 
     * @param string $navbar
     * @param string $content
     * @param string $footer
     * 
     * @return string
     */
    public function navbarAndContentAndFooter($navbar = null, $content = null, $footer = null)
    {
        return <<<HTML
        {$navbar}
        {$content}
        {$footer}
HTML;
    }

    /**
     * Une template avec une navbar fixe et le contenu.
     * 
     * @param $navbar
     * @param $content
     * 
     * @return string
     */
    public function navbarAndContent($navbar, $content)
    {
        return <<<HTML
        {$navbar}
        {$content}
HTML;
    }

    public function contentAndFooter($content, $footer)
    {
        return <<<HTML
        {$content}
        {$footer}
HTML;
    }

    /**
     * Template de la page d'administration. Elle comporte une navbar, une sidebar,
     * le contenu de la page et le footer.
     * 
     * @param string $sidebar La barre de gauche à fixer.
     * @param  $content Le contenu à afficher à code de la sidebar.
     * 
     * @return string
     */
    public function sidebarAndContainer($sidebar = null, $content = null)
    {
        return <<<HTML
        <section>
            {$sidebar}
        </section>
        <div id="content" class="container">
            {$content}
        </div>
HTML;
    }

    /**
     * Retourne la disposition de la page principale de la partie
     * publique.
     * 
     * @param $content
     * 
     * @return string
     */
    public function singleColumn($content = null)
    {
        return <<<HTML
        <div id="content" class="container">
            {$content}
        </div>
HTML;
    }
    
    /**
     * Template de liste de cartes.
     * 
     * @param array  $itemsForCards Un tableau contenant des cartes.
     * @param string $categorie     La classe ou la catégorie des éléments passées permettant
     *                              d'instancier des objets.
     * @param string $cssClass      Classe css.
     * 
     * @return string
     */
    public function gridOfCards(array $itemsForCards = null, string $cssClass = null)
    {
        $cards = null;

        foreach ($itemsForCards as $item) {

            if ($item->getCategorie() === "mini-services") {
                $cards .= Card::card($item);
            } else {
                $cards .= Card::card($item->getThumbsSrc(), $item->getTitle(), $item->getUrl("administrate"), $item->getCreatedAt());
            }
        }

        return <<<HTML
        <div class="row {$cssClass}">
            {$cards}
        </div>
HTML;
    }

}

