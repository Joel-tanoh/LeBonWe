<?php

namespace App\Controller;

use App\View\Page\Page;
use App\View\Model\User\AdministratorView;

class AdministrationController extends  AppController 
{
    public static function index()
    {
        $page = new Page(
            APP_NAME . " - Administration",
            AdministratorView::index(),
            "Administration"
        );

        $page->show();
    }
}