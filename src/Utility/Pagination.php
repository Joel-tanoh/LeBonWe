<?php

namespace App\Utility;

class Pagination extends Utility
{
    private $firstIndex;
    private $lastIndex;
    private $itemPerPage;
    private $itemTotalNbr;

    /**
     * Constructeur d'une pagination.
     */
    public function __construct(int $itemTotalNbr, int $itemPerPage)
    {
        $this->itemTotalNbr = $itemTotalNbr;
        $this->itemPerPage = $itemPerPage;
    }

    /**
     * Barre de pagination.
     * 
     * @return string
     */
    public function show()
    {
        return <<<HTML
        <div class="pagination-bar">
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item"><a class="page-link active" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
HTML;
    }

    /**
     * Affiche un bouton dans la suite des boutons de la pagination.
     * 
     * @return string
     */
    private function button()
    {
        return <<<HTML
        <li class="page-item"><a class="page-link" href="#">1</a></li>
HTML;
    }

    /**
     * Affiche le bouton suivant.
     * 
     * @return string
     */
    public function next()
    {

    }

    /**
     * Affiche le bouton précédent.
     * 
     * @return string
     */
    public function previous()
    {

    }

    /**
     * Affiche le premier bouton.
     * 
     * @return string
     */
    public function first()
    {

    }

    /**
     * Affiche le dernier bouton.
     * 
     * @return string
     */
    public function last()
    {

    }
}