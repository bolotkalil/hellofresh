<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/21/18
 * Time: 4:27 PM
 */

namespace app\Controller;

use app\Entity\Recipes\Response\Recipe;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use Hellofresh\Http\Controller\BaseController;
use Hellofresh\Http\Error\Exception\NotAcceptable;
use Hellofresh\Http\Error\Exception\NotFound;
use Hellofresh\Http\Error\Exception\UnprocessableEntity;
use Hellofresh\Http\Response\Created;
use Hellofresh\Http\Response\Ok;
use Hellofresh\Service\Hateoas\Helper as HateoasHelper;
use Hellofresh\Service\Validator\Helper as ValidatorHelper;
use Hellofresh\Service\Database\Helper as DatabaseHelper;
use Hellofresh\Service\Validator\Error;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;
use Hellofresh\Router\Annotation;

class Recipes extends BaseController
{
    use HateoasHelper;
    use ValidatorHelper;
    use DatabaseHelper;

    /**
     * @Annotation(method="GET", path="/recipes", since=3.0)
     */
    public function getList(ServerRequestInterface $request, $params)
    {
        $perPage = isset($request->getQueryParams()['limit']) ? $request->getQueryParams()['limit'] :10;
        $page    = isset($request->getQueryParams()['page'])  ? $request->getQueryParams()['page']  :0;

        $fetch = $this->getDatabaseService()->createQueryBuilder()->select('COUNT(r.*) AS total')
            ->from('recipes', 'r')
            ->execute()
            ->fetch();

        if ($page) {
            $start = ($page - 1) * $perPage;
        } else {
            $start = 0;
        }

        $collection = $this->getDatabaseService()->createQueryBuilder()->select('r.*, AVG(rr.rate) AS score_rate')
            ->from('recipes', 'r')
            ->leftJoin('r', 'recipes_rate', 'rr', 'rr.recipe_id = r.recipe_id')
            ->groupBy('r.recipe_id')
            ->setFirstResult($start)
            ->setMaxResults($perPage)
            ->execute()
            ->fetchAll();

        if ($page == 0) $page = 1;

        $paginatedCollection = new PaginatedRepresentation(
            new CollectionRepresentation($collection,'recipes', 'recipes'),
            '/recipes',
            array(),
            $page,
            $perPage,
            ceil((int)$fetch['total'] / $perPage),
            'page',
            'limit',
            false,
            $fetch['total']
        );

        return $this->serialize($paginatedCollection,
            $request,
            new Ok());
    }

    /**
     * @Annotation(method="POST", path="/recipes", middleware="\Hellofresh\Http\Middleware\Authentication", since=1.0, until=4.0)
     */
    public function create(ServerRequestInterface $request, $params)
    {
        $data = (array) $this->deserialize('app\Entity\Recipes\Request\Recipe', $request);

        $validator = [
            'name' => [
                new Length(array('min' => 2)),
                new NotBlank()
            ],
            'prep_time' => [
                new Type('numeric'),
                new GreaterThan([
                    'value' => -1
                ]),
                new NotBlank()
            ],
            'difficulty' => [
                new Range([
                    'min' => 1,
                    'max' => 3
                ]),
                new NotBlank()
            ],
            'vegetarian' => [
                new Choice([
                    true,
                    false
                ]),
                new NotBlank()
            ]
        ];

        $this->validate($data, $validator);

        $result = $this->getDatabaseService()->insert('recipes', $data);

        if ($result) {

            $data['recipe_id'] = $this->getDatabaseService()->lastInsertId();

            return $this->serialize(new \app\Entity\Recipes\Response\RecipeCreatedUpdated($data),
                $request,
                new Created("/recipes/" . $this->getDatabaseService()->lastInsertId())
            );

        }

        throw new NotAcceptable();
    }

    /**
     * @Annotation(method="GET", path="/recipes/{id}", since=1.0)
     */
    public function get(ServerRequestInterface $request, $params)
    {
        $fetch = $this->getDatabaseService()->createQueryBuilder()
            ->select('r.*, AVG(rr.rate) AS score_rate')
            ->from('recipes', 'r')
            ->leftJoin('r', 'recipes_rate', 'rr', 'rr.recipe_id = r.recipe_id')
            ->groupBy('r.recipe_id')
            ->where('r.recipe_id = ' . $params['id'])
            ->execute()
            ->fetch();

        if (isset($fetch['recipe_id'])) {

            return $this->serialize(new Recipe(
                    $fetch['recipe_id'],
                    $fetch['name'],
                    $fetch['prep_time'],
                    $fetch['difficulty'],
                    $fetch['vegetarian'],
                    $fetch['score_rate']
                ),
                $request,
                new Ok()
            );

        }

        throw new NotFound();
    }

    /**
     * @Annotation(method="PUT", path="/recipes/{id}", middleware="\Hellofresh\Http\Middleware\Authentication", since=1.0)
     */
    public function update(ServerRequestInterface $request, $params)
    {
        $data = (array) $this->deserialize('app\Entity\Recipes\Request\Recipe', $request);

        $fetch = $this->getDatabaseService()->createQueryBuilder()
            ->select('r.recipe_id')
            ->from('recipes', 'r')
            ->where('r.recipe_id = ' . $params['id'])
            ->execute()
            ->fetch();

        if (!isset($fetch['recipe_id'])) {

            throw new NotFound();

        }

        $validator = [
            'name' => [
                new Length(array('min' => 2)),
                new NotBlank()
            ],
            'prep_time' => [
                new Type('numeric'),
                new GreaterThan([
                    'value' => -1
                ]),
                new NotBlank()
            ],
            'difficulty' => [
                new Range([
                    'min' => 1,
                    'max' => 3
                ]),
                new NotBlank()
            ],
            'vegetarian' => [
                new Choice([
                    true,
                    false
                ]),
                new NotBlank()
            ]
        ];

        foreach ($data as $key=>$value) {

            if (empty($value)) {

                unset($validator[$key], $data[$key]);

            }

        }

        $this->validate($data, $validator);

        $result = $this->getDatabaseService()->update('recipes', $data, ["recipe_id"  => $fetch['recipe_id']]);

        if ($result) {

            $data['recipe_id'] = $fetch['recipe_id'];

            return $this->serialize(new \app\Entity\Recipes\Response\RecipeCreatedUpdated($data),
                $request,
                new Ok()
            );

        }

        throw new NotAcceptable();
    }

    /**
     * @Annotation(method="DELETE", path="/recipes/{id}", middleware="\Hellofresh\Http\Middleware\Authentication", since=1.0)
     */
    public function delete(ServerRequestInterface $request, $params)
    {
        $fetch = $this->getDatabaseService()->createQueryBuilder()->select('*')
            ->from('recipes', 'r')
            ->where('r.recipe_id = ' . $params['id'])
            ->execute()
            ->fetch();

        if (isset($fetch['recipe_id'])) {

            $deleted = $this->getDatabaseService()->createQueryBuilder()
                ->delete('recipes')
                ->where('recipe_id = ' . $fetch['recipe_id'])
                ->execute();

            if ($deleted) {

                $this->getDatabaseService()->createQueryBuilder()
                    ->delete('recipes_rate')
                    ->where('recipe_id = ' . $fetch['recipe_id'])
                    ->execute();

                return $this->serialize(new \app\Entity\Recipes\Response\RecipeDeleted(
                    $fetch['recipe_id']
                ),
                    $request,
                    new Ok()
                );

            }

            throw new NotAcceptable();

        }

        throw new NotFound();
    }

    /**
     * @Annotation(method="POST", path="/recipes/{id}/rating", since=1.0)
     */
    public function rate(ServerRequestInterface $request, $params)
    {

        $fetch = $this->getDatabaseService()->createQueryBuilder()->select('*')
            ->from('recipes', 'r')
            ->where('r.recipe_id = ' . $params['id'])
            ->execute()
            ->fetch();

        if (isset($fetch['recipe_id'])) {

            $data = (array) $this->deserialize('app\Entity\Recipes\Request\Rate', $request);

            $validator = [
                'rate' => [
                    new Range([
                        'min' => 1,
                        'max' => 5
                    ]),
                    new NotBlank()
                ]
            ];

            $this->validate($data, $validator);

            $data['recipe_id'] = $fetch['recipe_id'];
            $result = $this->getDatabaseService()->insert('recipes_rate', $data);

            if ($result) {

                $rated = new \app\Entity\Recipes\Response\RecipeRated(
                    $this->getDatabaseService()->lastInsertId(),
                    $data['recipe_id'],
                    $data['rate']
                );

                return $this->serialize($rated,
                    $request,
                    new Ok()
                );

            }

            throw new NotAcceptable();
        }

        throw new NotFound();
    }

    /**
     * @param $data
     * @param $validator
     * @throws UnprocessableEntity
     */
    private function validate($data, $validator)
    {
        foreach ($validator as $name => $condition) {

            $violation = $this->getValidatorService()->validate($data[$name], $condition);

            if ($violation->count()) {
                throw new UnprocessableEntity(0, [new Error($name, $violation->get(0)->getMessage())]);
            }
        }
    }
}