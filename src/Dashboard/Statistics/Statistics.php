<?php

namespace App\Dashboard\Statistics;

use App\Model\Post\Post;
use App\Model\User\Visitor;

/**
 * Classe de gestion des statistiques de l'application.
 * 
 * @author Joel <joel.developpeur@gmail.com>
 */
class Statistics
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
        // return Post::getAllPostsNumber();
        return 1;
    }

    /**
     * Retourne les posts validées et publiées.
     */
    public static function getPublishedPostsNumber() : int
    {
        // return Post::getValidatedNumber();
        return 1;
    }

    /**
     * Retourne le nombre de posts en attente.
     */
    public static function getPendingPostsNumber() : int
    {
        // return Post::getPendingNumber();
        return 1;
    }

    /**
     * Retourne le nombre de posts suspendus.
     */
    public static function getSuspendedPostsNumber() : int
    {
        // return Post::getSuspendedNumber();
        return 1;
    }

    /**
     * Retourne le nombre de posts fait le jour courrant.
     */
    public static function getCurrentDayPostsNumber() : int
    {
        // return Post::getCurrentDayPostsNumber();
        return 1;
    }

}