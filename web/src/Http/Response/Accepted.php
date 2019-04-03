<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/17/18
 * Time: 7:31 PM
 */

namespace Hellofresh\Http\Response;


use GuzzleHttp\Psr7\Response;

class Accepted extends Response
{
    /**
     * Accepted constructor.
     * @param int $body
     * @param array $headers
     */
    public function __construct($body='', array $headers=[])
    {
        parent::__construct(202, $headers, $body);
    }
}