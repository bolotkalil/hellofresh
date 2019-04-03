<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/18/18
 * Time: 5:26 PM
 */

namespace Hellofresh\Http\Error;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Hellofresh\App;
use Hellofresh\Config;
use Hellofresh\Http\Error\Exception\BadRequest;
use Hellofresh\Http\Error\Exception\Exception;
use Hellofresh\Http\Error\Handler\Formatter\Json;
use Hellofresh\Http\Request\Request;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    /**
     * @var Config
     */
    protected $config;

    public function setUp()
    {
        $this->config = new Config('1.1', 'hellofresh', true);
        $this->setElements($this->config);
    }

    /**
     * @param Config $config
     */
    private function setElements(Config $config)
    {
        AnnotationRegistry::registerLoader('class_exists');
        $config->getContainer()->get('HateoasService')->register(
            $config->getContainer(),
            $config->getContainer()->get('HateoasConfig')
        );

        $config->getContainer()->add(App::VENDOR_ID, $config->getVendor());
        $config->getContainer()->add(App::API_VERSION_ID, $config->getApiVersion());
        $config->getContainer()->add(App::DEBUG_ID, $config->isDebug());
    }

    public function testFormatWithDetailedException()
    {
        $jsonFormatter = new Json($this->config);

        $this->assertContains(
            '"code":11,"message":"Bad Request","details":[1,2,3,["a","b"]]',
            $jsonFormatter->format(new BadRequest(11, [1,2,3,['a','b']]))
        );
    }

    public function testFormatWithNotAcceptable()
    {
        $request = new Request('GET', Request::getUriFromGlobals(),
            [],
            '',
            '1.0',
            [
                'HTTP_ACCEPT'=>'docx'
            ]
        );

        $jsonFormatter = new Json($this->config, $request);

        $this->assertContains(
            '"code":0,"message":"Not Acceptable","details":["docx is not supported"]',
            $jsonFormatter->format(new \Exception())
        );
    }

    public function testFormatWithSimpleException()
    {
        $jsonFormatter = new Json($this->config);
        $this->assertContains(
            '"code":9,"message":"test","details":[]',
            $jsonFormatter->format(new Exception('test', 9, 500, []))
        );
    }
}
