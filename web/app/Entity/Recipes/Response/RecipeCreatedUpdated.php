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
class RecipeCreatedUpdated
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
     * Created constructor.
     * @param array $recipe
     */
    public function __construct($recipe)
    {
        foreach ($recipe as $key=>$field) {

            $this->{$key} = $field;

        }
    }
}