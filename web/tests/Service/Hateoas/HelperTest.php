<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/17/18
 * Time: 3:00 PM
 */

namespace Hellofresh\Service\Hateoas;

use Doctrine\Common\Annotations\AnnotationRegistry;
use GuzzleHttp\Psr7\Response;
use Hellofresh\App;
use Hellofresh\Http\Error\Exception\NotAcceptable;
use Hellofresh\Http\Request\Request;
use http\Exception;
use League\Container\Container;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    use Helper;

    public static function setUpBeforeClass()
    {
        AnnotationRegistry::registerLoader('class_exists');
    }

    /**
     * @var Container
     */
    private $container;

    /**
     * @var Service
     */
    private $service;

    /**
     * @var Config
     */
    private $config;

    public function setUp()
    {
        $this->container = new Container();
        $this->service   = new Service();
        $this->config    = new Config();
        $this->service->register($this->container, $this->config);
    }

    /**
     * Returns the DI container.
     *
     * @return \League\Container\Container
     */
    protected function getContainer()
    {
        return $this->container;
    }

    protected function addParameters($acceptHeader, $vendor, $apiVersion, $body=null)
    {
        $this->container->add(App::VENDOR_ID, $vendor);
        $this->container->add(App::API_VERSION_ID, $apiVersion);

        $this->service->register($this->container, $this->config);

        $request = new Request('GET', Request::getUriFromGlobals(), [
            'Accept' => $acceptHeader
        ],
            $body,
            $this->getContainer()->get(App::API_VERSION_ID),
            [
                'HTTP_ACCEPT' => $acceptHeader
            ]
        );

        return $request;
    }

    public function testDefaultSerialize()
    {
        $request = $this->addParameters(
            '*/*',
            'hellofresh',
            '2.0'
        );
        $result = $this->serialize(['a'=>'1', 'b'=>'2'], $request, new Response());
        $this->assertEquals('{"a":"1","b":"2"}', $result->getBody()->getContents());
    }

    public function testJsonSerialize()
    {
        $request = $this->addParameters(
            'application/json',
            'hellofresh',
            '2.0'
        );
        $result = $this->serialize(['a'=>'1', 'b'=>'2'], $request, new Response());
        $this->assertEquals('{"a":"1","b":"2"}', $result->getBody()->getContents());
    }

    /**
     * @expectedException \Exception
     */
    public function testNotSupportHeaderSerialize()
    {
        $request = $this->addParameters(
            'docx',
            'hellofresh',
            '2.0'
        );
        $this->serialize(['a'=>'1', 'b'=>'2'], $request, new Response());
    }

    public function testXmlSerialize()
    {
        $request = $this->addParameters(
            'application/xml',
            'hellofresh',
            '2.0'
        );
        $result = $this->serialize(['a' => 1, 'b' => 2], $request, new Response());
        $this->assertEquals(
            <<<EOD
<?xml version="1.0" encoding="UTF-8"?>
<result>
  <entry>1</entry>
  <entry>2</entry>
</result>

EOD
            , $result->getBody()->getContents());

    }

    /**
     * @expectedException Exception
     */
    public function testJsonDeserializeWithUnsopportedFormat()
    {
        $this->container->add(App::VENDOR_ID, 'hellofresh');
        $this->container->add(App::API_VERSION_ID, '3.0');

        $request = new Request(
            'GET',
            Request::getUriFromGlobals(),
            [
                'Content-Type'=>'application/docx'
            ],
            '{"x":1,"y":2}'
        );
        $this->deserialize('Hellofresh\Stub\Entity\Simple', $request);
    }

    public function testJsonDeserialize()
    {
        $this->container->add(App::VENDOR_ID, 'hellofresh');
        $this->container->add(App::API_VERSION_ID, '3.1');

        $request = new Request(
            'GET',
            Request::getUriFromGlobals(),
            [
            ],
            '{"x":1,"y":2}',
            $this->container->get(App::API_VERSION_ID),
            [
                'HTTP_CONTENT_TYPE'=>'application/json'
            ]
        );
        $simple = $this->deserialize('Hellofresh\Stub\Entity\Simple', $request);
        $this->assertInstanceOf('Hellofresh\Stub\Entity\Simple', $simple);
        $this->assertEquals(1, $simple->x);
        $this->assertEquals(2, $simple->y);
    }
}