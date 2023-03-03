<?php

namespace App\View\Model;

use App\View\Model\CategoryView;

/**
 * Classe de gestion des vues des sous-catégories.
 */
class SubCategory extends CategoryView
{
    protected $subCategory;

    public function __construct(\App\Model\SubCategory $subCategory)
    {
        $this->subCategory = $subCategory;
    }
}