<?php

/**
 *  Fichier de routage de l'application.
 */

session_start();

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "config.php";

use App\Route\Router;
use App\View\Page\Page;
use App\View\View;

try {
    
    // Visitor::manage();

    $router = new Router(trim($_SERVER["REQUEST_URI"], "/"));

    $router->get("/", "App\Controller\IndexController@index");
    $router->get("/admin", "App\Controller\AdministrationController@index");
    $router->get("/administration", "App\Controller\AdministrationController@index");

    $router->run();
  
} catch(Exception $e) {
    $page = new Page(APP_NAME, View::exception($e), "Exception capturée");
    $page->show();
}