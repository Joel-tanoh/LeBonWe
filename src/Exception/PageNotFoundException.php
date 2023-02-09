<?php

namespace App\Exception;

use Exception;

class PageNotFoundException extends Exception
{
    /**
     * @param string $messsage
     */
    public function __construct(string $notification)
    {
        parent::__construct($notification);
    }
}