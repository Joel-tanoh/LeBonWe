<?php

namespace App\Action\Update;

use Exception;

/**
 * Permet de modifier une valeur dans la base de données.
 */
class UpdateDb extends Update
{
    /** @var string Format texte des clauses à
     * insérer dans la requête de mise à jour.
     */
    private $clauseString;

    /** @var array Tableau associatif contenant la liste des paramètres
     * à passer dans la la méthode execute de la requête.
     */
    private $params;

    /**
     * Constructeur d'une mise à jour de la base de données.
     * 
     * @param array $data        Le tableau associatif contenant les valeurs
     *                           à insérer pour la mise à jour. Les clés du tableau
     *                           doivent respectivement être les clés dans la base
     *                           de données.
     * @param string $database   Le nom de la base de données.
     * @param string $tableName  Le nom de la table à mettre à jour.
     * @param string $dbLogin    Le login à utiliser pour se connecter à la base de
     *                           données.
     * @param string $dbPassword Le mot de passe à utiliser pour se connecter à la
     *                           base de données.
     * @param array $clauses     Un tableau associatif contenant les clauses.
     */
    public function __construct(
        array $data
        , string $database
        , string $tableName
        , string $dbLogin
        , string $dbPassword
        , array $clauses = null
    ) {
        $this->data = $data;
        $this->database = $database;
        $this->tableName = $tableName;
        $this->dbLogin = $dbLogin;
        $this->dbPassword = $dbPassword;
        $this->clauses = $clauses;
    }

    /**
     * Permet d'exécuter la requête finale.
     */
    public function run()
    {
        $this->formatQuery();
        $req = parent::connectToDb($this->dbLogin, $this->dbPassword)->prepare($this->query);
        $this->formatParams();

        // Si tout s'est bien passé, retourner true
        if ($req->execute($this->params)) {
           return true;
        } else {
            // Sinon, lancer une exception
            throw new Exception("Action Update Database failed.");
        }
    }

    /**
     * Constructeur de la requête sql de modification.
     */
    public function formatQuery()
    {
        // Formatage des composantes de la requête
        $arrayKeys = array_keys($this->data);
        $colAndValue = null;

        foreach($arrayKeys as $key) {
            $colAndValue .= "$key = :$key, ";
        }

        // Rétirer les dernières virgules et espaces à la fin de la chaine de caractère
        $colAndValue = rtrim($colAndValue, ", ");

        $this->query = "UPDATE $this->tableName SET $colAndValue $this->clauseString";
        $this->formatClauses();
    }

    /**
     * Permet de formater les paramètres à passer dans la requête de mise
     * à jour.
     */
    private function formatParams()
    {
        $this->params = array_merge($this->data, $this->clauses);
    }
}