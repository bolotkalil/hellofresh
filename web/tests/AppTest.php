<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 9:24 AM
 */

namespace Hellofresh;

use Hellofresh\Http\Request\Request;
use Hellofresh\Router\Router;
use Hellofresh\Service\Hateoas\Service;
use Hellofresh\Service\Hateoas\Config as ServiceConfig;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var App
     */
    private $app;

    public function setUp()
    {
        $this->config = new Config('1.0', 'hellofresh-test', true);
        $this->config->setRouter(new Router());
        $this->app = new App($this->config);
    }

    public function testRegisterController()
    {
        $this->app->registerController('\Hellofresh\Stub\Controller\Simple');

        $this->assertInstanceOf(
            '\Hellofresh\Stub\Controller\Simple',
            $this->app->getContainer()->get('\Hellofresh\Stub\Controller\Simple')
        );
    }

    public function testRegisterService()
    {
        $this->app->registerService(new Service(), new ServiceConfig(true));

        $hateoasService = $this->app->getContainer()->get(ServiceConfig::getServiceName());

        $this->assertInstanceOf('\Hateoas\Hateoas', $hateoasService);
    }

    public function testConfig()
    {
        $this->assertInstanceOf('Hellofresh\Config', $this->app->getConfig());
    }

    public function testHead()
    {
        $this->app->getRouter()->head('/test-head', 'test-handler');

        $this->assertEquals(
            ['method' => 'HEAD', 'path' => '/test-head', 'handler' => 'test-handler'],
            $this->app->getRouter()->getRouteList()[0]
        );
    }

    public function testOptions()
    {
        $this->app->getRouter()->options('/test-options', 'test-handler');

        $this->assertEquals(
            ['method' => 'OPTIONS', 'path' => '/test-options', 'handler' => 'test-handler'],
            $this->app->getRouter()->getRouteList()[0]
        );
    }

    public function testRunNotFound()
    {
        $this->app->run();

        $content = ob_get_contents();

        $this->assertContains(
            '"code":0,"message":"Not Found"',
            $content
        );

        ob_clean();
    }

    public function testRun()
    {
        $this->app->getRouter()->get('/', 'Hellofresh\Stub\Controller\Simple::getProcess');
        $this->app->run(new Request('GET', '/'));

        $content = ob_get_contents();

        $this->assertEquals('Hello World from HelloFresh', $content);

        ob_clean();
    }
}