<?php
namespace App\Auth\Event;

use App\Auth\User;
use App\Event\Event;

class LoginEvent extends Event
{
    public $name = 'auth.login';

    public function __construct(User $user)
    {
        $this->setTarget($user);
    }

    public function getTarget()
    {
        return parent::getTarget();
    }
}
