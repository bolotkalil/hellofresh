<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 6:27 PM
 */

namespace Hellofresh\Helper\Mime;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Hellofresh\Helper\Mime\Mime;
use Hellofresh\Router\Router;
use League\Container\Container;
use Hellofresh\App;
use PHPUnit\Framework\TestCase;
use Hellofresh\Stub\Controller\Index;
use Psr\Container\ContainerInterface;

class MimeTest extends TestCase
{
    use Mime;
    private $container;
    public function setUp()
    {
        $this->container = new Container();
        $this->container->add(App::ROUTER_ID, new Router());
        $this->container->add(App::VENDOR_ID, 'hellofresh');
        $this->container->add(App::API_VERSION_ID, '1.0');
    }

    public function testMimeStructure()
    {
        $this->assertEquals('json', $this->getMimeStructure('application/json')->format);
        $this->assertEquals('xml', $this->getMimeStructure('application/xml')->format);
        $this->assertEquals('json', $this->getMimeStructure('application/vnd.hellofresh+json; version=1.0')->format);
        $this->assertEquals('xml', $this->getMimeStructure('application/vnd.hellofresh+xml; version=1.0')->format);
        $this->assertEquals('json', $this->getMimeStructure('application/vnd.hellofresh-v1.0+json')->format);
        $this->assertEquals('xml', $this->getMimeStructure('application/vnd.hellofresh-v1.0+xml')->format);
        $this->assertEquals(null, $this->getMimeStructure('application/docx')->format);
    }

    /**
     * Returns container.
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}