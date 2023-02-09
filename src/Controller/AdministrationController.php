<?php

namespace App\Controller;

use App\View\Page\Page;
use App\View\AdministrationView;

class AdministrationController extends  AppController 
{
    public static function index()
    {
        $page = new Page(APP_NAME . " - Administration", AdministrationView::index());
        $page->show();
    }
}