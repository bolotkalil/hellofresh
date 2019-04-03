<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/18/18
 * Time: 2:56 PM
 */

namespace Hellofresh\Http\Error\Exception;


use Throwable;

class Exception extends \Exception
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var array
     */
    private $details;

    /**
     * Exception constructor.
     * @param string $message
     * @param int $code
     * @param int $statusCode
     * @param array $details
     * @param Throwable|null $previous
     */
    public function __construct(
        $message = "",
        $code = 0,
        $statusCode = 500,
        $details = [],
        Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->statusCode = $statusCode;
        $this->details = $details;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getDetails()
    {
        return $this->details;
    }
}