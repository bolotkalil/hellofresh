<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 10:28 AM
 */

namespace Hellofresh\Contract\Service;

interface IConfig
{
    /**
     * @return string
     */
    public static function getServiceName();
}