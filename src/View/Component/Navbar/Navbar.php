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
use App\View\Component\Component;

/**
 * Perlet de gérer tout ce qui concerne la barre de navigation supérieure.
 * 
 * @category Category
 * @package  App\
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com License
 * @link     Link
 */
class Navbar extends Component
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
        $this->logoFileName = Logo::LOGOS_DIR_URL . $logoFileName;;
        $this->userAvatarSrc = $userAvatarSrc;
    }

    /**
     * La barre de navigation Bootstrap
     */
    public function getBootstrapNavbar()
    {
        return (new BootstrapNavbar())
            ->getBootstrapNavbar(
                APP_URL,
                Logo::ALT_TEXT
            );
    }

}