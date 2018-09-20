<?php

namespace Framework\Router;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class MiddlewareApp
 * Classe qui permet d'utiliser la dernière version de fast router de zend expressive
 * @package Framework\Router
 */
class MiddlewareApp implements MiddlewareInterface
{
    /**
     * @var string|callable
     */
    private $callback;

    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface|null $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler = null): ResponseInterface
    {
        return $this->process($request, $handler);
    }

    /**
     * @return string|callable
     */
    public function getCallback()//: callable
    {
        return $this->callback;
    }
}
