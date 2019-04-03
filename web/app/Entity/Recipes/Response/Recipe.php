<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/22/18
 * Time: 12:41 AM
 */

namespace app\Entity\Recipes\Response;

use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @Serializer\XmlRoot("result")
 *
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route("/recipes", parameters = {"id" = "expr(object.recipe_id)"}, absolute = false)
 * )
 */
class Recipe
{
    /**
     * @var integer
     * @Serializer\Type("integer")
     */
    public $recipe_id;

    /**
     * @var integer
     * @Serializer\Type("integer")
     */
    public $prep_time;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * @var integer
     * @Serializer\Type("integer")
     */
    public $difficulty;

    /**
     * @var boolean
     * @Serializer\Type("boolean")
     */
    public $vegetarian;

    /**
     * @var float
     * @Serializer\Type("float")
     */
    public $score_rate;

    /**
     * Created constructor.
     * @param integer $recipe_id
     * @param string $name
     * @param integer $prep_time
     * @param integer $difficulty
     * @param boolean $vegetarian
     * @param float $score_rate
     */
    public function __construct(
        $recipe_id,
        $name,
        $prep_time,
        $difficulty,
        $vegetarian,
        $score_rate
    )
    {
        $this->recipe_id  = $recipe_id;
        $this->prep_time  = $prep_time;
        $this->name       = $name;
        $this->difficulty = $difficulty;
        $this->vegetarian = $vegetarian;
        $this->score_rate = $score_rate;
    }
}