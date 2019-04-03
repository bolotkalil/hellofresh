<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/21/18
 * Time: 5:47 PM
 */

namespace Hellofresh\Contract\Http\Middleware;


use Psr\Container\ContainerInterface;

class AbstractMiddleware
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * AbstractMiddleware constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}