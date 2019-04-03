<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 7:03 PM
 */

namespace Hellofresh\Http\Request;

use GuzzleHttp\Psr7\ServerRequest;

class Request extends ServerRequest
{
    /**
     * Request constructor.
     * @param string $method
     * @param \Psr\Http\Message\UriInterface|string $uri
     * @param array $headers
     * @param null $body
     * @param string $version
     * @param array $serverParams
     */
    public function __construct($method, $uri, array $headers = [], $body = null, $version = '1.1', array $serverParams = [])
    {
        parent::__construct($method, $uri, $headers, $body, $version, $serverParams);
    }

}