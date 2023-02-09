<?php

namespace App\Communication;

use App\File\Image\Logo;
use App\Model\Post;

/**
 * Gestionnaire des contenus de mail.
 */
class MailContentManager
{
    
    /**
     * Retourne le contenu de l'email envoyé lorsqu'uu nouveau post est fait.
     * 
     * @param \App\Model\Post\Post $post 
     * @return string
     */
    public function newPostMail($post)
    {
        $content = <<<HTML
        <p>Un nouveau post a été fait.</p>
        <p style="font-weight: bold;">Détails : <br>
            Titre : {$post->getTitle()} <br>
            Postée par : {$post->getOwner()->getFullName()}<br>
        </p>
HTML;
        return MailContentManager::setContent($content);
    }

    /**
     * Le conteu du mail envoyé lorsqu'une annonce est supprimée.
     * 
     * @param \App\Model\Post\Post $post
     * 
     * @return string
     */
    public static function postDeleted(\App\Model\Post\Post $post)
    {
        $content = <<<HTML
        <p>Un post a été supprimé.</p>
        <p style="font-weight: bold;">Détails : <br>
            Titre : {$post->getTitle()} <br>
            Postée par : {$post->getOwner()->getFullName()}<br>
            Date de création : {$post->getCreatedAt()} <br>
        </p>
HTML;
        return MailContentManager::setContent($content);
    }

    /**
     * Retourne le sujet du mail envoyé lorsque l'announce est validée.
     * 
     * @return string
     */
    public function postValidated()
    {
        return "Un post a été validé !";
    }

    /**
     * Retourne le contenu du mail envoyé lorsque l'annonce est validée.
     * 
     * @return string
     */
    public function validatedPostEmailContent($post)
    {
        $content = <<<HTML
        Félicitations !
        Votre post avec le titre : {$post->getTitle()} a été validé.
HTML;
        return MailContentManager::setContent($content);
    }

    /**
     * Notification envoyée lorsqu'une mise à jour vient d'être faite.
     * 
     * @return string
     */
    public function updatedPost($post)
    {
        return <<<HTML
        <p>Un post vient d'être mis à jour.</p>
        <p>
            Titre : {$post->getTitle()}<br>
            Postée par : {$post->getOwner()->getEmailAddress()}
        </p>
        <p>
            <a href="{$post->getLink('all')}" style="padding:1rem; background-color:">Voir</a>
        </p>
HTML;
    }

    /**
     * Contenu de l'email de notification de changement du mot de passe.
     * 
     * @param \App\Model\User\Registered $user
     * @param string                     $password 
     * 
     * @return string
     */
    public static function passwordChanged(\App\Model\User\Registered $user, string $password)
    {
        return <<<HTML
        <p>Bonjour {$user->getName()}</p>
        <p>Nous vous informons que votre mot de passe a été modifié. <br>
        Votre nouveau mot de passe est : {$password}
        </p>
        
HTML;
    }

    /**
     * Notification d'accueil qui est envoyé par mail lorsque quelqu'un vient de s'inscrire sur
     * le site.
     * 
     * @param \App\Model\User\Registered $user
     * 
     * @return string
     */
    public static function welcomeNotification(\App\Model\User\Registered $user)
    {
        $content = <<<HTML
        <p>Hello {$user->getName()}</p>
        <p>
            Nous sommes heureux de vous compter parmi nos abonnés. Nous ferons le nécessaire pour vous
            accompagner et vous fournir dans la mésure du possible ce que vous cherchez par du contenu
            de qualités et en relation avec vos besoins.
        </p>
        <p>
            Vous recevrez régulièrement les nouvelles informations, les tendances, tout en espérant vous fournir du contenu de qualité.
        </p>
HTML;
        return self::setContent($content);
    }

    /**
     * Permet de formater le contenu de l'email avec des propriétés CSS.
     * 
     * @return string
     */
    public static function setContent(string $content)
    {
        $head = self::appMailHeader();
        $foot = self::appEmailFooter();

        return <<<HTML
        <section style="width:80%;padding:1rem 0">
            {$head}
            <div>{$content}</div>
            {$foot}
        </section>
HTML;
    }

    /**
     * Entête de l'email.
     * 
     * @return string
     */
    private static function appMailHeader()
    {
        $logoSrc =  Logo::LOGOS_DIR_URL . "/logo-colored.png";

        return <<<HTML
        <div style="width:700px; font-family:Arial; padding-left:1.5rem">
            <div>
                <img src="{$logoSrc}" alt="L'indice">
            </div>
HTML;
    }
    
    /**
     * Pieds de l'email envoyé.
     * 
     * @return string
     */
    private static function appEmailFooter()
    {
        $appUrl = APP_URL;

        return <<<HTML
            <p style="margin:1.3rem 0">
                Merci de nous faire confiance pour vos annonces.<br>
                <a style="text-decoration:none;color:#003956" href="{$appUrl}">Aller sur Lindice.ci</a>
            </p>
        </div>
HTML;
    }

}