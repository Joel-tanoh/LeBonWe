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

use App\Model\User\User;
use App\View\Page\Snippet;

/**
 * Permet de gérer les barres de menu sur le coté.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class PublicSideBar extends Sidebar
{

    /**
     * Affiche la sidebar de l'utilisateur afin de lui permettre de naviguer dans 
     * sa session personnelle.
     * 
     * @return string
     */
    public function sidebarNav()
    {
        
    }

    /**
     * Affiche l'avatar et les liens de la sidebar de l'utilisateur.
     * 
     * @return string
     */
    private function sidebarContent()
    {

    }
    
    /**
     * Affiche le menu du dashboard de l'utilisateur.
     * @return string
     */
    private function registeredSidebar() : string
    {
        if (User::isAuthenticated()) {
            $registered = User::authenticated();
            return <<<HTML
            <nav class="navdashboard">
                <ul>
                    {$this->defineSidebarLink("Mes annonces", $registered->getProfileLink(). "/posts", "lni-dashboard")}
                    {$this->defineSidebarLink("Déconnexion", "sign-out", "lni-enter")}
                </ul>
            </nav>
HTML;
        } else {
            return <<<HTML
            <nav class="navdashboard">
                <p class="text-muted">Veuillez vous <a href="sign-in">connecter</a>, ou vous <a href="register">inscrire</a> si vous n'avez pas de compte</p>
            </nav>
HTML;
        }
    }

}