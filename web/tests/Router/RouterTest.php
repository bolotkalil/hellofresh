<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 10:43 AM
 */

namespace Hellofresh\Router;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Hellofresh\App;
use Hellofresh\Service\Hateoas\Config;
use Hellofresh\Service\Hateoas\Helper;
use Hellofresh\Service\Hateoas\Service;
use League\Container\Container;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class RouterTest extends TestCase
{
    use Helper;

    private $routeList;
    private $strategy;
    private $container;
    private $service;
    private $config;

    public static function setUpBeforeClass()
    {
        AnnotationRegistry::registerLoader('class_exists');
    }

    public function setUp()
    {
        parent::setUp();
        $this->routeList = new Router();
        $this->container = new Container();
        $this->strategy = new Strategy($this->container);
        $this->container->add(App::VENDOR_ID, 'hellofresh-test');
        $this->container->add(App::API_VERSION_ID, '2.1');
        $this->service   = new Service();
        $this->config    = new Config();
        $this->service->register($this->container, $this->config);
    }

    public function testGetRouteList()
    {
        $this->routeList->map('GET', '/recipes', function(){});
        $this->routeList->map('GET', '/recipes/1', function(){});
        $this->routeList->map('DELETE', '/recipes/1', function(){});

        $this->assertCount(3, $this->routeList->getRouteList());
        $this->assertEquals('/recipes', $this->routeList->getRouteList()[0]['path']);
        $this->assertEquals('/recipes/1', $this->routeList->getRouteList()[1]['path']);
        $this->assertEquals('/recipes/1', $this->routeList->getRouteList()[2]['path']);
    }

    public function testMap()
    {
        $request  = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $uri      = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->exactly(2))
            ->method('getPath')
            ->will($this->returnValue('/example/route'))
        ;
        $request
            ->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue('GET'))
        ;
        $request
            ->expects($this->exactly(2))
            ->method('getUri')
            ->will($this->returnValue($uri))
        ;
        $router = new Router;
        $router->map('GET', '/example/{something}', function (
            ServerRequestInterface $request,
            array $args
        ) use (
            $response
        ) : ResponseInterface {
            $this->assertSame([
                'something' => 'route'
            ], $args);
            return $response;
        });
        $router->setStrategy($this->strategy);
        $returnedResponse = $router->dispatch($request);
        $this->assertSame($response, $returnedResponse);
    }


    public function testDispatchWithClassAndMethodAndResponseObject()
    {
        $router = new Router;
        $request  = $this->createMock(ServerRequestInterface::class);
        $uri      = $this->createMock(UriInterface::class);
        $uri
            ->method('getPath')
            ->will($this->returnValue('/'))
        ;
        $request
            ->method('getMethod')
            ->will($this->returnValue('GET'))
        ;
        $request
            ->method('getUri')
            ->will($this->returnValue($uri))
        ;
        $request->method('getHeader')
                ->will($this->returnValue([0=>'application/json']));
        $router->get('/', 'Hellofresh\Stub\Controller\Simple::getProcess');
        $router->setStrategy($this->strategy);

        $result = $router->dispatch($request);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        if ($result instanceof ResponseInterface) {
            $result->getBody()->rewind();
            $this->assertEquals('"Hello World from HelloFresh"', $result->getBody()->getContents());
        }
    }

    /**
     * Returns container.
     *
     * @return \League\Container\Container
     */
    protected function getContainer()
    {
        return $this->container;
    }
}
