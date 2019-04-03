<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/17/18
 * Time: 10:11 PM
 */

namespace Hellofresh\Stub\Entity;

use JMS\Serializer\Annotation;

/**
 * @Annotation\XmlRoot("result")
 */
class Simple
{
    /**
     * @var int
     * @Annotation\Type("integer")
     */
    public $x;

    /**
     * @var int
     * @Annotation\Type("integer")
     */
    public $y;
}