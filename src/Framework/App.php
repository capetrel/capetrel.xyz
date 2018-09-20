<?php
namespace Framework;

use App\Framework\Middleware\CombinedMiddleware;
use App\Framework\Middleware\RoutePrefixedMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class App implements RequestHandlerInterface
{

    /**
     * Liste des modules
     * @var array
     */
    private $modules = [];

    /**
     * @var array
     */
    private $definitions;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string[]
     */
    private $middlewares = [];

    /**
     * @var int
     */
    private $index = 0;

    /**
     * App constructor.
     * @param array $definitions
     */
    public function __construct($definitions = [])
    {
        if (is_string($definitions)) {
            $definitions = [$definitions];
        }
        if (!$this->isSequential($definitions)) {
            $definitions = [$definitions];
        }
        $this->definitions = $definitions;
    }

    /**
     * Rajoute un module (une fonctionnalité) à l'application
     * @param string $module
     * @return App
     */
    public function addModule(string $module): self
    {
        $this->modules[] = $module;
        return $this;
    }

    /**
     * Ajoute un middleware (un comportement) à l'application
     * @param string|callable|MiddlewareInterface $routePrefix
     * @param string|callable|MiddlewareInterface $middleware
     * @return App
     * @throws \Exception
     */
    public function pipe($routePrefix, $middleware = null): self
    {
        if ($middleware === null) {
            $this->middlewares[] = $routePrefix;
        } else {
            $this->middlewares[] = new RoutePrefixedMiddleware($this->getContainer(), $routePrefix, $middleware);
        }

        return $this;
    }

    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->index++;
        if ($this->index > 1) {
            throw new \Exception('Oups, problème de middleware');
        }
        $middleware = new CombinedMiddleware($this->getContainer(), $this->middlewares);
        return $middleware->process($request, $this);
    }

    public function run(ServerRequestInterface $request): ResponseInterface
    {
        foreach ($this->modules as $module) {
            $this->getContainer()->get($module);
        }
        return $this->handle($request);
    }

    /**
     * @return ContainerInterface
     * @throws \Exception
     */
    public function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            $builder = new \DI\ContainerBuilder();
            $env = getenv('ENV') ?: 'production';
            if ($env === 'production') {
                //TODO corriger header already sent et le passage du tableau par référence dans csrfMiddleware
                //$builder->enableCompilation('tmp/di/cache');
                $builder->enableDefinitionCache();
                $builder->writeProxiesToFile(true, 'tmp/di/proxies');
            }
            foreach ($this->definitions as $definition) {
                $builder->addDefinitions($definition);
            }
            foreach ($this->modules as $module) {
                if ($module::DEFINITIONS) {
                    $builder->addDefinitions($module::DEFINITIONS);
                }
            }
            $builder->addDefinitions([App::class => $this]);
            $this->container = $builder->build();
        }
        return $this->container;
    }

    /**
     * @return array
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    private function isSequential(array $array): bool
    {
        if (empty($array)) {
            return true;
        }
        return array_keys($array) === range(0, count($array) - 1);
    }
}
