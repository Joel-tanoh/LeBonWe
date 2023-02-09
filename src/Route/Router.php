<?php

/**
 * Description
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  GIT: Joel_tanoh
 * @link     Link
 */

namespace App\Route;

use App\Exception\PageNotFoundException;

/**
 * Routeur de l'application.
 *  
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @version  "Release: package_version"
 * @link     Link
 */
class Router
{
    private $url;
    private $routes = [];

    /**
     * Constructeur du routeur, prend en paramètre l'URL.
     * 
     * @param string $url 
     * 
     * @return void
     */
    public function __construct(string $url = null)
    {
        $this->url = (null === $url) ? trim($_SERVER["REQUEST_URI"], "/") : trim($url, "/");
    }

    /**
     * Permet de modifier l'URL passé en paramètre.
     * 
     * @param string $url
     * 
     * @return void
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * Retourne l'URL contenu dans le variable serveur $_SERVER["REQUEST_URI"].
     * 
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $route  La route = URL.
     *                       Vous pouvez faire préceder les parties de l'URL par ":"
     *                       afin de récupérer les parties de l'URL comme variable.
     *                       Exemple = /:exemple vous permet de récuperer le paramêtre en
     *                       utilisant le tabeau $params["exemple"].
     * @param string $action
     */
    public function get(string $route, string $action)
    {
        $this->routes["GET"][] = new Route($route, $action);
    }


    /**
     * @param string $route  La route = URL.
     *                       Vous pouvez faire préceder les parties de l'URL par ":"
     *                       afin de récupérer les parties de l'URL comme variable.
     *                       Exemple = /:exemple vous permet de récuperer le paramêtre en
     *                       utilisant le tabeau $params["exemple"].
     * @param string $action
     */
    public function post(string $route, string $action)
    {
        $this->routes["POST"][] = new Route($route, $action);
    }

    /**
     * Retourne les routes de l'application.
     * 
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Ctte méthode va pracourrir le tableau des routes, et vérifier si la route 
     * matche, si elle matche, elle exécute la route.
     * 
     * @return mixed
     */
    public function run()
    {
        foreach($this->routes[$_SERVER["REQUEST_METHOD"]] as $route) {
            if ($route->matches($this->url)) {
                return $route->execute();
            }
        }

        throw new PageNotFoundException("Page non trouvée.");
    }

}