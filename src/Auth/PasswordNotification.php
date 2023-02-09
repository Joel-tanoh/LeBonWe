<?php

namespace App\Auth;

use App\Communication\Notify\NotificationText;

/**
 * Classe de gestion des notifications concernant les mots de passe.
 */
class PasswordNotification extends NotificationText
{
    /**
     * Retourne que les mots de passes ne sont pas identiques.
     * 
     * @author Joel
     * @return string
     */
    public function passwordsNotIdentics() : string
    {
        return "Veuillez vérifier que les mots de passes sont identiques !";
    }

    /**
     * Veuillez confirmer le mot de passe.
     * 
     * @return string
     */
    public function confirmPasswordIsEmpty()
    {
        return "Veuillez confirmer le mot de passe !";
    }
  
    /**
     * Retourne que le champ de mot de passe est vide.
     * 
     * @return string
     */
    public function passwordIsEmpty()
    {
        return "Veuillez saisir un mot de passe !";
    }

    /**
     * Retourne que le mot de passe saisi est invalide.
     * 
     * @return string
     */
    public function passwordLengthIsInvalid()
    {
        return "Veuillez saisir un mot de passe de plus de 8 caractères !";
    }

}