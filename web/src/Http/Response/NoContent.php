<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/17/18
 * Time: 7:31 PM
 */

namespace Hellofresh\Http\Response;


use GuzzleHttp\Psr7\Response;

class NoContent extends Response
{
    /**
     * NoContent constructor.
     * @param mixed $body
     * @param array $headers
     */
    public function __construct($body='', array $headers=[])
    {
        parent::__construct(204, $headers, $body);
    }
}