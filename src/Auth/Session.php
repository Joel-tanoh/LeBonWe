<?php

namespace App\Auth;

/**
 * Fichier de classe gestionnaire des variables de session.
 */
class Session extends Authentication
{
    const VISITOR_KEY = "Visitor_AxbjZteKoPflTdjUheXsDtvAjOp";
    const REGISTERED_KEY = "Registered_IjnOkpsQuGjnSheOrpSbfZmbgsx";
    const ADMINISTRATOR_KEY = "Admin_SasCdHuQuGjceAdeOrpSaSCVuGE";

    /**
     * Initie la variable de session qui permet d'identifier l'utilisateur
     * connecté.
     * 
     * @param string $value
     */
    public static function activateVisitor(string $value)
    {
        $_SESSION[self::VISITOR_KEY] = $value;
    }

    /**
     * Permet de mettre à jour le contenu de la session.
     * 
     * @param mixed $value
     */
    public static function activateRegistered($value)
    {
        $_SESSION[self::REGISTERED_KEY] = $value;
    }

    /**
     * Permet de vérifier si la session est active.
     * 
     * @return bool
     */
    public static function visitorActivated()
    {
        return isset($_SESSION[self::VISITOR_KEY]) && !empty($_SESSION[self::VISITOR_KEY]);
    }

    /**
     * Permet de vérifier si la session de l'utilisateur connecté est active.
     * 
     * @return bool
     */
    public static function registeredActivated()
    {
        return isset($_SESSION[self::REGISTERED_KEY]) && !empty($_SESSION[self::REGISTERED_KEY]);
    }

    /**
     * Retourne l'identifiant de la session de l'utilisateur.
     * 
     * @return string
     */
    public static function getVisitorId()
    {
        if (self::visitorActivated()) {
            return $_SESSION[self::VISITOR_KEY];
        }
    }

    /**
     * Retourne l'identifiant de la session de l'utilisateur connecté.
     * 
     * @return string
     */
    public static function getRegistered()
    {
        if (self::registeredActivated()) {
            return $_SESSION[self::REGISTERED_KEY];
        }
    }

    /**
     * Permet de désactiver la session de l'user connecté.
     * 
     * @return void
     */
    public static function disconnect()
    {
        $_SESSION[self::REGISTERED_KEY] = null;
    }
}