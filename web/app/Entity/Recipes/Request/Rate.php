<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/22/18
 * Time: 9:03 PM
 */

namespace app\Entity\Recipes\Request;

use JMS\Serializer\Annotation;

/**
 * @Annotation\XmlRoot("result")
 */
class Rate
{
    /**
     * @var integer
     * @Annotation\Type("integer")
     */
    public $rate;
}