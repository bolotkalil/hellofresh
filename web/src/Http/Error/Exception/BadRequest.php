<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/18/18
 * Time: 6:19 PM
 */

namespace Hellofresh\Http\Error\Exception;


use GuzzleHttp\Psr7\Response;

class BadRequest extends Exception
{
    /**
     * @param int $code
     * @param array $details
     * @param string $message
     * @param \Exception $previous
     */
    public function __construct(
        $code = 0,
        array $details = [],
        $message = 'Bad Request',
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, 400, $details, $previous);
    }
}