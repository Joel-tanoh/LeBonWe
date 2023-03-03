<?php

namespace App\View\Component;

/**
 * Vue gestionnaires des recherches
 **/
class SearchView extends Component
{
    /**
     * Retourne la vue pour afficher les résultats des recherches d'annonces.
     * 
     * @param string $modelType Le type des données à afficher (announces, users, etc.)
     * @param array $data Le tableau des éléments à afficher.
     * 
     * @return string
     */
    public function result(string $modelType, array $data)
    {
        if (empty($data)) {
            return Component::noResultFound();
        }
    }

    /**
     * Retourne le formulaire de recherche qui s'affiche sur la page 404.
     * 
     * @return string
     */
    public function notFoundSearch()
    {
        
    }

}