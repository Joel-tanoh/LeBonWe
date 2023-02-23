<?php

/**
 * Fichier de configuration général.
 */

/** Nom de l'application */
define("APP_NAME", "lebonwe");

/** Nom de la base de données */
define("DB_NAME", "lebonwe");

/** Adresse du serveur */
define("DB_ADDRESS", "localhost");

/** Login de la base de données */
define("DB_LOGIN", "root");

/** Mot de passe de connexion à la base de données */
define("DB_PASSWORD", "");

// Url de l'application
define("APP_URL", $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"]);

// Chemin du dossier racine de l'application
define("ROOT_PATH", dirname(__DIR__) . DIRECTORY_SEPARATOR);

/*** Chemin absolu du dossier d'accès au site web : public ***/
define("PUBLIC_PATH", ROOT_PATH . "public" . DIRECTORY_SEPARATOR);

/** Chemin absolu du dossier assets */
define("ASSETS_DIR_PATH", PUBLIC_PATH . "assets" . DIRECTORY_SEPARATOR);

/*** URL du dossiers des assets ***/
define("ASSETS_DIR_URL", APP_URL . "/assets");

// Appel de l'autoloader
require_once ROOT_PATH . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

// Appel du fichier contenant les fonctions générales
require_once ROOT_PATH . "src" . DIRECTORY_SEPARATOR . "functions.php";