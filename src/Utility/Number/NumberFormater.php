<?php

namespace App\Utility\Number;

use App\Utility\Utility;

/**
 * Cette classe permet de formater les nombres.
 */
class NumberFormater extends Utility
{
    private $nbrSrc;
    private $nbrResult;

    public function __construct(int $nbrSrc)
    {
        $this->nbrSrc = $nbrSrc;
    }

    /**
     * Retourne le nombre source.
     * 
     * @return int
     */
    public function getNbrSrc($nbrSrc) : int
    {
        return $this->nbrSrc;
    }

    /**
     * Retourne le nombre source formaté.
     * 
     * @param int $nbrSrc Le nombre à formater.
     * 
     * @return string Le nombre formaté.
     */
    public static function getFormated(int $nbrSrc) : string
    {
        if (!is_int($nbrSrc)) {
            return null;
        } else {
            return "";
        }
    }

    /**
     * Retourne les unités du nombre source.
     * 
     * @return array
     */
    public static function getUnits(int $nbrSrc) : array
    {
        return [];
    }

    /**
     * Retourne les centaines du nombre source.
     * 
     * @return array
     */
    public static function getHundreds(int $nbrSrc) : array
    {
        return [];
    }

    /**
     * Retourne les milliers du nombre source.
     * 
     * @return array
     */
    public static function getThousands(int $nbrSrc) : array
    {
        return [];
    }

    /**
     * Retourne les millions du nombre source.
     * 
     * @return array
     */
    public static function getMillions(int $nbrSrc) : array
    {
        return [];
    }

    /**
     * Retourne les milliards du nombre source.
     * 
     * @return array
     */
    public static function getBillions(int $nbrSrc) : array
    {
        return [];
    }
}