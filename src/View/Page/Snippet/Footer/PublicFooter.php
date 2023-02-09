<?php

/**
 * Description
 *
 * PHP version 7.1.9
 *
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com License
 * @link     Link
 */

namespace App\View\Page\Footer;

use App\View\Page\Snippet;

/**
 * Gère tout ce qui concerne le pied de page
 *
 * @category Category
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com License
 * @link     Link
 */
class PublicFooter extends Snippet
{
    /**
     * Pied de page
     *
     * @author Joel
     * @return string [[Description]]
     */
    public function get() : string
    {
        return <<<HTML
HTML;
    }

}