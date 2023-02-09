<?php

namespace App\Utility;

use Cocur\Slugify\Slugify;

/**
 * Gère toutes les fonctions de l'application.
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class Utility
{
    /**
     * Génère et retourne un code aléatoire.
     * 
     * @param int $length La longeur du code.
     * 
     * @author Joel
     * @return string
     */
    public static function generateCode(int $length = 10)
    {
        $code = '';
        $string_array = explode(
            ',',
            '0,1,2,3,4,5,6,7,8,9,_,A,a,B,b,C,c,D,d,E,e,F,f,G,g,H,h,I,i,J,j,K,k,L,l,M,n,O,o,P,p,Q,q,R,r,S,s,T,t,U,u,V,v,W,w,X,x,Y,y,Z,z'
        );

        $code_length = random_int(5, $length);
        $string_array_length = count($string_array);

        for ($i = 0; $i <= $code_length; $i++) {
            $j = random_int(0, $string_array_length - 1);
            $code .= $string_array[$j];
        }

        return $code;
    }

    /**
     * Permet de faire une redirection vers l'url passé en paramètre.
     * 
     * @param string $url L'url sur lequel faire la redirection.
     * 
     * @return void
     */
    static function redirect(string $url = null)
    {
        header("location: /" . trim($url, "/"));
        exit();
    }
    
    /**
     * Retourne un slugify qui peut être utilisé par toutes les classes.
     * 
     * @param string $string La chaîne de caractère qu'on veut sluguer.
     * 
     * @return string La chaîne de caractère slugué.
     */
    static function slugify($string)
    {
        $slugify = new Slugify(['rulesets' => ['default', 'turkish']]);
        return $slugify->slugify($string);
    }

    /**
     * Permet de convertir ue date en français.
     * 
     * @param string $date      Au format YYYY-mm-dd HH:ii:ss.
     * @param string $precision Permet de spécifier si l'on veut seulement
     *                          le jour ou l'heure. Si l'on veut le jour, on
     *                          passe en paramètre "D", si l'on veut l'heure,
     *                          on passe en paramètre "H".
     * @param bool   $shortLy
     * 
     * @return string
     */
    public static function formatDate($date, string $precision = null, bool $shortLy = true) 
    {
        if (null === $date) {
            return null;
        }
        
        list($date, $time) = explode(" ", $date);
        list($year, $month, $day) = explode("-", $date);
        list($hour, $min, $sec) = explode(":", $time);

        $month = Utility::convertMonth($month, $shortLy);

        if (in_array($precision, ["DAY", "Day", "day", "D", "d", "JOUR", "Jour", "jour", "J", "j"])) {
            return $day . " " . $month . " " . $year;
        } elseif (in_array($precision, ["HOUR", "Hour", "hour", "H", "h", "HEURE", "Heure"])) {
            return $hour . ':' . $min;
        } else {
            return "$day $month $year à $hour : $min";
        }
    }

    /**
     * Convertit un chiffre en mois.
     * 
     * @param int  $monthNumber 
     * @param bool $shortLy       Pour signifier qu'on veut le mois en format court
     * 
     * @return string
     */
    public static function convertMonth(int $monthNumber, bool $shortLy = null)
    {
        if ($shortLy) {
            $monthToUse = array('jan.', 'fév.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.');
        } else {
            $monthToUse = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
        }
        return $monthToUse[$monthNumber - 1];
    }

}
