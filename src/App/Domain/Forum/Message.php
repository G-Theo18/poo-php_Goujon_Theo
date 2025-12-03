<?php

declare(strict_types=1);

namespace App\Domain\Forum;

use App\Domain\User\User;

class Message
{
    public $user;
    public $msg;
    
    public function __construct($user, $msg)
    {
        $this->user = $user;
        $this->msg = $msg;
    }
}
