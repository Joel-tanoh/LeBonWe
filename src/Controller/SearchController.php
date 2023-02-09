<?php

namespace App\Controller;

use App\Action\Action;
use App\Engine\SearchEngine;
use App\View\Page\Page;
use App\View\SearchView;

/** Controller de gestion des recherches. */
abstract class SearchController extends AppController
{

    /**
     * Permet de router vers la bonne méthode si on a plusieurs
     * routes du même format.
     * @param array $params
     */
    public static function subRouter(array $params)
    {
        die("Vous faites des recherches selon des paramètres");
    }

    /**
     * Permet de gérer les recherches.
     * @param array $params
     */
    public static function searchAnnonce(array $params = null)
    {
        $announces = [];
        $pageTitle = "L'indice &#149 Announces";
        $searchEngine = new SearchEngine();

        if (Action::dataPosted()) {
            $searchEngine->searchAnnounces($_POST);
            $announces = $searchEngine->getResult("App\Model\Post\Announce", "id");
            $pageTitle .= " - Recherche " . $_POST["query"];
        }

        $page = new Page($pageTitle);
        $page->setView((new SearchView())->announcesResult($announces));
        $page->show();
    }

    /**
     * Permet de chercher les utilisateurs.
     * @param array $params
     */
    public static function searchUsers(array $params = null)
    {
        die("Vous cherchez les utilisateurs");
    }

    /** Reçoit les paramètres de la recherches s'il en existe */
    public static function reception()
    {

    }

    /**
     * Permet d'exécuter la requête.
     */
    private function run()
    {

    }

    /**
     * Permet de formater la requête à envoyer à la base de données.
     */
    private static function format()
    {

    }

    /**
     * Permet d'envoyer la requête finale de recherche à la base de données.
     */
    private static function sendQueryToDb()
    {

    }
}