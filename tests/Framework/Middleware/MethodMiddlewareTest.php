<?php
namespace Framework\Middleware;

use App\Framework\Middleware\MethodMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;

class MethodMiddlewareTest extends TestCase
{

    private $middleware;

    protected function setUp()
    {
        $this->middleware = new MethodMiddleware();
    }

    public function testAddMethod()
    {
        $delegate = $this->getMockBuilder(RequestHandlerInterface::class)
            ->setMethods(['handle'])
            ->getMock();

        $delegate->expects($this->once())
            ->method('handle')
            ->with($this->callback(function ($request) {
                return $request->getMethod() === 'DELETE';
            }));

        $request = (new ServerRequest('post', '/demo'))
            ->withParsedBody(['_method' => 'DELETE']);
        $this->middleware->process($request, $delegate);
    }
}
