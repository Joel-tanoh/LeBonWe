<?php

/**
 * Fichier contenant toutes les fonctions globales du système.
 * 
 * PHP version 7.1.9
 * 
 * @category Category
 * @package  Package
 * @author   Joel <tanohbassapatrick@gmail.com>
 * @license  url.com license_name
 * @version  GIT: Joel_tanoh
 * @link     Link
 */

/**
 * Permet de dumper une variable.
 * 
 * @param $var 
 * 
 * @return string
 */
function dump(...$var)
{
    echo '<pre class="dumper">';
    var_dump($var);
    echo '</pre>';
}