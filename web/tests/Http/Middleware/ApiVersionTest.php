<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/18/18
 * Time: 5:26 PM
 */

namespace Hellofresh\Http\Middleware;

use Doctrine\Common\Annotations\AnnotationRegistry;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Hellofresh\App;
use Hellofresh\Config;
use Hellofresh\Http\Error\Exception\BadRequest;
use Hellofresh\Http\Error\Exception\Exception;
use Hellofresh\Http\Error\Handler\Formatter\Json;
use Hellofresh\Http\Middleware\ApiVersion;
use Hellofresh\Http\Request\Request;
use Hellofresh\Http\Response\Ok;
use League\Container\Container;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ApiVersionTest extends TestCase
{
    private $container;
    public function setUp()
    {
        $this->container = new Container();
        $this->container->add(App::API_VERSION_ID, '1.0');
        $this->container->add(App::VENDOR_ID, 'hellofresh');
    }

    public function testHandle()
    {
        $middleware = new ApiVersion($this->container);
        $handler = (new class implements RequestHandlerInterface{

            /**
             * Handle the request and return a response.
             */
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new Ok($request->getProtocolVersion());
            }
        });
        $response = $middleware->process(new ServerRequest('GET', Request::getUriFromGlobals(), [], '', '1.1', ['HTTP_ACCEPT'=>'application/json']), $handler);
        $this->assertEquals('1.0', $response->getBody()->getContents());

    }
}
