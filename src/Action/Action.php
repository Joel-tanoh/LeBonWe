<?php

namespace App\Action;

use App\Database\Database;

abstract class Action
{
    /** @var PDO Instance PDO pour exécuter les requêtes sur la base de 
     * doonées.
     */
    protected $pdo;

    /** @var string Le nom de la base de données. */
    protected $database;

    /** @var string Le nom de la table de laquelle on récupère les données. */
    protected $tableName;
    
    /** @var string Le nom d'utilisateur pour se connecter à la base de données. */
    protected $dbLogin;

    /** @var string Le mot de passe à utiliser pour se connecter à la 
     *  base de données.
     */
    protected $dbPassword;

    /** @var array Un tableau associatif qui contient les données à
     * insérer ou à retourner
     */
    protected $data;

    /** @var string La requête finale a envoyer à la base de données */
    protected $query;

    /** @var string Le nom de la colonne à utiliser pour instantier les résultats sous
     *              forme d'objet.
     */
    protected $colForInstantiate;

    /** @var array Tableau associatif contenant l'ensemble des clauses 
     * injectées dans la reqûete de mise à jour.
     */
    protected $clauses;

    /** @var string La clause en format text */
    protected $clausesAsString;


    /**
     * Retourne l'instance PDO.
     * 
     * @return PDO
     */
    public function getPDO()
    {
        return $this->pdo;
    }

    /**
     * Permet de vérifier qu'un ou plusieurs fichiers ont été uploadés.
     * 
     * @param string $key La clé dans le tableau.
     * 
     * @return bool
     */
    public static function fileIsUploaded(string $key)
    {
        return !empty($_FILES[$key]["name"][0]);
    }

    /**
     * Permet de vérifier si des données ont été postées.
     * 
     * @return bool
     */
    public static function dataPosted()
    {
        return isset($_POST) && !empty($_POST);
    }
    
    /**
     * Retourne la requête.
     * @return string
     */
    public function getQuery()
    {
        $this->formatQuery();
        return $this->query;
    }

    /**
     * Permet de se connecter à une base de données dans le cas où
     * l'action porte sur une donnée dans cette base de données.
     * 
     * @return PDO
     */
    protected function connectToDb(string $dbLogin, string $dbPassword)
    {
        $this->pdo = (new Database($this->database, $dbLogin, $dbPassword))->connect();
        return $this->pdo;
    }

    /**
     * Permet de formater la requête SQL pour insérer les données dans la base de données.
     */
    public function formatQuery(){}

    /**
     * Permet de passer la colonne à utiliser pour instancier les résultats sous
     * forme d'objet.
     * 
     * @param string $colForInstantiate Le nom de la colonne qu'on va utiliser pour
     *                                  instantier les résultats sous forme d'objet.
     */
    public function setColForInstantiate(string $colForInstantiate)
    {
        $this->setColForInstantiate = $colForInstantiate;
    }

    /**
     * Permet de formater la clause en format texte et l'ajouter à la requête.
     * 
     * @param array $clauses   Un tableau contenant les clauses permettant d'apporter
     *                         des spécifications dans la requête fournie.
     * @param string $operator Permet de dire si on veut que la requête, ce soit des "et"
     *                         ou des "ou" entre les clauses.
     */
    public function formatClauses(array $clauses = null, string $operator = "AND")
    {
        $clauses = empty($this->clauses) && !empty($clauses) ? $clauses : $this->clauses;

        // Formatage des composantes de la clause
        $arrayKeys = array_keys($clauses);
        $clauseString = null;

        foreach($arrayKeys as $key) {
            $clauseString .= "$key = :$key $operator";
        }

        // Rétirer les dernières virgules et espaces à la fin de la chaine de caractère
        $clauseString = rtrim($clauseString, $operator . " ");

        // $this->clauseAsString = " WHERE $clauseString";
        $this->query .= " WHERE $clauseString";
    }

}