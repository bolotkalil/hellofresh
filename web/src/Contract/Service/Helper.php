<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/19/18
 * Time: 12:54 PM
 */

namespace Hellofresh\Contract\Service;

use Psr\Container\ContainerInterface;

trait Helper
{
    /**
     * Returns container.
     *
     * @return ContainerInterface
     */
    abstract public function getContainer();
}