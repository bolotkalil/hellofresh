<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/19/18
 * Time: 8:23 PM
 */

namespace Hellofresh\Http\Middleware;

use Hellofresh\App;
use Hellofresh\Contract\Http\Middleware\AbstractMiddleware;
use Hellofresh\Helper\Mime\Mime;
use Hellofresh\Http\Request\Request;
use Negotiation\Negotiator;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ApiVersion extends AbstractMiddleware implements MiddlewareInterface
{
    use Mime;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $accept = (isset($request->getServerParams()['HTTP_ACCEPT']) ? $request->getServerParams()['HTTP_ACCEPT'] : '*/*');

        $mediaApplication = (new Negotiator())->getBest($accept, ['application/json', 'application/xml']);

        if (method_exists($mediaApplication, 'getValue')) {
            $structure = $this->getMimeStructure($mediaApplication->getValue());
            $request = $request->withProtocolVersion(str_pad($structure->apiVersion, 3, '.0'));
        }

        return $handler->handle($request);
    }

    /**
     * Returns container.
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}