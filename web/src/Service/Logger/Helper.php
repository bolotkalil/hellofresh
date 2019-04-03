<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/19/18
 * Time: 12:51 PM
 */

namespace Hellofresh\Service\Logger;

use Hellofresh\Http\Error\Handler\Log\Logger;

trait Helper
{
    use \Hellofresh\Contract\Service\Helper;

    /**
     * @return Logger
     */
    protected function getLoggerService()
    {
        return $this->getContainer()->get(Config::getServiceName());
    }
}