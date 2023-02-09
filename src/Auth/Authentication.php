<?php

namespace App\Auth;

use App\Utility\Utility;
use App\Utility\Validator;

/**
 * Fichier de classe gestionnaire de l'authentification des utilisateurs.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class Authentication
{
    /**
     * Retourne le tableau contenant les valeurs de session.
     * 
     * @param string $key
     * 
     * @return array
     */
    public static function getSession(string $key = null)
    {
        if (null !== $key) {
            return $_SESSION[$key];
        }

        return $_SESSION;
    }

    /**
     * Retourne le tableau des valeurs de coockie.
     * 
     * @param string $key
     * 
     * @return array
     */
    public static function getCookies(string $key = null)
    {
        if (null !== $key) {
            return $_COOKIE[$key];
        }

        return $_COOKIE;
    }
    
    /**
     * Initalise les variables de sessions en mettant le login de l'administrateur.
     * 
     * @param string $sessionKey La clé de la session.
     * @param mixed  $value      La valeur de la session
     * 
     * @return void
     */
    public static function setSession($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Gère l'authentification des registereds.
     * 
     * @param string $emailAddress
     * @param string $password
     * 
     * @return bool
     */
    public static function authenticateUser($emailAddress, $password)
    {
        if (null === $emailAddress) {
            return false;
        } else {

            $validator = new Validator();

            if ($validator->email("email_address", $emailAddress)) {

                // $user = User::getByEmailAddress($emailAddress);

                // if ($user) {

                //     if (Password::verifyHash($password, $user->getPassword())) {
                //         return true;
                //     } else {
                //         return false;
                //     }

                // } else {
                //     return false;
                // }

            } else {
                return false;
            }
        }
    }

    /**
     * Permet de rédiriger l'utilisateur sur sa
     * page de connexion s'il n'est pas authentifié.
     */
    public static function askToAuthenticate(string $where)
    {
        if (!Session::registeredActivated() && !Cookie::registeredSetted()) {
            Utility::redirect($where);
        }
    }

    /**
     * Permet de vérifier qu'une session est active ou
     * que des cookies de session existent.
     */
    public static function done()
    {
        return Cookie::registeredSetted() || Session::registeredActivated();
    }

    /**
     * Retourne l'id de session ou de cookie pour reconnaitre l'utilisateur
     * connecté.
     * 
     * @return string
     */
    public static function getId()
    {
        if (Session::registeredActivated()) {
            return Session::getRegistered();
        } elseif (Cookie::registeredSetted()) {
            return Cookie::getRegistered();
        }
    }

}