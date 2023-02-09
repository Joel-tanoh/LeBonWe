<?php

namespace App\Communication\SocialNetwork;

/**
 * Facebook.
 */
class Facebook
{
    /**
     * Affiche le bouton partager.
     * 
     * @param string $href Le lien à partager. 
     * @return string
     */
    public function button(string $href)
    {
        $href = str_replace("/", "%2F", str_replace(":", "%3A", $href));
        
        return <<<HTML
        <iframe src="https://www.facebook.com/plugins/share_button.php?href={$href}&layout=button&size=small&width=83&height=20&appId" width="83" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
HTML;
    }
}