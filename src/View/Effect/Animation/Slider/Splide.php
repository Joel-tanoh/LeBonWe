<?php

namespace App\View\Effect\Animation\Slider;

/**
 * Slider Splide
 */
class Splide extends Slider
{
    /**
     * CDN JS de Splide.
     * @return string
     */
    public static function jsCDN()
    {
        return "https://cdn.jsdelivr.net/npm/@splidejs/splide@2.4.20/dist/js/splide.min.js";
    }

    /**
     * CDN CSS de Splide.
     * @return string
     */
    public static function cssCDN()
    {
        return "https://cdn.jsdelivr.net/npm/@splidejs/splide@2.4.20/dist/css/splide.min.css";
    }

    public function show()
    {
        return <<<HTML
        <div class="splide">
            <div class="splide__track">
                <ul class="splide__list">
                    {$this->core()}
                </ul>
            </div>
            {$this->progessBar()}
        </div>
HTML;
    }

    private function core()
    {
        return <<<HTML
        <li class="splide__slide">Slide 01</li>
        <li class="splide__slide">Slide 02</li>
        <li class="splide__slide">Slide 03</li>
HTML;
    }

    private function progessBar()
    {
        return <<<HTML
        <div class="splide__progress">
            <div class="splide__progress__bar">
            </div>
        </div>
HTML;
    }

}