<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/21/18
 * Time: 10:34 AM
 */

namespace Hellofresh\Service\Database;


use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Hellofresh\Contract\Service\IConfig;
use Hellofresh\Contract\Service\IService;
use League\Container\Container;

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
            throw new \InvalidArgumentException('Database wrong config object');
        }

        $container->add($config::getServiceName(), DriverManager::getConnection($config->params, new Configuration()));
    }
}