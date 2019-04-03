<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/20/18
 * Time: 12:17 PM
 */

namespace Hellofresh\Http\Middleware;

use Hellofresh\App;
use Hellofresh\Contract\Http\Middleware\AbstractMiddleware;
use Hellofresh\Http\Response\Unauthorized;
use Hellofresh\Service\Hateoas\Helper;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Auth0\SDK\JWTVerifier;

class Authentication extends AbstractMiddleware implements MiddlewareInterface
{
    use Helper;

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
        if (!isset($request->getServerParams()['HTTP_AUTHORIZATION'])){

            $response = new Unauthorized('Authorization Header not found');
            return $this->serialize(
                $response->getBody()->getContents(),
                $request,
                $response
            );

        }


        if (!isset($request->getServerParams()['HTTP_AUTHORIZATION'])){

            $response = new Unauthorized('No token provided');
            return $this->serialize(
                $response->getBody()->getContents(),
                $request,
                $response
            );

        }
        $token = (isset($request->getHeader('Authorization')[0])?
            $request->getHeader('Authorization')[0]:
            $request->getServerParams()['HTTP_AUTHORIZATION']
        );

        $this->retrieveAndValidateToken(trim(str_replace('Bearer ', '', $token), ' '));

        return $handler->handle($request);
    }

    public function retrieveAndValidateToken($token)
    {
        try {

            $verifier = new JWTVerifier([
                'supported_algs' => ['RS256'],
                'valid_audiences' => ['https://freshtesting.eu.auth0.com/api/v2/'],
                'authorized_iss' => ['https://freshtesting.eu.auth0.com/']
            ]);

            $verifier->verifyAndDecode($token);
        }
        catch(\Auth0\SDK\Exception\CoreException $e) {
            throw $e;
        };
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