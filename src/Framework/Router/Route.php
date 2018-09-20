<?php
namespace Framework\Router;

/**
 * Class Route
 * Représente une route associée
 * @package Framework\Router
 */
class Route
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|callable
     */
    private $callback;

    /**
     * @var array
     */
    private $parameters;

    /**
     * Route constructor.
     * @param string $name
     * @param string|callable $callback
     * @param array $parameters
     */
    public function __construct(string $name, $callback, array $parameters)
    {
        $this->name = $name;
        $this->callback = $callback;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|callable
     */
    public function getCallBack()
    {
        return $this->callback;
    }

    /**
     * Récupère les paramètre dans l'url
     * @return string[]
     */
    public function getParams(): array
    {
        return $this->parameters;
    }
}
