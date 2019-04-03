<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 7:07 PM
 */

namespace Hellofresh\Http\Response;

use GuzzleHttp\Psr7\Response;

class Ok extends Response
{
    public function __construct($body='', array $headers=[])
    {
        parent::__construct(200, $headers, $body);
    }
}