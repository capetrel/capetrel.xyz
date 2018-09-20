<?php
namespace App\Framework\Auth;

use App\Framework\AuthInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoggedMiddleware implements MiddlewareInterface
{

    /**
     * @var AuthInterface
     */
    private $auth;

    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $delegate): ResponseInterface
    {
        $user = $this->auth->getUser();
        if (is_null($user)) {
            throw new ForbiddenException('Action interdite');
        }
        return $delegate->handle($request->withAttribute('user', $user));
    }
}
