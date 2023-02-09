<?php

namespace App\Model\Post;

use App\Model\Model;

/**
 * Gestionnaire des posts.
 */
class Post extends Model
{
    protected $category;
    protected $subCategory;
    protected $owner;
    protected $postedAt;

    /**
     * Retourne l'utilisateur à qui appartient le post.
     * 
     * @return \App\Model\User\Registered
     */
    public function getOwner() : \App\Model\User\Registered
    {
        return $this->owner;
    }

}