<?php

namespace App\Action\Update;

use App\Action\Action;

/**
 * Classe qui permet de gérer une action de modification.
 */
class Update extends Action
{
    /**
     * Constructeur.
     * 
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Permet de spécifier que l'item à modifier a aussi des images
     * qu'il faut modifier.
     * 
     * @param array  $lastImgNames Un tableau qui doit contenir les noms
     *                             des anciennes images.
     * @param array  $newImgNames  Un tableau qui doit contenir les noms des nouvelles
     *                             images dans le cas où il faut renommer les images.
     * @param string $toCheckIndex L'index de la valeur à checker pour vérifier que le
     *                             nom des images a changer. Si cette valeur n'est pas vide,
     *                             alors il faut renommer les images de cet item.
     * @param        $toCheckValue La valeur sur laquelle il faut faire la comparaison
     *                             pour vérifier que le nom des images doit changer.
     */
    public function changeImg(string $key, array $lastImgNames, array $newImgNames = null, string $toCheckIndex = null, $toCheckValue = null)
    {
        $valueChange = $this->data[$toCheckIndex] == $toCheckValue;

        // toCheckIndex n'a pas changé et pas de nouvelles images
            // rien à faire
        if (!$valueChange && !parent::fileIsUploaded($key)) {
            return;
        }

        // la valeur n'a changé mais nouvelles images
            // supprimer les anciennes
            // enregistrer les nouvelles images avec leurs noms
        if (!$valueChange && parent::fileIsUploaded($key)) {
            
        }

        // la valeur a changé mais pas de nouvelles images
            // renommer les images
        if ($valueChange && !parent::fileIsUploaded($key)) {
            
        }

        // la valeur a changé et nouvelles images
            // supprimer les anciennes images
            // enregistrer les nouvelles images avec leurs noms
        if ($valueChange && parent::fileIsUploaded($key)) {
            
        }
    }

    /**
     * Méthode qui exécute le code de la modification.
     */
    public function run()
    {

    }

}