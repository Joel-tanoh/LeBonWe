<?php

namespace App\Auth;

/**
 * Classe gestionnaire des variables relatives aux cookie.
 */
class Cookie extends Authentication
{
    const VISITOR_KEY = "V_AxbjZteKoPflTdjUheXsDtvAjOp";
    const REGISTERED_KEY = "R_IjnOkpsQuGjnSheOrpSbfZmbgsx";
    const ADMINISTRATOR_KEY = "A_SasCdHuQuGjceAdeOrpSaSCVuGE";

    /**
     * Retourne la variable cookie pour la partie administration.
     * 
     * @return string
     */
    public static function getAdministrator()
    {
        return $_COOKIE[self::ADMINISTRATOR_KEY];
    }

    /**
     * Retourne le contenu de la variable cookie de l'id de session du visiteur.
     * 
     * @return string
     */
    public static function getVisitorId()
    {
        if (!empty($_COOKIE[self::VISITOR_KEY])) {
            return $_COOKIE[self::VISITOR_KEY];
        }
    }

    /**
     * Retourne le contenu de la variable cookie de l'utilisateur authentifié.
     * 
     * @return string
     */
    public static function getRegistered()
    {
        if (!empty($_COOKIE[self::REGISTERED_KEY])) {
            return $_COOKIE[self::REGISTERED_KEY];
        }
    }

    /**
     * Vérifie si l'id de session du visiteur existe.
     * 
     * @return bool
     */
    public static function visitorSetted()
    {
        return null !== self::getVisitorId();
    }

    /**
     * Vérifie si le cookie de l'utulisateur authentifié existe.
     * 
     * @return bool
     */
    public static function registeredSetted()
    {
        return null !== self::getRegistered();
    }

    /**
     * Permet de mettre à jour le contenu de la cookie.
     * 
     * @param mixed $value
     */
    public static function setVisitor($value, $domain = null)
    {
        setcookie(
            Cookie::VISITOR_KEY,
            $value,
            time()+(30*24*3600),
            null,
            $domain,
            false,
            true
        );
    }

    /**
     * Permet d'activer les cookie pour l'utilisateur connecté.
     * 
     * @param mixed $value
     */
    public static function setRegistered($value, $domain = null)
    {
        setcookie(
            Cookie::REGISTERED_KEY,
            $value,
            time()+(30*24*3600),
            null,
            $domain,
            false,
            true
        );
    }

    /**
     * Permet de détruire la session.
     */
    public static function destroy()
    {
        setcookie(Cookie::REGISTERED_KEY, '', 0);
    }

}