<?php

namespace App\Action\Get;

use App\Action\Action;
use Exception;

/**
 * Classe de gestion d'une recherche
 */
class SearchInDb extends Action
{
    /**
     * Constructeur d'une recherche.
     */
    public function __construct(
        string $database = DB_NAME,
        string $dbLogin = DB_LOGIN,
        string $dbPassword = DB_PASSWORD,
        string $query = null
    ) {
        $this->database = DB_NAME;
        $this->dbLogin = DB_LOGIN;
        $this->dbPassword = DB_PASSWORD;
        $this->query = $query;
        $this->run();
    }

    /**
     * Permet de passer le nom de la base de données.
     * @param string $database
     */
    public function setDatabase(string $database)
    {
        $this->database = $database;
    }

    /**
     * Permet de passer la requête de la recherche.
     * 
     * @param string $query
     */
    public function query(string $query)
    {
        $this->query = $query;
    }

    /**
     * Retourne les résultats de la recherche.
     * 
     * @param string $type                On peut spécifier "objet" pour dire qu'on veut
     *                                    retourner les résultats sous forme d'objets.
     * @param string $className           La classe des objets.
     * @param string $indexForInstantiate Le nom de l'index qu'on av utiliser pour instantier
     *                                    les objets.
     */
    public function getResult(string $type = null, string $className = null, string $indexForInstantiate = null)
    {
        if ($type !== null && in_array($type, ["object", "objet", "instance", "class"])) {

            $result = [];

            if (strpos($this->query, $indexForInstantiate)) {
                foreach ($this->data as $r) {
                    $result[] = new $className($r[$indexForInstantiate]);
                }
                return $result;
            } else {
                throw new Exception("L'index pour l'instantiation des objets doit se trouver dans la requête.");
            }

        } else {
            return $this->data;
        }
    }

    /**
     * Permet d'exécuter le requête.
     */
    private function run()
    {
        $req = parent::connectToDb($this->dbLogin, $this->dbPassword)->query($this->query);
        $this->data = $req->fetchAll();
    }
}