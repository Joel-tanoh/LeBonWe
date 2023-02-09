<?php

namespace App\View\Effect\Animation\Slider;

use App\File\Image\Image;

/**
 * Une slide
 */
class Slide
{
    /** @var string La source de la slide (de l'image) */
    private $src;

    /** @var string Le texte alt qui est affiché lorsqu'on ne voit pas la slide (l'image) */
    private $altText;

    /** @var string Le texte qui s'affiche sur l'image */
    private $caption;

    /** Le dossier ou se trouve les slides */
    const SLIDES_DIR_PATH = Image::IMG_DIR_PATH . "slides" . DIRECTORY_SEPARATOR;

    /**
     * Constructeur d'une slide.
     */
    public function __construct(string $src, string $altText = null, string $caption = null, string $actived = null)
    {
        $this->src = $src;
        $this->altText = $altText;
        $this->caption = $caption;
        $this->actived = $actived;
    }

    /**
     * Retourne la slide.
     */
    public function get()
    {
        return <<<HTML
        <div class="carousel-item {$this->actived}">
            <img class="d-block w-100" src="{$this->src}" alt="{$this->altText}">
        </div>
HTML;
    }

    /**
     * Retourne les images du slide.
     * 
     * @return array
     */
    public static function slides()
    {
        return Image::gets("*", Slide::SLIDES_DIR_PATH);
    }

    private function caption()
    {
        return <<<HTML
        <div class="carousel-caption d-none d-md-block">
            <h1 class="head-title">Bienvenue sur <span class="year">L'indice</span></h1>
            <p>Achetez et vendez de tout, des voitures d'occasion aux téléphones mobiles et aux ordinateurs, <br> ou recherchez une propriété, des emplois et plus encore</p>
        </div>
HTML;
    }

}