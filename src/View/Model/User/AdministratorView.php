<?php

namespace App\View\Model\User;

use App\Admin\Dashboard\DashboardView;

/**
 * Classe de gestion de la vue pour l'administrateur.
 */
class AdministratorView extends RegisteredView
{   
    /**
     * Constructeur de la vue du registered.
     * 
     * @param \App\Model\User\Administrator $user
     */
    public function __construct(\App\Model\User\Administrator $user = null)
    {
        parent::__construct($user);
    }

    /**
     * Vue d'accueil de l'administrateur dans la partie administration.
     * 
     * @return string
     */
    public function adminIndex()
    {
        $visitorsOnlineBox = DashboardView::showvisitorsOnline();
        $currentDayVisitorsNumberBox = DashboardView::showCurrentDayVisitorsNumber();
        $allVisitorsNumber = DashboardView::showAllVisitorsNumber();
        $publishedPosts = DashboardView::showPublishedPostsNumber();
        $pendingPosts = DashboardView::showPendingPostsNumber();
        $suspendedPosts = DashboardView::showSuspendedPostsNumber();
        $currentDayPostsNumber = DashboardView::showCurrentDayPostsNumber();
        $allPostsNumber = DashboardView::showAllPostsNumber();

        $content = <<<HTML
        <h1 class="mb-4">Tableau de Bord</h1>
            <section class="mb-4">
                <h5 class="mb-3">Vos visites</h5>
                <div class="row">
                    {$visitorsOnlineBox}
                    {$currentDayVisitorsNumberBox}
                    {$allVisitorsNumber}
                <div>
            </section>
            <section class="mb-4">
                <h5 class="mb-3">Vos annonces</h5>
                <div class="row">
                    {$publishedPosts}
                    {$pendingPosts}
                    {$suspendedPosts}
                    {$currentDayPostsNumber}
                    {$allPostsNumber}
                <div>
            </section>
HTML;
        return parent::administrationTemplate($content, "Administration", "Administration");
    }

    /**
     * Affiche les utilisateurs.
     * @author Joel-tanoh
     * @return string
     */
    public function readUsers(array $users)
    {
        return parent::administrationTemplate((new RegisteredView())->list($users), "Les utilisateurs", "Administration / Utilisateurs");
    }
    
    /**
     * Vue qui affiche le profile d'un autre utilisateur dont on veut
     * voir le profile.
     * @param \App\Model\User\Registered $user
     * @return string
     */
    public function readUserProfile(\App\Model\User\Registered $user)
    {
        $content = <<<HTML
        <h4>{$user->getFullName()}</h4>
        <div></div>
HTML;
        return parent::administrationTemplate($content, "Profil - ". $user->getFullName(), "Profil / ". $user->getFullName());
    }

    /**
     * Affiche la liste des annonces pour que l'administrateur puisse les manager.
     * 
     * @return string
     */
    public static function readAnnounces(array $announces)
    {
        return (new self())->dashboard($announces, "Toutes les annonces", "Gestion des annonces");
    }

    /**
     * Permet à l'administrateur de voir les statistiques du site.
     * 
     * @return string
     */
    public static function readStatistics()
    {

    }

    /**
     * Affiche les commentaires postés par cet utilisateur.
     * 
     * @return string
     */
    public function showComments()
    {
        return <<<HTML
        
HTML;
    }

}