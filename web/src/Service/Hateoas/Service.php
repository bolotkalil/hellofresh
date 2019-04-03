<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 1:50 PM
 */

namespace Hellofresh\Service\Hateoas;

use Hateoas\HateoasBuilder;
use Hateoas\UrlGenerator\CallableUrlGenerator;
use Hellofresh\Contract\Service\IConfig;
use Hellofresh\Contract\Service\IService;
use League\Container\Container;
use Psr\Container\ContainerInterface;

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
            throw new \InvalidArgumentException('Hateoas wrong config object');
        }

        $hateoas = HateoasBuilder::create();

        $hateoas->setDebug($config->debug);
        $hateoas->setUrlGenerator(null, new CallableUrlGenerator($config->urlGenerator));

        if (!$config->debug) {
            $hateoas->setCacheDir($config->cacheDir);
            $hateoas->addMetadataDir($config->metadataDir);
        }

        $container->add($config->getServiceName(), $hateoas->build());
    }
}