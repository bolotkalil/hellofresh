<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/19/18
 * Time: 12:42 PM
 */

namespace Hellofresh\Service\Logger;


use Hellofresh\Contract\Service\IConfig;
use Hellofresh\Contract\Service\IService;
use League\Container\Container;
use Monolog\Logger;

class Service implements IService
{

    /**
     * Set container
     *
     * @param Container $container
     * @param IConfig $config
     * @return void
     */
    public function register(Container $container, IConfig $config)
    {
        if (!$config instanceof Config) {
            throw new \InvalidArgumentException('Logger wrong config object');
        }

        $container->add($config->getServiceName(), (new Logger($config->name, $config->handlers)));
    }
}