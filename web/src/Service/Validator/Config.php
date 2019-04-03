<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/20/18
 * Time: 10:40 AM
 */

namespace Hellofresh\Service\Validator;


use Hellofresh\Contract\Service\IConfig;

class Config implements IConfig
{

    /**
     * @var boolean
     */
    public $annotationMapping = true;

    /**
     * @param boolean $annotationMapping
     */
    public function __construct($annotationMapping=true)
    {
        $this->annotationMapping = $annotationMapping;
    }

    /**
     * @return string
     */
    public static function getServiceName()
    {
        return 'validator';
    }
}