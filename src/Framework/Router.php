<?php
namespace Framework;

use Framework\Router\MiddlewareApp;
use Framework\Router\Route;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\Route as ZendRoute;

/**
 * Class Router
 * Enregistre et asscie les routes
 * @package Framework
 */
class Router
{

    /**
     * @var FastRouteRouter
     */
    private $router;

    public function __construct(?string $cache = null)
    {
            $this->router = new FastRouteRouter(null, null, [
                FastRouteRouter::CONFIG_CACHE_ENABLED => !is_null($cache),
                FastRouteRouter::CONFIG_CACHE_FILE => $cache
            ]);
    }

    /**
     * @param string $path
     * @param string|callable $callable
     * @param string $name
     */
    public function get(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new ZendRoute($path, new MiddlewareApp($callable), ['GET'], $name));
    }

    /**
     * @param string $path
     * @param string|callable $callable
     * @param string $name
     */
    public function post(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new ZendRoute($path, new MiddlewareApp($callable), ['POST'], $name));
    }

    /**
     * @param string $path
     * @param string|callable $callable
     * @param string $name
     */
    public function delete(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new ZendRoute($path, new MiddlewareApp($callable), ['DELETE'], $name));
    }

    /**
     * @param string $path
     * @param $callable
     * @param null|string $name
     */
    public function any(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(
            new ZendRoute(
                $path,
                new MiddlewareApp($callable),
                ['DELETE', 'POST', 'GET', 'PUT'],
                $name
            )
        );
    }

    /**
     * Génère les routes du CRUD
     * @param string $prefixPath
     * @param callable $callable
     * @param null|string $prefixName
     */
    public function crud(string $prefixPath, $callable, ?string $prefixName)
    {
        $this->get("$prefixPath", $callable, "$prefixName.index");
        $this->get("$prefixPath/new", $callable, "$prefixName.create");
        $this->post("$prefixPath/new", $callable);
        $this->get("$prefixPath/{id:\d+}", $callable, "$prefixName.edit");
        $this->post("$prefixPath/{id:\d+}", $callable);
        $this->delete("$prefixPath/{id:\d+}", $callable, "$prefixName.delete");
    }

    /**
     * @param ServerRequestInterface $request
     * @return Route|null
     */
    public function match(ServerRequestInterface $request): ?Route
    {

        $result = $this->router->match($request);
        if ($result->isSuccess()) {
            return new Route(
                $result->getMatchedRouteName(),
                $result->getMatchedRoute()->getMiddleware()->getCallBack(),
                $result->getMatchedParams()
            );
        }
        return null;
    }

    public function generateUri(string $name, array $params = [], array $queryParams = []): ?string
    {
        $uri = $this->router->generateUri($name, $params);
        if (!empty($queryParams)) {
            return $uri . '?' . http_build_query($queryParams);
        }
        return $uri;
    }
}