<?php
namespace App\Framework\Auth;

use App\Framework\AuthInterface;

class RoleMiddlewareFactory
{
    /**
     * @var AuthInterface
     */
    private $auth;

    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    public function makeForRole($role): RoleMiddleware
    {
        return new RoleMiddleware($this->auth, $role);
    }
}
