<?php

namespace App\Model\Location;

use App\Model\Model;

/** Classe abstraite de gestion de la localisation */
abstract class Location extends Model
{
    /** @var string Nom*/
    protected $name;

    /**
     * Retourne le nom.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}