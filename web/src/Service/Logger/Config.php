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

class Config implements IConfig
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $handlers;

    /**
     * Config constructor.
     * @param $name
     * @param array $handlers
     */
    public function __construct($name, array $handlers=[])
    {
        $this->name     = $name;
        $this->handlers = $handlers;
    }

    /**
     * @return string
     */
    public static function getServiceName()
    {
        return 'logger';
    }
}