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
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route("/recipes", parameters = {"id" = "expr(object.getPath())"}, absolute = false)
 * )
 */
class RecipeRated
{
    /**
     * @var integer
     * @Serializer\Type("integer")
     */
    public $rate_id;

    /**
     * @var integer
     * @Serializer\Type("integer")
     */
    public $recipe_id;

    /**
     * @var integer
     * @Serializer\Type("integer")
     */
    public $rate;

    /**
     * Rated constructor.
     * @param $rate_id
     * @param $recipeId
     * @param $rate
     */
    public function __construct($rate_id, $recipeId, $rate)
    {
        $this->rate_id   = $rate_id;
        $this->recipe_id = $recipeId;
        $this->rate      = $rate;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return  $this->recipe_id.'/rating';
    }
}