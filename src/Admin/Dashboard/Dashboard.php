<?php

namespace App\Admin\Dashboard;

use App\Model\Post\Announce;
use App\Model\User\Visitor;

/**
 * Classe de gestion du dashboard.
 */
class Dashboard
{
    /**
     * Retourne le nombre de visiteurs en ligne.
     */
    public static function visitorsOnlineNumber() : int
    {
        return Visitor::onlineNumber();
    }

    /**
     * Retourne le nombre de visites de la journée en cours.
     */
    public static function getCurrentDayVisitsNumber() : int
    {
        return Visitor::getCurrentDayVisitsNumber();
    }

    /**
     * Retourne le nombre total de visiteurs.
     */
    public static function getAllVisitorsNumber() : int
    {
        return Visitor::getAllNumber();
    }

    /**
     * Retourne le nombre total de posts.
     */
    public static function getAllPostsNumber() : int
    {
        return Announce::getAllNumber();
    }

    /**
     * Retourne les posts validées et publiées.
     */
    public static function getPublishedPostsNumber() : int
    {
        return Announce::getValidatedNumber();
    }

    /**
     * Retourne le nombre de posts en attente.
     */
    public static function getPendingPostsNumber() : int
    {
        return Announce::getPendingNumber();
    }

    /**
     * Retourne le nombre de posts suspendus.
     */
    public static function getSuspendedPostsNumber() : int
    {
        return Announce::getSuspendedNumber();
    }

    /**
     * Retourne le nombre de posts fait le jour courrant.
     */
    public static function getCurrentDayPostsNumber() : int
    {
        return Announce::getCurrentDayPostsNumber();
    }

}