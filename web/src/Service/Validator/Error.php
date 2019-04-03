<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/20/18
 * Time: 10:55 AM
 */

namespace Hellofresh\Service\Validator;

use JMS\Serializer\Annotation;

/**
 * @Annotation\XmlRoot("result")
 */
class Error
{
    /**
     * @var string
     * @Annotation\Type("string")
     */
    public $field;

    /**
     * @var string
     * @Annotation\Type("string")
     */
    public $message;

    /**
     * @param string $field
     * @param string $message
     */
    public function __construct($field, $message)
    {
        $this->field = $field;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->field . ':' . $this->message;
    }
}
