<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/20/18
 * Time: 10:41 AM
 */

namespace Hellofresh\Service\Validator;

use Hellofresh\Contract\Service\IConfig;
use Hellofresh\Contract\Service\IService;
use League\Container\Container;
use Symfony\Component\Validator\Validation;

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
            throw new \InvalidArgumentException('Validator wrong config object');
        }

        $validator = Validation::createValidatorBuilder();
        if ($config->annotationMapping) {
            $validator->enableAnnotationMapping();
        }

        $container->add($config->getServiceName(), $validator->getValidator());
    }
}