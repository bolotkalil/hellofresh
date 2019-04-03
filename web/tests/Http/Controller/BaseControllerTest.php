<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 6:27 PM
 */

namespace Hellofresh\Http\Controller;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Hellofresh\Router\Router;
use League\Container\Container;
use Hellofresh\App;
use PHPUnit\Framework\TestCase;
use Hellofresh\Stub\Controller\Index;

class BaseControllerTest extends TestCase
{

    /**
     * @var Index
     */
    private $controller;

    /**
     * @var Router
     */
    private $router;

    public static function setUpBeforeClass()
    {
        AnnotationRegistry::registerLoader('class_exists');
    }

    public function setUp()
    {
        $this->router = new Router();

        $container = new Container();
        $container->add(App::ROUTER_ID, $this->router);

        $this->controller = new Index($container);
    }

    public function testRoutingList()
    {
        $routeList = $this->router->getRouteList();

        $this->assertCount(2, $routeList);

        $this->assertEquals('GET', $routeList[0]['method']);
        $this->assertEquals('\Hellofresh\Stub\Controller\Index::getFoo', $routeList[0]['handler']);
        $this->assertEquals('/{version:(?:1\.[2-9])|(?:2\.[0-8])}/foos/{id}', $routeList[0]['path']);

        $this->assertEquals('POST', $routeList[1]['method']);
        $this->assertEquals('\Hellofresh\Stub\Controller\Index::postBar', $routeList[1]['handler']);
        $this->assertEquals('/{version:(?:0\.[5-7])}/bars', $routeList[1]['path']);
    }
}