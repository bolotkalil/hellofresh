<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/22/18
 * Time: 8:00 PM
 */

namespace app\Entity\Recipes\Request;

use JMS\Serializer\Annotation;

/**
 * @Annotation\XmlRoot("result")
 */
class Recipe
{
    /**
     * @var string
     * @Annotation\Type("string")
     */
    public $name;

    /**
     * @var integer
     * @Annotation\Type("integer")
     */
    public $prep_time;

    /**
     * @var integer
     * @Annotation\Type("integer")
     */
    public $difficulty;

    /**
     * @var boolean
     * @Annotation\Type("boolean")
     */
    public $vegetarian;
}