<?php

/**
 * Classe de gestion des menus top
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com License
 * @link     Link
 */

namespace App\View\Component\Navbar;

use App\File\Image\Logo;
use App\Model\User\User;

/**
 * Perlet de gérer tout ce qui concerne la barre de navigation supérieure.
 * 
 * @category Category
 * @package  App\
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com License
 * @link     Link
 */
class BootstrapNavbar extends Navbar
{
    protected $logoFileName;
    protected $userAvatarSrc;

    /**
     * Contructeur de la barre de navigation supérieure.
     * 
     * @param string $logoFileName Le nom du fichier image du logo.
     * @param string userAvatarSrc Le chemin vers le fichier image avatar de l'utilisateur
     *                             connecté.
     */
    public function __construct(string $logoFileName = "/logo.png", string $userAvatarSrc = null)
    {
        $this->logoFileName = Logo::LOGOS_DIR_URL . $logoFileName;
        $this->userAvatarSrc = $userAvatarSrc;
    }

    /**
     * La barre de navigation Bootstrap
     * 
     * @return string
     */
    public function getBootstrapNavbar() : string
    {
        return <<<HTML
        <header id="header-wrap">
            <nav class="navbar navbar-expand-lg fixed-top scrolling-navbar top-nav-collapse">
                <div class="container border">
                    {$this->navbarHeader()}
                    <div class="collapse navbar-collapse" id="main-navbar">
                    </div>
                </div>
            </nav>
        </header>
HTML;
    }

    private function navbarHeader()
    {
        $appUrl = APP_URL;
        $logoAltText = Logo::ALT_TEXT;

        return <<<HTML
        <div class="navbar-header">
            {$this->navbarBrand()}
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navbar" aria-controls="main-navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                <span class="lni-menu"></span>
                <span class="lni-menu"></span>
                <span class="lni-menu"></span>
            </button>
            <a href="{$appUrl}" class="navbar-brand"><img id="logo" src="{$this->logoFileName}" alt="{$logoAltText}"></a>
        </div>
HTML;
    }

    /**
     * La barre de navigation faite sur-mésure pour l'application
     */
    private function getAppNavbar()
    {
        return <<<HTML

HTML;
    }

    /**
     * Le menu affiché sur les écrans des mobiles.
     * 
     * @return string
     */
    private function mobileMenu()
    {
        return <<<HTML
        <ul class="mobile-menu">
            {$this->mobileNavbar()}
        </ul>
HTML;
    }

    /**
     * Permet d'afficher le logo dans la navbar.
     * 
     * @param string $brandSrc Le lien vers l'image.
     * @param string $href     L'url exécuté lors du click sur le logo.
     * @param string $caption  Le texte à afficher si l'image introuvable.
     * 
     * @return string
     */
    private function navbarBrand(string $brandSrc = null, string $href = null, string $caption = null)
    {
        $brandSrc = $brandSrc ? $brandSrc : $this->logoFileName;
        return <<<HTML
        <a class="navbar-brand" href="{$href}">
            <img src="{$brandSrc}" alt="{$caption}" class="brand" style="width:15rem">
        </a>
HTML;
    }

    /**
     * Permet d'afficher l'image miniature de l'utilisateur dans la navbar lorsqu'il est connecté.
     * 
     * @param string $avatarSrc Le chemin de l'image.
     * @param string $caption   Le texte qui s'affiche lorsque l'image est indisponible
     * 
     * @return string
     */
    private static function navbarUserAvatar(string $avatarSrc, string $caption = null)
    {
        return <<<HTML
        <div>
            <img src="{$avatarSrc}" alt="{$caption}" class="navbar-user-avatar img-circle shdw mr-2"/>
        </div>
HTML;
    }

    /**
     * La barre de navigation qui est affichée lorsque l'utilisateur est authentifié et connecté.
     * 
     * @return string
     */
    private function authenticatedUserNavbar()
    {
        $registered = User::authenticated();

        $administrationLink = User::authenticated()->isAdministrator()
            ? '<a class="dropdown-item" href="administration"><i class="lni-dashboard"></i> Administration</a>'
            . '<a class="dropdown-item" href="administration/users"><i class="lni-users"></i> Gérer les utilisateurs</a>'
            . '<a class="dropdown-item" href="register"><i class="lni-user"></i> Ajouter un compte</a>'
            : null;

        return <<<HTML
        {$administrationLink}
        <a class="dropdown-item" href="{$registered->getProfileLink()}/posts"><i class="lni-dashboard"></i> Mes annonces</a>
        <a class="dropdown-item" href="sign-out"><i class="lni-close"></i> Déconnexion</a>
HTML;
    }

    /**
     * Affiche la navbar de l'utilisateur.
     * 
     * @return string
     */
    private function navbarMenu()
    {
        if (User::isAuthenticated()) {
            $content = $this->authenticatedUserNavbar();
        } else {
            $content = $this->navbarForUnconnectedUser();
        }

        return <<<HTML
        <ul class="sign-in">
            <li class="nav-item dropdown">
                <div class="dropdown-menu">
                    {$content}                    
                </div>
            </li>
        </ul>
HTML;
    }

    /**
     * Affiche la navbar dans le menu version mobile.
     * 
     * @return string
     */
    private function mobileNavbar()
    {
        if (User::isAuthenticated()) {
            return self::mobileNavbarForConnectedUser(User::authenticated());
        } else {
            return $this->mobileNavbarForUnconnectedUser();
        }
    }

    /**
     * Menu qui sera affiché si l'utilisateur n'est pas encore authentifé.
     * 
     * @return string
     */
    private function navbarForUnconnectedUser()
    {
        return <<<HTML
        <a class="dropdown-item" href="register"><i class="lni-user"></i> S'inscrire</a>
        <a class="dropdown-item" href="sign-in"><i class="lni-lock"></i> Connexion</a>
HTML;
    }

    /**
     * Affiche le menu dans la version mobile pour un visitor non connecté.
     * 
     * @return string
     */
    private function mobileNavbarForUnconnectedUser()
    {
        return <<<HTML
        <li><a href="register"><i class="lni-user"></i> S'inscrire</a></li>
        <li><a href="sign-in"><i class="lni-lock"></i> Connexion</a></li>
HTML;
    }

    /**
     * Affiche le menu dans la version mobile lorsqu'on a un utilisateur connecté.
     * Les liens réservés à la partie administration sont affichés si l'utilisateur
     * connecté a un compte administrateur.
     * 
     * @return string
     */
    private static function mobileNavbarForConnectedUser()
    {
        if (User::authenticated()) {
            $registered = User::authenticated();

            $administrationLink = User::authenticated()->isAdministrator()
                ? '<a class="dropdown-item" href="administration"><i class="lni-dashboard"></i> Administration</a>'
                . '<a class="dropdown-item" href="administration/users"><i class="lni-users"></i> Gérer les utilisateurs</a>'
                . '<a class="dropdown-item" href="register"><i class="lni-user"></i> Ajouter un compte</a>'
                : null;

            return <<<HTML
            <li><a href="/">Accueil</a></li>
            {$administrationLink}
            <li>
                <a>Mon compte</a>
                <ul class="dropdown">
                    <li><a href="{$registered->getProfileLink()}"><i class="lni-user"></i> Mon profil</a></li>
                    <li><a href="sign-out"><i class="lni-close"></i> Déconnexion</a></li>
                </ul>
            </li>
HTML;
        }
    }

}