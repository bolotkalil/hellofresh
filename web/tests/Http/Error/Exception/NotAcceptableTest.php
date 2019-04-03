<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/18/18
 * Time: 5:26 PM
 */

namespace Hellofresh\Http\Error\Exception;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Hellofresh\App;
use Hellofresh\Config;
use Hellofresh\Http\Error\Exception\BadRequest;
use Hellofresh\Http\Error\Exception\Exception;
use Hellofresh\Http\Error\Handler\Formatter\Json;
use Hellofresh\Http\Request\Request;
use PHPUnit\Framework\TestCase;

class NotAcceptableTest extends TestCase
{
    public function testInstance()
    {
        $exception = new NotAcceptable(9, [1,2,3]);

        $this->assertEquals('Not Acceptable', $exception->getMessage());
        $this->assertEquals(406, $exception->getStatusCode());
        $this->assertEquals(9, $exception->getCode());
        $this->assertEquals([1,2,3], $exception->getDetails());
    }
}
