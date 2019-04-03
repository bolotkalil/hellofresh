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

class ErrorTest extends TestCase
{
    /**
     * @var Error
     */
    protected $error;

    public function setUp()
    {
        $this->error = new Error(new \Exception('exception message', 101));
    }

    public function testGetCode()
    {
        $this->assertEquals(101, $this->error->getCode());
    }

    public function testGetMessage()
    {
        $this->assertEquals('exception message', $this->error->getMessage());
    }

    public function testGetDetails()
    {
        $error = new Error(new Exception('', 0, 500, ['details']));

        $this->assertEquals(['details'], $error->getDetails());
    }
}
