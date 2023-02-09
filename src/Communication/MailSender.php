<?php

/**
 * Fichier de classe
 * 
 * PHP verison 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @version  "GIT: <Joel-tanoh>"
 * @link     Link
 */

namespace App\Communication;

use Exception;

/**
 * Gère les envois d'email.
 * 
 * PHP verison 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license
 * @version  "Release: <package_version>"
 * @link     Link
 */
class MailSender
{
    /** Le ou les destinataires du mail */
    private $to = [];

    /** @var string Le sujet du mail */
    private $subject;

    /** @var string La notification */
    private $notification;

    /** @var string Le destinateur du mail */
    private $from;

    /** @var string Entête de l'email */
    private $headers;

    /** @var bool Permet de signifier qu'on veut ajouter des fichiers */
    private $joinFile;

    /** @var string Le séparateur */
    private $separator;

    /**
     * Constructeur de MailSender
     * 
     * @param string|array $destinataire Ceux à qui on envoit le mail.
     * @param string       $subject      Le sujet du mail.
     * @param string       $notification La notification à envoyer.
     * @param string       $from         L'email d'envoie qui apparaitra dans le mail.
     * @param bool         $joinFile     True si le mail contient des fichiers joints.
     * 
     * @return void
     */
    public function __construct($to, string $subject, string $notification, string $from = null, string $separator = "\r\n", bool $joinFile = null)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->notification = $notification;
        $this->separator = $separator;
        $this->from = $from;
        $this->joinFile = $joinFile;
        $this->treatTo();
        $this->headers();
    }

    /**
     * Retourne les adresses email des destinataires.
     * 
     * @return string[]
     */
    public function getTo()
    {
        return $this->treatTo();
    }

    /**
     * La méthode qui envoie les mails aux destinataires.
     * 
     * @return bool
     */
    public function send()
    {
        if (!empty($this->to)) {
            return mail($this->treatTo(), $this->subject, $this->notification, $this->headers);
        }
    }

    /**
     * Retourne les headers.
     * 
     * @return string
     */
    private function headers()
    {
        $this->headers = "MIME-Version: 1.0" . $this->separator;
        $this->headers .= "Content-type:text/html;charset=UTF-8" . $this->separator;

        if ($this->from !== null) {
            $this->headers .= "From: " . $this->from . $this->separator;
        }

        if ($this->joinFile) {}

        return $this->headers;
    }

    /**
     * Permet de traiter les destinataires, si $destinataires est un tableau,
     * les valeurs sont collées en les séparant par ", ".
     * 
     * @return string
     */
    private function treatTo()
    {
        if (is_string($this->to)) {
            return $this->to;
        }elseif (is_array($this->to)) {
            return implode(", ", $this->to);
        } elseif (is_int($this->to)) {
            throw new Exception("Une erreur s'est produite lors de l'envoi de mail.");
        }
    }

}