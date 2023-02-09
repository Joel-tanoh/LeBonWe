<?php

namespace App\Controller;

use App\Communication\Notify\NotifyByHTML;
use App\Model\BookLink;
use App\Utility\Utility;
use App\View\BookLinkView;
use App\View\Page\Page;

class BookLinkController extends AppController
{
    /**
     * Permet d'afficher le lien du livre.
     */
    public static function showBookLink()
    {
        $page = new Page("Lien du livre");
        $bookLink = BookLink::getBookLink();
        $page->setView(BookLinkView::showBookLink($bookLink));
        $page->show();
    }

    /**
     * Controlleur de modification du lien du livre.
     */
    public static function updateBookLink()
    {
        if (!$_POST["update-book-link"]) {
            Utility::redirect("/administration/book-link");
        } else {
            $bookLink = htmlspecialchars(htmlentities(trim($_POST["book_link"])));
            if (BookLink::updateBookLink($bookLink)) {
                $notification = (new NotifyByHTML)->toast("Modification effectuée avec succès.", "success");
            } else {
                $notification = (new NotifyByHTML)->toast("Echec de la modification, veuillez réessayer svp.", "danger");
            }
            
            $page = new Page("Lien du livre");
            $bookLink = BookLink::getBookLink();
            $page->setView(BookLinkView::showBookLink($bookLink, $notification));
            $page->show();
        }
    }
}