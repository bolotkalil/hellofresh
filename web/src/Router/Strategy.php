<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 12:23 PM
 */

namespace Hellofresh\Router;

use GuzzleHttp\Psr7\Response;
use Hellofresh\Http\Response\NoContent;
use Hellofresh\Service\Hateoas\Helper;
use http\Exception;
use League\Container\ContainerAwareTrait;
use League\Route\Http\Exception\MethodNotAllowedException;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Route;
use League\Route\Strategy\StrategyInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Strategy implements StrategyInterface
{
    use ContainerAwareTrait;
    use Helper;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Invoke the route callable based on the strategy.
     *
     * @param \League\Route\Route $route
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function invokeRouteCallable(Route $route, ServerRequestInterface $request): ResponseInterface
    {
        $controller = $route->getCallable($this->getContainer());
        $response = $controller($request, $route->getVars());

        if ($response instanceof Response && $response->getBody()->getContents() !== '') {
            $response->getBody()->rewind();
            return $this->serialize(
                $response->getBody()->getContents(),
                $request,
                $response
            );
        }

        return $response;
    }

    /**
     * Get a middleware that will decorate a NotFoundException
     *
     * @param \League\Route\Http\Exception\NotFoundException $exception
     *
     * @return \Psr\Http\Server\MiddlewareInterface
     */
    public function getNotFoundDecorator(NotFoundException $exception): MiddlewareInterface
    {
        return $this->throwExceptionMiddleware($exception, $this->container);
    }

    /**
     * Get a middleware that will decorate a NotAllowedException
     *
     * @param \League\Route\Http\Exception\NotFoundException $exception
     *
     * @return \Psr\Http\Server\MiddlewareInterface
     */
    public function getMethodNotAllowedDecorator(MethodNotAllowedException $exception): MiddlewareInterface
    {
        return $this->throwExceptionMiddleware($exception, $this->container);
    }

    /**
     * Return a middleware that simply throws an exception
     *
     * @param NotFoundException $exception
     *
     * @return \Psr\Http\Server\MiddlewareInterface
     */
    protected function throwExceptionMiddleware($exception, $container) : MiddlewareInterface
    {
        return new class($exception, $container) implements MiddlewareInterface
        {
            use Helper;
            protected $exception;
            protected $container;
            public function __construct($exception, $container)
            {
                $this->exception = $exception;
                $this->container = $container;
            }
            public function process(
                ServerRequestInterface $request,
                RequestHandlerInterface $requestHandler
            ) : ResponseInterface {
                $response = new NoContent('No Content');
                return $this->serialize(
                    $response->getBody()->getContents(),
                    $request,
                    $response
                );
            }

            /**
             * Returns container.
             *
             * @return \League\Container\Container
             */
            public function getContainer()
            {
                return $this->container;
            }
        };
    }


    /**
     * Get a middleware that acts as an exception handler, it should wrap the rest of the
     * middleware stack and catch eny exceptions.
     *
     * @return \Psr\Http\Server\MiddlewareInterface
     */
    public function getExceptionHandler(): MiddlewareInterface
    {
        return new class implements MiddlewareInterface
        {
            /**
             * {@inheritdoc}
             */
            public function process(
                ServerRequestInterface $request,
                RequestHandlerInterface $requestHandler
            ) : ResponseInterface {
                try {
                    return $requestHandler->handle($request);
                } catch (Exception $e) {
                    throw $e;
                }
            }
        };
    }
}