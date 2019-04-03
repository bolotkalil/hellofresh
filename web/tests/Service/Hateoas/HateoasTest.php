<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 1:47 PM
 */

namespace Hellofresh\Service\Hateoas;

use Hellofresh\Stub\Service\SimpleConfig;
use League\Container\Container;
use PHPUnit\Framework\TestCase;

class HateoasTest extends TestCase
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var Config
     */
    private $hateoasConfig;

    public function setUp()
    {
        $this->container     = new Container();
        $this->hateoasConfig = new Config(true);
    }

    public function testInstance()
    {
        $service = new Service();
        $service->register($this->container, $this->hateoasConfig);

        $hateoasService = $this->container->get(Config::getServiceName());

        $this->assertInstanceOf('\Hateoas\Hateoas', $hateoasService);
    }

    public function testUrlGeneratorRelative()
    {
        $url = call_user_func_array($this->hateoasConfig->urlGenerator, ['/foo', [], false]);

        $this->assertEquals('/foo', $url);
    }

    public function testUrlGeneratorRelativeWithParameters()
    {
        $url = call_user_func_array($this->hateoasConfig->urlGenerator, ['/foo', ['a' => 1, 'b' => 2], false]);

        $this->assertEquals('/foo?a=1&b=2', $url);
    }

    public function testUrlGeneratorRelativeWithIdAndParameters()
    {
        $url = call_user_func_array($this->hateoasConfig->urlGenerator, ['/foo', ['id' => 5, 'name' => 'Adam'], false]);

        $this->assertEquals('/foo/5?name=Adam', $url);
    }

    public function testUrlGeneratorAbsolute()
    {
        $url = call_user_func_array($this->hateoasConfig->urlGenerator, ['/foo', ['id' => 5, 'name' => 'Adam'], true]);

        $this->assertEquals('http://localhost/foo/5?name=Adam', $url);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWithWrongConfig()
    {
        $service = new Service();
        $service->register($this->container, new SimpleConfig());
    }

    protected function getContainer()
    {
        return $this->container;
    }
}