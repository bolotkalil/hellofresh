<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 9:56 AM
 */

namespace Hellofresh\Contract\Service;

use League\Container\Container;

interface IService
{
    /**
     * Set container
     *
     * @param Container $container
     * @param IConfig $config
     * @return void
     */
    public function register(Container $container, IConfig $config);
}