<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/18/18
 * Time: 2:49 PM
 */

namespace Hellofresh\Http\Error;

use JMS\Serializer\Annotation;
use Hellofresh\Http\Error\Exception;

/**
 * @Annotation\XmlRoot("result")
 */
class Error
{
    /**
     * @var int
     * @Annotation\Type("integer")
     */
    protected $code;

    /**
     * @var string
     * @Annotation\Type("string")
     */
    protected $message;

    /**
     * @var array
     * @Annotation\Type("array")
     */
    protected $details;

    /**
     * Error constructor.
     * @param object $e
     */
    public function __construct($e)
    {
        $this->code    = $e->getCode();
        $this->message = $e->getMessage();

        if ($e instanceof Exception\Exception) {
            $this->details = $e->getDetails();
        }
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getDetails()
    {
        return $this->details;
    }
}