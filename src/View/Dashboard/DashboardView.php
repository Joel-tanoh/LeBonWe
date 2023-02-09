<?php

namespace App\View\Dashboard;

use App\Admin\Dashboard\Dashboard;

/**
 * Classe de gestion des vues Dashboard.
 */
class DashboardView
{

    /**
     * Retourne le code d'une box avec le nombre de visiteurs en ligne.
     */
    public static function showvisitorsOnline() : string
    {
        return self::KPIBox(Dashboard::visitorsOnlineNumber(), "Visiteur(s) en ligne", "fas fa-users fa-2x", "bg-success", "text-white");
    }

    /**
     * Retourne le code d'une box avec le nombre de visite de la journée en cours.
     */
    public static function showCurrentDayVisitorsNumber() : string
    {
        return self::KPIBox(Dashboard::getCurrentDayVisitsNumber(), "Visite(s) aujourd'hui", "fas fa-users fa-2x", "bg-dark", "text-white");
    }

    /**
     * Affiche le nombre total de visites.
     */
    public static function showAllVisitorsNumber() : string
    {
        return self::KPIBox(Dashboard::getAllVisitorsNumber(), "Visite(s) au total", "fas fa-users fa-2x", "bg-primary", "text-white");
    }

    /**
     * Affiche le nombre total de posts.
     */
    public static function showAllPostsNumber() : string
    {
        return self::KPIBox(Dashboard::getAllPostsNumber(), "Announce(s) postée(s) au total", "fas fa-folder fa-2x", "bg-primary", "text-white");
    }

    /**
     * Affiche le nombre de posts fait le jour courrant.
     */
    public static function showCurrentDayPostsNumber() : string
    {
        return self::KPIBox(Dashboard::getCurrentDayPostsNumber(), "Annonce(s) postée(s) aujourd'hui", "fas fa-folder fa-2x", "bg-dark", "text-white");
    }

    /**
     * Affiche le nombre de posts en attente de validation.
     */
    public static function showPendingPostsNumber() : string
    {
        return self::KPIBox(Dashboard::getPendingPostsNumber(), "Annonce(s) en attente", "fas fa-folder fa-2x", "bg-warning", "text-white");
    }

    /**
     * Retourne le nombre de posts publiés.
     */
    public static function showPublishedPostsNumber() : string
    {
        return self::KPIBox(Dashboard::getPublishedPostsNumber(), "Annonce(s) validée(s)", "fas fa-folder fa-2x", "bg-success", "text-white");
    }

    /**
     * Affiche le nombre de posts suspendues.
     */
    public static function showSuspendedPostsNumber() : string
    {
        return self::KPIBox(Dashboard::getSuspendedPostsNumber(), "Annonce(s) suspendue(s)", "fas fa-folder fa-2x", "bg-danger", "text-white");
    }

    /**
     * Affiche une box avec un nombre, un petit texte et une icône.
     * 
     * @param int $kpi                              Le nombre (KPI) à afficher.
     * @param string $text                          Le texte descriptif du KPI.
     * @param string|null $iconClass                A passer si on veut afficher une icône, cela
     *                                              peut être une icône Bootstrap ou autres.
     * @param string|null $boxBgBootstrapClassColor La classe Bootstrap qui permet de définir la couleur
     *                                              du background de la box. Par défaut elle est à "light".
     * @param string      $text-color               La classe Bootstrap pour la couleur fdu texte.
     * 
     * @return string
     */
    public static function KPIBox(
        int $kpi, string $text
        , string $iconClass = null
        , string $boxBgBootstrapClassColor = "light"
        , $textColor = null
    ) : string
    {
        $icon = null;
        if ($iconClass) {
            $icon = '<span class="mr-2"><i class="' . $iconClass . '"></i></span>';
        }

        return <<<HTML
        <div class="col-12 col-md-3">
            <div class="{$boxBgBootstrapClassColor} {$textColor} p-3 mb-2 rounded">
                <div class="mb-2">
                    $icon<span class="h1">$kpi</span>
                </div>
                <div class="justify-content-end">$text</div>
            </div>
        </div>
HTML;
    }

}