<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 9:24 AM
 */

namespace Hellofresh;

use Doctrine\Common\Annotations\AnnotationRegistry;
use GuzzleHttp\Psr7\Response;
use Hellofresh\Contract\Service\IConfig;
use Hellofresh\Contract\Service\IService;
use Hellofresh\Http\Error\Handler\Formatter\Json;
use Hellofresh\Http\Middleware\ApiVersion;
use Hellofresh\Http\Request\Request;
use Hellofresh\Router\Router;
use League\Route\RouteCollectionTrait;
use Psr\Http\Message\ServerRequestInterface;
use Hellofresh\Service\Hateoas\Helper as HateoasServiceHelper;
use Hellofresh\Service\Logger\Helper as LoggerServiceHelper;

class App
{
    use RouteCollectionTrait;
    use HateoasServiceHelper;
    use LoggerServiceHelper;

    const DEBUG_ID       = 'debug';
    const ROUTER_ID      = 'router';
    const API_VERSION_ID = 'api-version';
    const VENDOR_ID      = 'vendor';

    const API_VER_REG_EXP = '((?:[0-9](?:\.[0-9])?){1})';

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var callable
     */
    protected $exceptionDecorator;

    /**
     * App constructor.
     * @param Config $config
     */

    public function __construct(Config $config)
    {
        $this->config = $config;

        AnnotationRegistry::registerLoader('class_exists');

        $this->registerService($this->getContainer()->get('HateoasService'), $this->getContainer()->get('HateoasConfig'));
        $this->registerService($this->getContainer()->get('LoggerService'), $this->getContainer()->get('LoggerConfig'));
        $this->registerService($this->getContainer()->get('ValidatorService'), $this->getContainer()->get('ValidatorConfig'));

        $this->getContainer()->add(self::API_VERSION_ID, $this->config->getApiVersion());
        $this->getContainer()->add(self::VENDOR_ID, $this->config->getVendor());
        $this->getContainer()->add(self::DEBUG_ID, $this->config->isDebug());
        $this->getContainer()->add(self::ROUTER_ID, function () {
            return $this->getRouter();
        });

        $this->initErrorHandler();
    }

    /**
     * @param Contract\Service\IService $service
     * @param Contract\Service\IConfig $config
     *
     * @return void
     */
    public function registerService(IService $service, IConfig $config)
    {
        $service->register($this->getContainer(), $config);
    }

    /**
     * @param string $class, class name with namespace
     *
     * @return void
     */
    public function registerController($class)
    {
        $controller = new $class($this->getContainer());
        $this->getContainer()->add($class, function () use ($controller) {
            return $controller;
        });
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->config->getRouter();
    }
    /**
     * Run the application
     *
     * @param ServerRequestInterface $request
     *
     * @return void
     */
    public function run(ServerRequestInterface $request = null)
    {
        if (is_null($request)) {
            $request = Request::fromGlobals();
        }

        $response  = $this->handle($request);
        if (!headers_sent()) {

            /* RFC2616 - 14.18 says all Responses need to have a Date */
            if (!$response->hasHeader('Date')) {
                $date = (\DateTime::createFromFormat('U', time()));
                $date->setTimezone(new \DateTimeZone('UTC'));
                $date = $date->format('D, d M Y H:i:s').' GMT';
                $response = $response->withHeader('Date', $date);
            }

            // headers
            foreach ($response->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    header($name.': '.$value, false, $response->getStatusCode());
                }
            }
            // status
            header(sprintf('HTTP/%s %s %s', $response->getProtocolVersion(), $response->getStatusCode(), $response->getReasonPhrase()), true, $response->getStatusCode());

        }
        echo $response->getBody()->getContents();
    }

    /**
     * @param ServerRequestInterface|null $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function handle(ServerRequestInterface $request = null)
    {
        $this->getRouter()->middleware(new ApiVersion($this->getContainer()));

        try {

            $response = $this->getRouter()->dispatch($request);

            return $response;

        } catch (\Exception $e) {
            $response = call_user_func($this->exceptionDecorator, $e);
            if (!$response instanceof Response) {
                throw new \LogicException(
                    'Exception decorator did not return an instance of Response'
                );
            }

            return $response;
        }
    }

    /**
     * @param callable $callable
     */
    public function setExceptionDecorator(callable $callable)
    {
        $this->exceptionDecorator = $callable;
    }

    /**
     * @return void
     */
    public function initErrorHandler()
    {
        $app = $this;

        $this->getContainer()->get('LoggerHandler')->setLogger($this->getLoggerService());
        $this->getContainer()->get('ErrorHandler')->pushHandler($this->getContainer()->get('LoggerHandler'));
        $this->getContainer()->get('ErrorHandler')->register();

        $this->setExceptionDecorator(function (\Exception $e) use ($app) {
            $formatter = new Json($this->config);
            return new Response(http_response_code(), [], $formatter->format($e));
        });
    }

    /**
     * Returns container.
     *
     * @return \League\Container\Container
     */
    public function getContainer()
    {
        return $this->config->getContainer();
    }

    /**
     * Add a route to the map.
     *
     * @param string $method
     * @param string $path
     * @param callable|string $handler
     *
     * @return \League\Route\Route
     */
    public function map(string $method, string $path, $handler)
    {
        return $this->getRouter()->map($method, $path, $handler);
    }
}