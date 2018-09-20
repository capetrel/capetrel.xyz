<?php
namespace App\Framework\Middleware;

use Framework\Router\Route;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteDispatcherMiddleware implements MiddlewareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
        $route = $request->getAttribute(Route::class);

        if (is_null($route)) {
            return $delegate->handle($request);
        }

        $callback = $route->getCallBack();
        if (!is_array($callback)) {
            $callback = [$callback];
        }
        return (new CombinedMiddleware($this->container, $callback))->process($request, $delegate);
    }
}
