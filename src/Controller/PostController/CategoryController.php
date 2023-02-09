<?php

namespace App\Controller\PostController;

use App\Controller\AppController;
use App\Exception\PageNotFoundException;
use App\Model\Category;
use App\View\Model\CategoryView;
use App\View\Page\Page;

/**
 * Controller des catégories.
 */
abstract class CategoryController extends AppController
{
    /**
     * Controlleur de création d'une catégorie.
     */
    static function create()
    {
        $page = new Page("Créer une annonce &#149; L'indice", CategoryView::create());
        $page->setDescription("");
        $page->show();
    }

    /**
     * Controller pour le read d'une catégorie.
     */
    static function read(array $params = null)
    {
        if (Category::isCategorySlug($params["category"])) {
            $category = Category::getBySlug($params["category"], Category::TABLE_NAME, "App\Model\Category");
            $page = new Page($category->getTitle() . " &#149; L'indice", (new CategoryView($category))->read());
            $page->setDescription($category->getDescription());
            $page->show();
        } else {
            throw new PageNotFoundException("La catégorie que vous cherchez n'a pas été trouvée.");
        }
    }

    /**
     * Controller de mise à jour d'une catégorie.
     */
    public function update()
    {

    }

    /**
     * Controlleur de suppression
     */
    public function delete()
    {

    }

}