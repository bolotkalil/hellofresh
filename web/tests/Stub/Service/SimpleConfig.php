<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/16/18
 * Time: 5:52 PM
 */

namespace Hellofresh\Stub\Service;

use Hellofresh\Contract\Service\IConfig;

class SimpleConfig implements IConfig
{
    public static function getServiceName()
    {
        return 'simple';
    }
}
