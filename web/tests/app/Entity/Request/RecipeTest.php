<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/24/18
 * Time: 2:43 PM
 */

namespace app\Entity\Recipes\Request;

use GuzzleHttp\Psr7\ServerRequest;
use Hellofresh\App;
use Hellofresh\Http\Request\Request;
use Hellofresh\Service\Hateoas\Helper;
use League\Container\Container;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Hellofresh\Http\Response\Created as CreatedResponse;

class RecipeTest extends TestCase
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

    public function testRecipe()
    {
        $request = new Request(
            'GET',
            Request::getUriFromGlobals(),
            [],
            \GuzzleHttp\json_encode(['name'=>'Salad','prep_time'=>12,'difficulty'=>3,'vegetarian'=>true]),
            '1.0',
            ['HTTP_CONTENT_TYPE'=>'application/json']
        );
        $dataObject = $this->deserialize('app\Entity\Recipes\Request\Recipe', $request);
        $this->assertEquals('Salad', $dataObject->name);
        $this->assertEquals(12, $dataObject->prep_time);
        $this->assertEquals(3, $dataObject->difficulty);
        $this->assertTrue($dataObject->vegetarian);
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
