<?php

namespace Hellofresh\Service\Logger;

use Hellofresh\Stub\Service\SimpleConfig;
use League\Container\Container;
use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/19/18
 * Time: 1:17 PM
 */


class LoggerTest extends TestCase
{
    use \Hellofresh\Service\Logger\Helper;

    /**
     * @var Container
     */
    private $container;

    public function setUp()
    {
        $this->container = new Container();
    }

    public function testInstance()
    {
        $service = new Service();
        $service->register($this->container, new Config('loggerName'));

        $loggerService = $this->container->get(Config::getServiceName());

        $this->assertEquals('loggerName', $loggerService->getName());
    }

    public function testHelper()
    {
        $service = new Service();
        $service->register($this->container, new Config('anotherLoggerName'));
        $this->assertEquals('anotherLoggerName', $this->getLoggerService()->getName());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWithWrongConfig()
    {
        $service = new Service();
        $service->register($this->container, new SimpleConfig());
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
