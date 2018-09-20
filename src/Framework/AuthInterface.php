<?php
namespace App\Framework;

use App\Framework\Auth\UserInterface;

interface AuthInterface
{
    /**
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface;
}
