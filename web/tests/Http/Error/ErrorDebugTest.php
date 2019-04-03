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

class ErrorDebugTest extends TestCase
{
    /**
     * @var ErrorDebug
     */
    protected $errorDebug;

    public function setUp()
    {
        $this->errorDebug = new ErrorDebug(new \Exception('exception message', 101));
    }

    public function testGetFileName()
    {
        $this->assertEquals('ErrorDebugTest.php', basename($this->errorDebug->getFile()));
    }

    public function testGetLine()
    {
        $this->assertEquals(29, $this->errorDebug->getLine());
    }

    public function testGetTrace()
    {
        $this->assertNotNull($this->errorDebug->getTrace());
    }
}
