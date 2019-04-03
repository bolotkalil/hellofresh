<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/24/18
 * Time: 2:43 PM
 */

namespace app\Entity\Recipes\Response;


use GuzzleHttp\Psr7\ServerRequest;
use Hellofresh\App;
use Hellofresh\Http\Request\Request;
use Hellofresh\Service\Hateoas\Helper;
use League\Container\Container;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Hellofresh\Http\Response\Created as CreatedResponse;

class RecipeCreatedUpdatedTest extends TestCase
{
    use Helper;
    private $container;
    public function setUp()
    {
        $this->container = new Container();
        $this->container->add(App::API_VERSION_ID, '1.0');
        $this->container->add(App::VENDOR_ID, 'hellofresh');
        (new \Hellofresh\Service\Hateoas\Service())->register($this->container, new \Hellofresh\Service\Hateoas\Config());
    }

    public function testCreated()
    {
        $request = new Request('GET', Request::getUriFromGlobals());
        $response = $this->serialize(new \app\Entity\Recipes\Response\RecipeCreatedUpdated([
            'recipe_id'=>1,
            'name'=>"Salad",
            'prep_time'=>12,
            'difficulty'=>3,
            'vegetarian'=>true
        ]), $request,
        new CreatedResponse("/recipes/1"));

        $this->assertEquals(
            '{"recipe_id":1,"prep_time":12,"name":"Salad","difficulty":3,"vegetarian":true,"_links":{"self":{"href":"\/recipes\/1"}}}',
            $response->getBody()->getContents()
        );
    }

    /**
     * Returns container.
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}
