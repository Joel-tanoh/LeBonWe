<?php

namespace App\Controller\UserController;

use App\Action\Action;
use App\Communication\Notify\NotifyByMail;
use App\Controller\UserController\RegisteredController;
use App\Exception\PageNotFoundException;
use App\Model\Post\Announce;
use App\Model\User\Registered;
use App\Model\User\User;
use App\Utility\Utility;
use App\View\Model\User\AdministratorView;
use App\View\Page\Page;
use App\View\View;
use Exception;

abstract class AdministratorController extends RegisteredController
{
    /**
     * Index de la partie administration.
     */
    public static function index()
    {
        $page = new Page("Administration &#149; L'indice", (new AdministratorView)->adminIndex());
        $page->setDescription("Vous êtes sur la partie Administration de votre site");
        $page->show();
    }

    /**
     * Controller qui permet de gérer les annonces.
     * 
     * @param array $params
     * @return void
     */
    public static function administrateAnnounces(array $params = null)
    {
        if (empty($params)) {
            $announces = Announce::getAll();
        } else {
            $announces = Announce::getByStatus($params[3]);
        }

        $page = new Page("Administration - Gérer les annonces &#149; L'indice", AdministratorView::readAnnounces($announces));
        $page->show();
    }
    
    /**
     * Controller pour afficher tous les comptes.
     * @return void
     */
    public static function readUsers(array $params = null)
    {
        User::askToAuthenticate("/sign-in");

        if (User::authenticated()->isAdministrator()) {
            $users = Registered::getAll();
            $page = new Page("Administration - Liste des utilisateurs &#149; L'indice");
            $page->setView((new AdministratorView(User::authenticated()))->readUsers($users));
            $page->show();
        } else {
            throw new Exception("Oup's ! Nous n'avons pas trouvé la ressource que vous cherchez !");
        }
    }

    /**
     * Controller du profil de l'utilisateur.
     * 
     * @param \App\Model\User\Registered $user
     * @return void
     */
    public static function readUser(\App\Model\User\Registered $user)
    {
        User::askToAuthenticate("/sign-in");

        $registered = User::authenticated();

        if ($registered->isAdministrator()) {
            (new Page("Profil - " . $user->getFullName(). " &#149; L'indice", (new AdministratorView($registered))->readUserProfile($user)))->show();
        } else {
            Utility::redirect($registered->getProfileLink() . "/posts");
        }
    }

    /**
     * Controller de suppression de plusieurs utilisateurs.
     * 
     * @param array $params
     * 
     * @return void
     */
    public static function deleteUsers(array $params = null)
    {
        if (!User::authenticated()) {
            User::askToAuthenticate();
        } else {
            $page = new Page;
            
            // 1 - Récupérer les utilisateurs à supprimer
            // 2a - Si le tableau est vide, on revient sur la page de suppression et on affiche une notif
            // 2b - Si le tableau n'est pas vide
            // 3a - Si les identifiants ne sont pas dans la base de données, on revient sur la page de suppression et on affiche une notif
            // 3b - Si les identifiants sont dans la base de données
            // 4 - On récupère le nombre d'utilisateurs
            // 5 - On les supprime
            // 6a - Si la suppression a marché, on affiche la page de succès
            // 6b - Si la suppression n'a pas marché, revient sur la page de suppression et n affiche une notif
        }
    }

    /**
     * Controller de suppression d'un user.
     * @param \App\Model\User\Registered $user
     * @return void
     */
    public static function deleteUser(\App\Model\User\Registered $user)
    {
        
    }

    /**
     * Permet à un administrateur de commenter une annonce.
     * 
     * @param \App\Model\Post\Announce $announce
     */
    public static function commentAnnounce(\App\Model\Post\Announce $announce)
    {
        $page = new Page();

        if (User::authenticated()->isAdministrator() && Action::dataPosted()) {
            if(User::authenticated()->comment($announce->getId(), htmlspecialchars(trim($_POST["comment"])), Announce::TABLE_NAME)) {
                
                NotifyByMail::users(
                    $announce->getOwner()->getEmailAddress(), 
                    "Lindice : un nouveau commentaire sur votre annonce",
                    Registered::newCommentMail($announce->getTitle(), trim($_POST["comment"]), $announce->getLink("all"))
                );
                
                $page->setMetatitle("Commentaire envoyé avec succès &#149; L'indice");
                $page->setView(
                    View::success(
                        "Commentaire envoyée avec succès",
                        "Le commentaire a été envoyée à l'utilisateur avec succès !",
                        "Retour",
                        $announce->getLink(),
                        "Commentaire envoyée"
                    )
                );
                $page->show();
            } else {
                $page->setMetatitle("Echec de l'envoi du commentaire &#149; L'indice");
                $page->setView(
                    View::failed(
                        "Echec lors de l'envoi du commentaire",
                        "Nous avons rencontré une erreur lors de l'envoi du commentaire, veuillez réessayer ultérieurement.",
                        "Retour",
                        $announce->getLink(),
                        "Echec de l'envoi du commentaire"
                    )
                );
                $page->show();
            }
        } else {
            throw new PageNotFoundException("Oup's ! Nous n'avons pas pu repondre à votre requête, veuillez réessayer ultérieurement.");
        }
    }

}