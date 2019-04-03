<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 6:27 PM
 */

namespace Hellofresh\Http\Controller;

use Doctrine\Common\Annotations\AnnotationReader;
use Hellofresh\Router\Annotation;
use Hellofresh\Router\Router;
use Psr\Container\ContainerInterface;
use Hellofresh\App;

abstract class BaseController
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     * @param bool $registerRoutes
     */
    public function __construct(ContainerInterface $container, $registerRoutes=true)
    {
        $this->container = $container;

        if ($registerRoutes) {
            $this->registerRoutes();
        }
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->container;
    }

    /**
     * @return void
     */
    protected function registerRoutes()
    {
        $reader = new AnnotationReader();
        $class  = new \ReflectionClass($this);
        /** @var Router $router */
        $router = $this->getContainer()->get(App::ROUTER_ID);

        /** @var \ReflectionMethod $method */
        foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $this->registerRoute(
                $router,
                $class,
                $method,
                $reader->getMethodAnnotation($method, '\Hellofresh\Router\Annotation')
            );
        }
    }

    /**
     * @param Router $router
     * @param \ReflectionClass $class
     * @param \ReflectionMethod $method
     * @param mixed $docBlock
     */
    protected function registerRoute(
        Router $router,
        \ReflectionClass $class,
        \ReflectionMethod $method,
        $docBlock
    ) {

        if ($docBlock instanceof Annotation) {
            $this->addVersionToRoute($docBlock);

            if (isset($docBlock->middleware) && class_exists($docBlock->middleware)) {

                $router->map(
                    $docBlock->method,
                    $docBlock->path,
                    '\\' . $class->getName() . '::' . $method->getName()
                )->middleware(new $docBlock->middleware($this->getContainer()));

            } else {

                $router->map(
                    $docBlock->method,
                    $docBlock->path,
                    '\\' . $class->getName() . '::' . $method->getName()
                );

            }
        }
    }

    /**
     * @param \Hellofresh\Router\Annotation $docBlock
     */
    protected function addVersionToRoute(Annotation $docBlock)
    {
        if (! is_null($docBlock->version) && $docBlock->path[0] === '/') {
            $docBlock->path = '/' . $docBlock->version . $docBlock->path;
        } elseif (! is_null($docBlock->version) && $docBlock->path[0] !== '/') {
            $docBlock->path = '/' . $docBlock->version . '/' . $docBlock->path;
        }
    }
}