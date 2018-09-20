<?php
namespace App\Framework\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CombinedMiddleware implements MiddlewareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $middlewares;

    public function __construct(ContainerInterface $container, array $middlewares)
    {

        $this->container = $container;
        $this->middlewares = $middlewares;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $delegate
     * @return ResponseInterface
     * @throws \Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $delegate): ResponseInterface
    {
        $delegate = new CombinedMiddlewareDelegate($this->container, $this->middlewares, $delegate);
        return $delegate->handle($request);
    }
}
