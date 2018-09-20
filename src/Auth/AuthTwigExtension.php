<?php
namespace App\Auth;

use App\Framework\AuthInterface;

class AuthTwigExtension extends \Twig_Extension
{

    /**
     * @var AuthInterface
     */
    private $auth;

    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('current_user', [$this->auth, 'getUser'])
        ];
    }
}
