<?php

namespace App\Action\Create;

use App\Action\Action;
use Exception;

/**
 * Classe de gestion des insertions de données.
 */
class InsertInDb extends Action
{
    /**
     * Constructeur de l'action insert.
     * 
     * @param array  $data       Le tableau contenant les données à inserer.
     * @param string $tableName  Le nom de la table qui va recevoir les données.
     * @param string $database   Le nom de la base de données dans laquelle
     *                           se trouve la table dans laquelle les données 
     *                           seront mises à jour.
     * @param string $dbLogin    Le login à utiliser pour se connecter à la base
     *                           de données.
     * @param string $dbPassword Le mot de passe à utiliser pour se connecter à
     *                           la base de données.
     */
    public function __construct(
        array $data, 
        string $tableName,
        string $database = DB_NAME, 
        string $dbLogin = DB_LOGIN, 
        string $dbPassword = DB_PASSWORD
    ) {
        $this->data = $data;
        $this->database = $database;
        $this->tableName = $tableName;
        $this->dbLogin = $dbLogin;
        $this->dbPassword = $dbPassword;
    }

    /**
     * Permet d'insérer les données.
     * 
     * @param string $dbLogin     A spécifier si à l'instanciation d'un objet
     *                            Insert, vous passez le nom d'une base de données
     *                            différente de celle utilisée par défaut par l'application.
     * @param string $dbPassword. Pareil au paramètre dbLogin.
     * 
     * @return bool
     */
    public function run()
    {
        parent::connectToDb($this->dbLogin, $this->dbPassword);
        $this->formatQuery();
        $req = $this->pdo->prepare($this->query);
        
        if ($req->execute($this->data)) {
            return true; // Si tout s'est bien passé, retourner true
        } else { 
            throw new Exception("Action Insert in database failed."); // Sinon, lancer une exception
        }
    }

    /**
     * Permet de formater la requête SQL pour insérer les données dans la base de données.
     */
    public function formatQuery()
    {
        // Formatage des composantes de la requête
        $arrayKeys = array_keys($this->data);
        $cols = null;
        $values = null;

        foreach($arrayKeys as $key) {
            $cols .= "$key, ";
            $values .= ":$key, ";
        }

        // Rétirer les dernières virgules et espaces à la fin de la chaine de caractère
        $cols = rtrim($cols, ", ");
        $values = rtrim($values, ", ");

        // Formatage et envoi de la requête
        $this->query = "INSERT INTO $this->tableName($cols) VALUES($values)";
    }

}