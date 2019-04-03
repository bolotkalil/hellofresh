<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 10:44 AM
 */

namespace Hellofresh\Router;

use League\Route\Route;
use League\Route\RouteCollectionInterface;
use League\Route\Strategy\StrategyInterface;
use FastRoute\RouteParser;
use FastRoute\DataGenerator;

class Router extends \League\Route\Router implements RouteCollectionInterface
{

    /**
     * @var array keys: method, path, handler
     */
    protected $routeList = [];

    /**
     * @param RouteParser               $parser
     * @param DataGenerator             $generator
     */
    public function __construct(
        RouteParser        $parser = null,
        DataGenerator      $generator = null
    ) {
        parent::__construct($parser, $generator);

        $this->addPatternMatcher('any', '\d\.\d');
    }

    /**
     * Add a route to the map.
     *
     * @param string $method
     * @param string $path
     * @param callable|string $handler
     * @param StrategyInterface $strategy
     *
     * @return \League\Route\Route
     */
    public function map(string $method, string $path, $handler, StrategyInterface $strategy=null): Route
    {
        if (!is_null($strategy)) {
            parent::setStrategy($strategy);
        }

        $this->routeList[] = [
            'method'    => $method,
            'path'      => $path,
            'handler'   => $handler,
        ];

        return parent::map($method, $path, $handler);
    }

    /**
     * @return array keys: method, path, handler
     */
    public function getRouteList()
    {
        return $this->routeList;
    }
}