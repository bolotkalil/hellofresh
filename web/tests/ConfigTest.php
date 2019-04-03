<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 9:24 AM
 */

namespace Hellofresh;

use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @var Config
     */
    private $config;

    public function setUp()
    {
        $this->config = new Config('1.0', 'hellofresh', true);
    }

    public function testServices()
    {
        $this->assertEquals('1.0', $this->config->getApiVersion());
        $this->assertEquals('hellofresh', $this->config->getVendor());
        $this->assertTrue($this->config->isDebug());

        $this->assertInstanceOf('\Hellofresh\Service\Hateoas\Service', $this->config->getContainer()->get('HateoasService'));
        $this->assertInstanceOf('\Hellofresh\Service\Hateoas\Config', $this->config->getContainer()->get('HateoasConfig'));

        $this->assertInstanceOf('\Hellofresh\Service\Logger\Service', $this->config->getContainer()->get('LoggerService'));
        $this->assertInstanceOf('\Hellofresh\Service\Logger\Config', $this->config->getContainer()->get('LoggerConfig'));

        $this->assertInstanceOf('\Hellofresh\Service\Validator\Service', $this->config->getContainer()->get('ValidatorService'));
        $this->assertInstanceOf('\Hellofresh\Service\Validator\Config', $this->config->getContainer()->get('ValidatorConfig'));

        $this->assertInstanceOf('Hellofresh\Router\Router', $this->config->getRouter());

        $this->assertInstanceOf('League\BooBoo\BooBoo', $this->config->getContainer()->get('ErrorHandler'));
        $this->assertInstanceOf('\Hellofresh\Http\Error\Handler\Log\Logger', $this->config->getContainer()->get('LoggerHandler'));

    }

    /**
     * @dataProvider inCorrectApiVersionsDataProvider
     *
     * @expectedException \InvalidArgumentException
     *
     * @param mixed $apiVersion
     */

    public function testInCorrectApiVersions($apiVersion) {
        $config = new Config($apiVersion, 'hellofresh', true);

        $this->assertEquals($apiVersion, $config->getApiVersion());
    }

    public function inCorrectApiVersionsDataProvider() {
        return [
            [-2], [-1], [10], [11], [12],
            ['a'], [null],
            ['1.0.0'],
            ['1.2.3.4'],
            ['10.1'],
            ['1.10']
        ];
    }

    /**
     * @dataProvider apiVersionsDataProvider
     *
     * @param mixed $apiVersion
     */
    public function testCorrectApiVersions($apiVersion) {
        $config = new Config($apiVersion, 'hellofresh', true);

        $this->assertEquals($apiVersion, $config->getApiVersion());
    }

    public function apiVersionsDataProvider() {
        return [
            [0], [1], [2], [3], [4], [5], [6], [7], [8], [9],
            ['0.0'], ['0.1'], ['0.2'], ['0.8'], ['0.9'],
            ['1.0'], ['1.1'], ['1.2'], ['1.8'], ['1.9'],
            ['2.0'], ['2.1'], ['2.2'], ['2.8'], ['2.9'],
            ['3.0'], ['3.1'], ['3.2'], ['3.8'], ['3.9'],
            ['4.0'], ['4.1'], ['4.2'], ['4.8'], ['4.9'],
            ['5.0'], ['5.1'], ['5.2'], ['5.8'], ['5.9'],
            ['6.0'], ['6.1'], ['6.2'], ['6.8'], ['6.9'],
            ['7.0'], ['7.1'], ['7.2'], ['7.8'], ['7.9'],
            ['8.0'], ['8.1'], ['8.2'], ['8.8'], ['8.9'],
            ['9.0'], ['9.1'], ['9.2'], ['9.8'], ['9.9']
        ];
    }
}