<?php

namespace App\Communication\Notify;

use App\Communication\MailSender;
use App\Model\User\Administrator;
use App\Model\User\Registered;

/**
 * Permet de faire des notifications au utilisateurs par email.
 */
class NotifyByMail extends Notify
{
    /**
     * Envoie un mail aux administrateurs.
     * 
     * @param string $subject Le sujet du mail.
     * @param string $content Le contenu du mail.
     * 
     * @param string
     */
    public static function administrators(string $subject, string $content)
    {
        if ((new MailSender(
                Administrator::getEmailAddresses(),
                $subject,
                $content
            )
        )->send()) {
            return true;
        }
    }

    /**
     * Envoie un mail à tous les inscrits.
     * 
     * @param $emailAddresses La liste des adresses email des utilisateurs inscrits.
     * 
     * @return bool
     */
    public static function registered($emailAddresses, string $subject, string $content)
    {
        self::users(Registered::getEmailAddresses(), $subject, $content);
    }

    /**
     * Permet de notifier un ou plusieurs utilisateurs.
     * 
     * @param array|string $emailAddresses La liste des adresses email en chaîne de caractère
     *                                     ou en tableau.
     * @param string $subject              Le sujet du mail.
     * @param string $content              Le contenu du mail.
     */
    public static function users($emailAddresses, string $subject, string $content)
    {
        if ((new MailSender($emailAddresses, $subject, $content))->send()) {
            return true;
        }
    }

}