<?php

namespace App\View\Effect\Animation\Slider;

/**
 * Slider
 */
class Slider
{
    /** @var array Le tableau contenant les slides (images). */
    private $images = [];

    /** @var string Le result final */
    private $result;

    /**
     * Constructeur d'un slider.
     */
    public function __construct()
    {
        $this->images = Slide::slides();
    }

    /**
     * Affiche le slider.
     */
    public function show()
    {
        $this->result();
        return <<<HTML
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                {$this->result}
            </div>
            {$this->previousButton()}
            {$this->nextButton()}
        </div>
HTML;
    }

    /**
     * Retourne le bouton pour afficher la slide précédente (previous).
     */
    private function previousButton()
    {
        return <<<HTML
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Précedent</span>
        </a>
HTML;
    }

    /**
     * Retourne le bouton pour afficher la slide suivante (next).
     */
    private function nextButton()
    {
        return <<<HTML
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Suivant</span>
        </a>
HTML;
    }

    /**
     * Parcoure le tableau des slide passé à la classe et instancie une slide à chaque
     * itération.
     */
    private function result()
    {
        for ($i = 0; $i < count($this->images); $i++) {
            $actived = null;
            if ($i === 0) {
                $actived = "active";
            }
            $this->result .= (new Slide($this->images[$i], "slider ".$i, null, $actived))->get();
        }
    }
}