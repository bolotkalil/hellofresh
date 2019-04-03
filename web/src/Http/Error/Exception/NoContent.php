<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/22/18
 * Time: 12:56 PM
 */

namespace Hellofresh\Http\Error\Exception;


class NoContent extends Exception
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
        $message = 'No Content',
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, 204, $details, $previous);
    }
}