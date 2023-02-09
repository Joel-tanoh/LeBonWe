<?php

namespace App\Route;

use App\Request\Url;

/**
 * Classe de gestion d'une route.
 */
class Route
{
    protected $route;
    protected $action;
    protected $params = [];

    public function __construct(string $route, string $action)
    {
        $this->route = trim($route, "/");
        $this->action = $action;
    }

    /**
     * Retourne true si la route coincide avec l'url.
     * 
     * @return bool
     */
    public function matches($url)
    {
        if (!$this->hasParams()) {
            return $this->route == $url;
        } else {
            $this->paramsName();
            return $this->length() === Url::urlLength();
        }
    }

    /**
     * Exécute l'action de la route.
     * 
     * @return mixed
     */
    public function execute()
    {
        $actionParams = explode("@", $this->action);
        $method = $actionParams[1];
        
        if (!$this->params) {
            return $actionParams[0]::$method();
        } else {
            return $actionParams[0]::$method($this->getParams());
        }
    }

    /**
     * Rétourne les paramètres qui sont dans la route.
     * 
     * @return array
     */
    private function paramsName() : array
    {
        preg_match_all("#:([\w]+)#", $this->route, $matches);
        $this->params = $matches[1];
        return $this->params;
    }

    /**
     * Permet de vérifier si la route contient des paramètres.
     * 
     * @return bool
     */
    private function hasParams()
    {
        return count($this->paramsName()) !== 0;
    }

    /**
     * Retourne les parties de la route.
     * 
     * @return array
     */
    private function parts()
    {
        return explode("/", $this->route);
    }

    /**
     * Retourne la longeur de la route.
     * 
     * @return int
     */
    private function length()
    {
        return count($this->parts());
    }

    /**
     * Retourne un tableau dans lequel les clés sont les valeurs
     * de la route et les valeurs sont les issues de l'url.
     * 
     * @return array
     */
    public function getParams()
    {
        $routesParams = [];
        $routeSplited = explode("/", $this->route);
        for($i = 0; $i < count($routeSplited); $i++) {
            $routesParams[trim($routeSplited[$i], ":")] = Url::getUrlAsArray()[$i];
        }

        return $routesParams;
    }

}