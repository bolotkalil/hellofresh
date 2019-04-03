<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/17/18
 * Time: 7:31 PM
 */

namespace Hellofresh\Http\Response;


use GuzzleHttp\Psr7\Response;

class NotModified extends Response
{
    /**
     * NotModified constructor.
     * @param string $location The value of the Location header
     * @param string $etag The value of the Etag header
     * @param array $headers
     */
    public function __construct($location, $etag, array $headers=[])
    {
        $headers['Content-Location'] = $location;
        $headers['Etag'] = $etag;
        parent::__construct(304, $headers);
    }
}