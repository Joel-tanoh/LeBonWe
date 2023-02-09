<?php

namespace App\Engine;

use App\Action\Action;
use App\Model\Post\Announce;

/**
 * Moteur de recherche de l'application.
 */
class SearchEngine extends Action
{
    /**
     * Constructeur d'un moteur de recherche.
     *
     */
    public function __construct(string $database = DB_NAME, string $dbLogin = DB_LOGIN, string $dbPassword = DB_PASSWORD)
    {
        $this->database = $database;
        $this->dbLogin = $dbLogin;
        $this->dbPassword = $dbPassword;
    }

    /**
     * Permet de chercher les annonces.
     */
    public function searchAnnounces(array $dataSent)
    {
        $this->searchAnnounceQuery($dataSent);
        
        $req = parent::connectToDb($this->dbLogin, $this->dbPassword)->prepare($this->query);

        $req->execute($this->dataTreated($dataSent));
        $this->data = $req->fetchAll();
    }

    /** 
     * Retourne les résultats de la recherche.
     * 
     * @param string $className         Possible de passer le nom d'une class afin
     *                                  de retourner un tableau d'objet.
     * @param string $colForInstantiate L'index de la colonne à utiliser pour instantier
     *                                  les objets.
     * 
     * @return array Un Tableau contenant les données prises de la base ddvonnées.
     */
    public function getResult(string $className = null, string $colForInstantiate = null)
    {
        $result = [];

        if (null !== $className) {
            foreach($this->data as $item) {
                $result[] = new $className($item[$colForInstantiate]);
            }

            return $result;
        } else {
            return $this->data;
        }
    }

    /**
     * Retourne le nombre de résultat.
     * 
     * @return int
     */
    public function resultNumber()
    {
        return count($this->data);
    }

    /**
     * Permet de formater la requête.
     */
    private function searchAnnounceQuery(array $dataSent = null)
    {
        $this->query = "SELECT id FROM " . Announce::TABLE_NAME . " WHERE (title LIKE :query OR description LIKE :query)";

        if (!empty($dataSent["id_category"])) {
            $this->query .= " AND id_category = :id_category";
        }

        if (!empty($dataSent["location"])) {
            $this->query .= " AND location = :location";
        }

        if (!empty($dataSent["type"])) {
            $this->query .= " AND type = :type";
        }

        if (!empty($dataSent["direction"])) {
            $this->query .= " AND direction = :direction";
        }

        if (!empty($dataSent["price"])) {
            $this->query .= " AND price = :price";
        }

        $this->query .= " AND status IN (2, 3)";
    }

    /**
     * Formate la requête pour faire des recherches sur les utilisateurs.
     */
    public function searchUsers()
    {

    }

    /**
     * Permet de traiter les données envoyées par l'utilisateur
     * pour la recherche.
     * 
     * @param array $data Un tableau contenant les données saisies par l'utilisateur.
     * @return array Tableau contenant les données traitées.
     */
    private function dataTreated(array $dataSent)
    {
        $data = [];

        $query = $dataSent["query"] ?? $dataSent["search_query"] ?? $dataSent["request"] ?? $dataSent["q"];

        if (!empty($query)) {
            $data["query"] = "%" . htmlspecialchars(trim($query)) . "%";
        }

        if (!empty($dataSent["id_category"])) {
            $data["id_category"] = (int)htmlspecialchars(trim($dataSent["id_category"]));
        }

        if (!empty($dataSent["location"])) {
            $data["location"] = htmlspecialchars(trim($dataSent["location"]));
        }

        if (!empty($dataSent["type"])) {
            $data["type"] = htmlspecialchars(trim($dataSent["type"]));
        }

        if (!empty($dataSent["direction"])) {
            $data["direction"] = htmlspecialchars(trim($dataSent["direction"]));
        }

        if (!empty($dataSent["price"])) {
            $data["price"] = htmlspecialchars(trim($dataSent["price"]));
        }

        return $data;
    }

}