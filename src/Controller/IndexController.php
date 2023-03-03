<?php

namespace App\Controller;

use App\View\Page\Page;
use App\View\View;

abstract class IndexController extends AppController
{
    /**
     * Index du site.
     */
    public static function index()
    {
        $page = new Page(
            "Bienvenu sur " . APP_NAME,
            View::index(),
            APP_NAME . " est votre framework Web"
        );
        $page->show();
    }
}