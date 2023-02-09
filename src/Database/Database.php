<?php

namespace App\Database;

use PDO;
use PDOException;

/**
 * Gère la base de données.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <joel.developpeur@gmail.com>
 * @license  url.com license_name
 * @link     Link
 */
class Database
{
    private $sgbd;
    private $dbAddress; 
    private $dbName;
    private $dbCharset;
    private $dbLogin;
    private $dbPassword;
    private $pdo;
    private $paramsArray = [];

    /**
     * Permet d'instancier une base de données et de s'y connecter immédiatement.
     * 
     * @param string $dbName     Le nom de la base de données.
     * @param string $dbLogin    Le login pour se connecter à la base de données.
     * @param string $dbPassword Le mot de passe pour se connecter à la base de données.
     * @param string $dbAddress  L'adresse ip du serveur.
     * @param string $sgbd       Le système de gestion de la base de données.
     * @param string $dbCharset  L'encodage des caractères.
     * 
     */
    public function __construct(string $dbName = DB_NAME, string $dbLogin = DB_LOGIN , string $dbPassword = DB_PASSWORD , string $dbAddress = "localhost", string $sgbd = "mysql", string $dbCharset = "utf8") {
        $this->dbName       = $dbName;
        $this->dbLogin      = $dbLogin;
        $this->dbPassword   = $dbPassword;
        $this->dbAddress    = $dbAddress;
        $this->sgbd         = $sgbd;
        $this->dbCharset    = $dbCharset;
        $this->pdo          = $this->connect();
    }

    /**
     * Méthode de connexion à la base de données. Retourne l'instance de connexion.
     * 
     * @return PDOInstance
     */
    public function connect() {
        try {
            return new PDO(
                $this->sgbd.':host='.$this->dbAddress.';dbname='.$this->dbName.'; charset='.$this->dbCharset, $this->dbLogin, $this->dbPassword,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT => true // Connexion persistante pour que Windows suppoprte plus de 16 connexions
                ]
            );

        } catch (PDOException $e) {
            self::connectionErrorHandler($e);
        }
    }

    /**
     * Retourne l'instance PDO.
     * 
     * @return PDOInstance
     */
    public function getPDO()
    {
        return $this->pdo;
    }

    /**
     * Cette méthode est exécutée lorsqu'une erreur de connexion à la base de donnée survient.
     * 
     * @param \Exception $e
     */
    private static function connectionErrorHandler($e)
    {
        echo "<h1>Erreur lors de la connexion à la base de données</h1>";
        echo $e->getMessage();
    }
 
}