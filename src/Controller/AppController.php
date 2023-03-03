<?php

namespace App\Controller;


use App\View\Page\Page;
use App\View\View;
use Exception;

abstract class AppController
{
    /** @var array Les actions possibles au sein de l'application. */
    protected static $actions = [
        "create"
        , "read"
        , "update"
        , "delete"
        , "show"
        , "view"
        , "validate"
        , "suspend"
        , "block"
        , "comment"
    ];

    /**
     * Une sous-couche du routage qui permet de gérer le routage vers le bon controller
     * en fonction des paramêtres contenu dans l'URL.
     * 
     * @param array $params La liste des paramètres de la route.
     */
    public static function subRouter(array $params)
    {
        
    }

    /**
     * Permet de vérifier que le verbe passé en paramêtre est une action gérée par le routage.
     * @return bool
     */
    public static function isAction(string $action)
    {
        return in_array($action, self::$actions);
    }

    /**
     * Cette page s'affiche pour les ressources pas encore développées.
     */
    public static function onDevelopment()
    {
        $page = new Page(APP_NAME . " - 404", View::pageNotFound());
        $page->show();
    }

}