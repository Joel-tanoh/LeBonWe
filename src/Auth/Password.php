<?php

namespace App\Auth;

/**
 * Classe de gestion de mot de passe.
 */
class Password
{
    private $password;
    private $length;
    private $hashed;
    private $errors;
    private $notifier;

    const PASSWORD_VALID_LENGTH = 8;

    /**
     * Constructeur d'un objet mot de passe.
     * 
     * @param $var Le mot de passe.
     */
    public function __construct($var)
    {
        $this->password = $var;
        $this->length = strlen($var);
        $this->notifier = new PasswordNotification();
    }

    /**
     * Retourne le format hashé du mot de passe.
     * 
     * @return string
     */
    public function getHashed()
    {
        if ($this->isValid()) {
            $this->hashed = password_hash($this->password, PASSWORD_DEFAULT);
            return $this->hashed;
        }
    }

    /**
     * Permet de faire la validation d'un mot de passe et le mot de passe de
     * confirmation.
     * 
     * @param string $confirmationPassword.
     */
    public function validate(string $confirmationPassword)
    {
        $this->isValid();
        $this->validateConfirmation($confirmationPassword);
    }

    /**
     * Effectue les validations sur le mot de passe.
     * 
     * @return bool
     */
    public function isValid()
    {
        if (empty($this->password)) {
            $this->errors = $this->notifier->passwordIsEmpty();
        } elseif (!$this->hasValidLength()) {
            $this->errors = $this->notifier->passwordLengthIsInvalid();
        }
    }

    /**
     * Compare les deux mots de passe passé en paramètre
     * 
     * @param string $confirmPassword Le second mot de passe.
     * 
     * @author Joel
     * @return bool
     */
    public function validateConfirmation(string $confirmPassword)
    {
        if (empty($confirmPassword)) {
            $this->errors = $this->notifier->confirmPasswordIsEmpty();
        } elseif ($this->password !== $confirmPassword) {
            $this->errors = $this->notifier->passwordsNotIdentics();
        }        
    }

    /**
     * Retourne true s'il n'y a aucune erreur.
     * 
     * @return bool
     */
    public function noErrors() : bool
    {
        return count($this->errors) == 0;
    }

    /**
     * Retourne les notifications d'erreur lors de la validation du mot de passe.
     * 
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Permet de vérifier la validité d'un mot de passe avec sa version
     * hashée.
     * 
     * @param $password
     * @param $hashed
     * 
     * @return bool
     */
    public static function verifyHash($password, $hashed) {
        return password_verify($password, $hashed);
    }

    /**
     * Permet de vérifier que le mot de passe a une longeur valide.
     * 
     * @return bool
     */
    private function hasValidLength()
    {
        return $this->length >= self::PASSWORD_VALID_LENGTH;
    }

}