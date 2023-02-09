<?php

namespace App\View;

class AdministrationView extends View
{
    public static function index()
    {
        return <<<HTML
        Index de l'administration
HTML;
    }
}