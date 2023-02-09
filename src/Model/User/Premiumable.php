<?php

namespace App\Model\User;


/**
 * Classe de gestion des utilisateurs prémium.
 */
Interface Premiumable
{
    /** @var array La liste des privilèges des utilisateurs prémiums */
    const PRIVILEGES = [
        "POST_PER_MONT" => 30
        ,
    ];
}