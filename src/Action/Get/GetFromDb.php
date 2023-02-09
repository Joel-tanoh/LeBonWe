<?php

namespace App\Action\Get;

use App\Database\SqlQueryFormater;
use Exception;

/**
 * Permet de récupérer des données de la base de données.
 */
class GetFromDb extends Get
{
    /** Les colonnes à récupérer */
    private $cols;

    /** @var array Tableau associatif contenant les clauses afin de 
     * filter les résultats.
    */
    private $whereClauses = [];

    /** @var string Colonne par rapport à laquelle il faut ordonner
     * les résultats de la requête.
     */
    private $orderBy;

    /** @var int Permet de spécifier à partir du quelième élément il
     * faut récupérer les données.
     */
    private $begining;

    /** @var int Pour spécifer le numéro du dernier élément à retourner
     * dans les résultats.
     */
    private $itemNumber;

    /**
     * Constructeur de l'action insert.
     * 
     * @param string $database   Le nom de la base de données dans laquelle
     *                           se trouve la table dans laquelle les données 
     *                           seront mises à jour.
     * @param string $tableName  Le nom de la table qui va recevoir les données.
     * @param string $dbLogin    Le login à utiliser pour se connecter à la base
     *                           de données.
     * @param string $dbPassword Le mot de passe à utiliser pour se connecter à
     *                           la base de données.
     */
    public function __construct(
        array $cols
        , string $database
        , string $tableName
        , string $dbLogin
        , string $dbPassword
        , array $whereClauses = null
        , string $orderBy = null
        , int $begining = null
        , int $itemNumber = null
    ) {
        $this->cols = $cols;
        $this->database = $database;
        $this->tableName = $tableName;
        $this->dbLogin = $dbLogin;
        $this->dbPassword = $dbPassword;
        $this->whereClauses = $whereClauses;
        $this->orderBy = $orderBy;
        $this->begining = $begining;
        $this->itemNumber = $itemNumber;
    }

    /**
     * Permet d'exécuter la requête.
     */
    public function run()
    {
        parent::connectToDb($this->dbLogin, $this->dbPassword);
        $this->formatQuery();
        $req = $this->pdo->prepare($this->query);
        
        // Si tout s'est bien passé, retourner true
        if ($req->execute($this->whereClauses)) {
            $this->data = $req->fetchAll();
        } else {
            // Sinon, lancer une exception
            throw new Exception("Action Get From Database failed.");
        }
    }

    /**
     * Retourne les résultats de la requête.
     */
    public function getResponse()
    {
        return $this->data;
    }

    /**
     * Permet de formater la requête SQL pour insérer les données dans la base de données.
     */
    public function formatQuery()
    {
        // Formatage des composantes de la requête
        $arrayKeys = array_keys($this->cols);
        $cols = "";

        foreach($arrayKeys as $key) {
            $cols .= "$key, ";
        }

        // Rétirer les dernières virgules et espaces à la fin de la chaine de caractère
        $cols = rtrim($cols, ", ");

        // Formatage de la clause where
        $this->formatClauses();

        $sqlFormater = new SqlQueryFormater();
        $this->query = $sqlFormater->select($cols)
            ->from($this->tableName)
            ->where($this->clauseAsString)
            ->orderBy($this->orderBy)
            ->limit($this->itemNumber, $this->begining)
            ->returnQueryString();
    }

}