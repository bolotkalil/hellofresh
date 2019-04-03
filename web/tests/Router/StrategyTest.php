<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 12:17 PM
 */

namespace Hellofresh\Router;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use League\Container\Container;
use Hellofresh\Service;
use League\Route\Route;

class StrategyTest extends TestCase
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var Strategy
     */
    private $strategy;

    public function setUp()
    {
        $this->container = new Container();
        $this->strategy = new Strategy($this->container);
    }

    public function testInvokeRouteCallable()
    {
        $route = $this->createMock(Route::class);
        $expectedResponse = $this->createMock(ResponseInterface::class);
        $expectedRequest  = $this->createMock(ServerRequestInterface::class);
        $expectedVars     = ['something', 'else'];
        $route
            ->expects($this->once())
            ->method('getCallable')
            ->will($this->returnValue(
                function (
                    ServerRequestInterface $request,
                    array                  $vars = []
                ) use (
                    $expectedRequest,
                    $expectedResponse,
                    $expectedVars
                ) : ResponseInterface {
                    $this->assertSame($expectedRequest, $request);
                    $this->assertSame($expectedVars, $vars);
                    return $expectedResponse;
                }
            ))
        ;
        $route
            ->expects($this->once())
            ->method('getVars')
            ->will($this->returnValue($expectedVars))
        ;
        $response = $this->strategy->invokeRouteCallable($route, $expectedRequest);
        $this->assertSame($expectedResponse, $response);
    }
}