<?php

namespace App\Request;

/** Classe de gestion de l'url et tout ce qui
 * tourne autour.
 */
class Url
{
    
    /**
     * Cette méthode fonctionne avec la variable
     * serveur $_SERVER["REQUEST_URI]. Elle Nous retourne
     * les différentes parties de $_SERVER["REQUEST_URI] en les
     * separant avec le séparateur passé en paramètre. Si on se trouve sur
     * l'index, cette méthode retourne null, vu que $_SERVER["REQUEST_URI"] ne
     * contient rien.
     * 
     * @param string $separator
     * 
     * @return array Un tableau contenant les parties de $_SERVER["REQUEST_URI"]
     *               en fonction du séparateur.
     */
    public static function getUrlAsArray(string $separator = "/")
    {
        return self::explodeUrl(self::getRequestUri(), $separator);
    }

    /**
     * Permet de découper l'url qui se trouve dans la varaible $_GET["url"]
     * en plusieurs parties et les retourne.
     * 
     * @param string $separator
     * 
     * @return array
     */
    public static function getGETUrlAsArray(string $separator = "/")
    {
        return self::explodeUrl(self::getGETurl(), $separator);
    }

    /**
     * Retourne la longueur de l'url
     * 
     * @return int
     */
    public static function urlLength()
    {
        return count(self::explodeUrl(self::getRequestUri()));
    }

    /**
     * Retourne toute l'url se trouvant après le nom de domaine.
     * 
     * @return string
     */
    public static function getRequestUri()
    {
        return trim($_SERVER["REQUEST_URI"], "/");
    }

    /**
     * Cette méthode retourne le contenu de la variable $_GET["url"].
     * Pour faire exister cette variable, il faut un fichier .htaccess dans le
     * même dossier que le fichier index qui lui retourne toutes les urls demandées et
     * accessibles par la variable $_GET["url"];
     * Le fichier .htaccess doit être dans le même dossier que le fichier routeur(index dans
     * la plupart des cas) et doit contenir le code suivant au minimum.
     * 
     * RewriteEngine On
     * 
     * RewriteCond %{REQUEST_FILENAME} !-f 
     * RewriteCond %{REQUEST_FILENAME} !-d 
     * RewriteRule ^(.*)$ index.php?url=$1 [QSA,NC] 
     * 
     * @return string
     */
    public static function getGETurl()
    {
        $url = isset($_GET["url"]) ? $_GET["url"] : "/";
        return trim($url, "/");
    }

    /**
     * Découpe l'url contenue dans la variable serveur
     * $_SERVER["REQUEST_URI"] avec le séparateur passé en paramètre.
     * 
     * @param string $url
     * @param string $separator
     * 
     * @return array Un tableau contenant les valeurs separées de l'url.
     */
    private static function explodeUrl(string $url, string $separator = "/")
    {
        if ($url === "/") {
            return null;
        }

        $uriParts = explode($separator, $url);
        return $uriParts;
    }

}