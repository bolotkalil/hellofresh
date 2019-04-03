<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/21/18
 * Time: 10:36 AM
 */

namespace Hellofresh\Service\Database;


trait Helper
{
    use \Hellofresh\Contract\Service\Helper;

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDatabaseService()
    {
        return $this->getContainer()->get(Config::getServiceName());
    }
}