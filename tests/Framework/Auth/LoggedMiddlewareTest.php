<?php
namespace Tests\Framework\Auth;

use App\Auth\User;
use App\Framework\Auth\ForbiddenException;
use App\Framework\Auth\LoggedMiddleware;
use App\Framework\AuthInterface;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoggedMiddlewareTest extends TestCase
{

    public function makeMiddleware($user)
    {
        $auth = $this->getMockBuilder(AuthInterface::class)->getMock();
        $auth->method('getUser')->willReturn($user);
        return new LoggedMiddleware($auth);
    }

    public function makeDelegate($calls)
    {
        $delegate = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $delegate->expects($calls)->method('handle')->willReturn($response);
        return $delegate;
    }

    public function testThrowIfNoUser()
    {
        $request = (new ServerRequest('GET', '/demo/'));
        $this->expectException(ForbiddenException::class);
        $this->makeMiddleware(null)->process(
            $request,
            $this->makeDelegate($this->never())
        );
    }

    public function testNextIfUser()
    {
        $user = $this->getMockBuilder(User::class)->getMock(); // UserInterface
        $request = (new ServerRequest('GET', '/demo/'));
        $this->makeMiddleware($user)->process(
            $request,
            $this->makeDelegate($this->once())
        );
    }
}
