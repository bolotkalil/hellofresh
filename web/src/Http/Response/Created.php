<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/17/18
 * Time: 7:31 PM
 */

namespace Hellofresh\Http\Response;


use GuzzleHttp\Psr7\Response;

class Created extends Response
{
    /**
     * Created constructor.
     * @param string $location The value of the Location header
     * @param mixed $body
     * @param array $headers
     */
    public function __construct($location, $body='', array $headers=[])
    {
        $headers['Location'] = $location;
        parent::__construct(201, $headers, $body);
    }
}