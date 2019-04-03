<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/18/18
 * Time: 5:26 PM
 */

namespace Hellofresh\Http\Response;

use Doctrine\Common\Annotations\AnnotationRegistry;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Hellofresh\App;
use Hellofresh\Config;
use Hellofresh\Http\Error\Exception\BadRequest;
use Hellofresh\Http\Error\Exception\Exception;
use Hellofresh\Http\Error\Handler\Formatter\Json;
use Hellofresh\Http\Middleware\ApiVersion;
use Hellofresh\Http\Middleware\Authentication;
use Hellofresh\Http\Request\Request;
use Hellofresh\Http\Response\Ok;
use Hellofresh\Service\Hateoas\Service;
use League\Container\Container;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UnauthorizedTest extends TestCase
{
    public function testInstance()
    {
        $response = new Unauthorized('test', ['Content-Type' => 'application/json']);

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeader('content-type')[0]);
    }
}
