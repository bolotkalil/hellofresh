<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/23/18
 * Time: 10:57 AM
 */

namespace app\Controller;

use GuzzleHttp\Psr7\ServerRequest;
use Hellofresh\App;
use Hellofresh\Router\Router;
use Hellofresh\Service\Database\Config;
use Hellofresh\Service\Database\Service;
use League\Container\Container;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Hellofresh\Service\Hateoas\Helper as HateoasHelper;
use Hellofresh\Service\Validator\Helper as ValidatorHelper;
use Hellofresh\Service\Database\Helper as DatabaseHelper;

class RecipesTest extends TestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Recipes
     */
    private $recipes;

    public function setUp()
    {
        $this->container = new Container();
        (new Service())->register($this->container, new Config());
        $this->container->get(Config::getServiceName());
        $this->container->add(App::API_VERSION_ID, '1.0');
        $this->container->add(App::VENDOR_ID, 'hellofresh');
        $this->container->add(App::ROUTER_ID, new Router());
        (new \Hellofresh\Service\Hateoas\Service())->register($this->container, new \Hellofresh\Service\Hateoas\Config());
        $this->container->get(\Hellofresh\Service\Hateoas\Config::getServiceName());
        (new \Hellofresh\Service\Validator\Service())->register($this->container, new \Hellofresh\Service\Validator\Config());
        $this->container->get(\Hellofresh\Service\Validator\Config::getServiceName());
        $this->recipes = new Recipes($this->container, true);
    }

    private function create()
    {
        $request = new ServerRequest('POST', '/recipes', [],
            \GuzzleHttp\json_encode(['name'=>'test', 'prep_time'=>1, 'difficulty'=>1, 'vegetarian'=>true]),
            '1.0',
            ['HTTP_CONTENT_TYPE'=>'application/json']
        );

        $response = $this->recipes->create($request, []);

        return \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
    }

    private function delete($id)
    {
        $request = new ServerRequest('DELETE', '/recipes', [],
            '',
            '1.0',
            ['HTTP_CONTENT_TYPE'=>'application/json']
        );

        $response = $this->recipes->delete($request, ['id'=>$id]);

        return \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
    }

    private function update($id)
    {
        $request = new ServerRequest('PUT', '/recipes', [],
            \GuzzleHttp\json_encode(['name'=>'my test name', 'prep_time'=>1, 'difficulty'=>'3', 'vegetarian'=>true]),
            '1.0',
            ['HTTP_CONTENT_TYPE'=>'application/json']
        );

        $response = $this->recipes->update($request, ['id'=>$id]);
        return $response->getBody()->getContents();
    }

    public function testCreateAndUpdate()
    {
        $rowCreate = $this->create();
        $this->assertGreaterThan(0, $rowCreate['recipe_id']);

        $updateResponse = \GuzzleHttp\json_decode($this->update($rowCreate['recipe_id']), true);

        $this->assertEquals('my test name', $updateResponse['name']);

        $row = $this->delete($rowCreate['recipe_id']);
        $this->assertGreaterThan(0, $row['recipe_id']);
    }

    public function testGetList()
    {
        $row = $this->create();

        $request = new ServerRequest('GET', '/recipes');
        $response = $this->recipes->getList($request, []);
        $collection = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        $this->assertTrue(isset($collection['page']));
        $this->assertTrue(isset($collection['_links']));

        $row = $this->delete($row['recipe_id']);
        $this->assertGreaterThan(0, $row['recipe_id']);
    }

    public function testGet()
    {
        $row = $this->create();

        $request = new ServerRequest('GET', '/recipes/'.$row['recipe_id']);
        $response = $this->recipes->get($request, ['id'=>$row['recipe_id']]);

        $row = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        $this->assertTrue(isset($row['recipe_id']));
        $this->assertGreaterThan(0, $row['recipe_id']);

        $row = $this->delete($row['recipe_id']);
        $this->assertGreaterThan(0, $row['recipe_id']);
    }

    public function testRate()
    {
        $row = $this->create();

        $request = new ServerRequest('POST',
            '/recipes/'.$row['recipe_id'].'/rating',
            [],
            \GuzzleHttp\json_encode(['rate'=>'4']),
            '1.0',
            ['HTTP_CONTENT_TYPE'=>'application/json']
        );

        $response = $this->recipes->rate($request, ['id'=>$row['recipe_id']]);
        $row = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
        $this->assertTrue(isset($row['recipe_id']));
        $this->assertGreaterThan(0, $row['rate_id']);

        $request = new ServerRequest('GET', '/recipes');
        $response = $this->recipes->getList($request, []);
        $collection = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
        $this->assertGreaterThan(0, count($collection['_embedded']['recipes']));
        $row = $this->delete($row['recipe_id']);
        $this->assertGreaterThan(0, $row['recipe_id']);
    }

    /**
     * @expectedException Hellofresh\Http\Error\Exception\NotFound
     */
    public function testRatingNotFound()
    {
        $recipeId = rand(10000000000, 90000000000);
        $request = new ServerRequest('POST',
            '/recipes/' . $recipeId . '/rating',
            [],
            \GuzzleHttp\json_encode(['rate'=>'4']),
            '1.0',
            ['HTTP_CONTENT_TYPE'=>'application/json']
        );

        $this->recipes->rate($request, ['id'=>$recipeId]);
    }

    /**
     * @expectedException Hellofresh\Http\Error\Exception\NotFound
     */
    public function testDeleteNotFound()
    {
        $recipeId = rand(10000000000, 90000000000);
        $request = new ServerRequest('POST',
            '/recipes/' . $recipeId,
            [],
            \GuzzleHttp\json_encode(['rate'=>'4']),
            '1.0',
            ['HTTP_CONTENT_TYPE'=>'application/json']
        );

        $this->recipes->delete($request, ['id'=>$recipeId]);
    }

    /**
     * @expectedException Hellofresh\Http\Error\Exception\NotFound
     */
    public function testUpdateNotFound()
    {
        $recipeId = rand(10000000000, 90000000000);
        $request = new ServerRequest('PUT',
            '/recipes/' . $recipeId,
            [],
            \GuzzleHttp\json_encode(['name'=>'test']),
            '1.0',
            ['HTTP_CONTENT_TYPE'=>'application/json']
        );

        $this->recipes->update($request, ['id'=>$recipeId]);
    }

    /**
     * @expectedException Hellofresh\Http\Error\Exception\UnprocessableEntity
     */
    public function testCreateUnprocessableEntity()
    {
        $request = new ServerRequest('POST',
            '/recipes',
            [],
            \GuzzleHttp\json_encode(['name'=>'test']),
            '1.0',
            ['HTTP_CONTENT_TYPE'=>'application/json']
        );
        $this->recipes->create($request, []);
    }

    public function testUpdate()
    {
        $rowCreate = $this->create();
        $this->assertGreaterThan(0, $rowCreate['recipe_id']);

        $request = new ServerRequest('PUT',
            '/recipes/' . $rowCreate['recipe_id'],
            [],
            \GuzzleHttp\json_encode(['name'=>'test']),
            '1.0',
            ['HTTP_CONTENT_TYPE'=>'application/json']
        );
        $response = $this->recipes->update($request, ['id'=>$rowCreate['recipe_id']]);

        $this->assertEquals('{"recipe_id":'.$rowCreate['recipe_id'].',"name":"test","_links":{"self":{"href":"\/recipes\/'.$rowCreate['recipe_id'].'"}}}', $response->getBody()->getContents());

        $this->recipes->delete($request, ['id'=>$rowCreate['recipe_id']]);
    }

}