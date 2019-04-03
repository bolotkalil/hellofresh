<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 10:04 AM
 */

namespace Hellofresh;

use Hellofresh\Http\Error\Handler\Formatter\Json;
use Hellofresh\Router\Router;
use Hellofresh\Router\Strategy;
use League\BooBoo\BooBoo;
use League\Container\Container;
use League\Route\Strategy\StrategyInterface;

class Config
{
    /**
     * @var string
     */
    protected $apiVersion;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $vendor;

    /**
     * @var boolean
     */
    protected $debug;

    /**
     * @var Router
     */
    protected $router;

    /**
     * Config constructor.
     * @param $apiVersion
     * @param $vendor
     * @param bool $debug
     */
    public function __construct($apiVersion, $vendor, $debug=false)
    {
        if (!preg_match('/^' . App::API_VER_REG_EXP . '$/', (string) $apiVersion)) {
            throw new \InvalidArgumentException('Oops, api version is not valid');
        }

        $this->apiVersion = $apiVersion;
        $this->vendor     = $vendor;
        $this->debug      = $debug;

        $this->setContainer(new Container());
        $this->setRouter(new Router());
        $this->setRouterStrategy(new Strategy($this->getContainer()));

        $this->getContainer()->add('HateoasService',   new \Hellofresh\Service\Hateoas\Service());
        $this->getContainer()->add('HateoasConfig',    new \Hellofresh\Service\Hateoas\Config($this->debug));
        $this->getContainer()->add('LoggerService',    new \Hellofresh\Service\Logger\Service());
        $this->getContainer()->add('LoggerConfig',     new \Hellofresh\Service\Logger\Config($this->vendor));
        $this->getContainer()->add('ValidatorService', new \Hellofresh\Service\Validator\Service());
        $this->getContainer()->add('ValidatorConfig',  new \Hellofresh\Service\Validator\Config());

        $errHandler = new BooBoo([new Json($this)]);
        $errHandler->treatErrorsAsExceptions(true);
        $errHandler->silenceAllErrors(false);

        $this->getContainer()->add('ErrorHandler', $errHandler);
        $this->getContainer()->add('LoggerHandler', new \Hellofresh\Http\Error\Handler\Log\Logger());

    }

    /**
     * @return string
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Router $router
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param StrategyInterface $strategy
     */
    public function setRouterStrategy(StrategyInterface $strategy)
    {
        $this->router->setStrategy($strategy);
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

}