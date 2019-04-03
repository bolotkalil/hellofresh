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
class RecipeDeleted
{
    /**
     * @var integer
     * @Serializer\Type("integer")
     */
    public $recipe_id;

    /**
     * Created constructor.
     * @param integer $recipe_id
     */
    public function __construct($recipe_id)
    {
        $this->recipe_id = $recipe_id;
    }
}