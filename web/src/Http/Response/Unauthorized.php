<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/20/18
 * Time: 12:24 PM
 */

namespace Hellofresh\Http\Response;


use GuzzleHttp\Psr7\Response;

class Unauthorized extends Response
{
    public function __construct($body='', array $headers=[])
    {
        parent::__construct(401, $headers, $body);
    }
}