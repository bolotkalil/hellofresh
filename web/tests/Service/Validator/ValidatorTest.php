<?php

namespace Hellofresh\Service\Validator;

use Hellofresh\Stub\Service\SimpleConfig;
use League\Container\Container;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/20/18
 * Time: 11:17 AM
 */


class ValidatorTest extends TestCase
{
    use \Hellofresh\Service\Validator\Helper;

    /**
     * @var Container
     */
    private $container;

    public function setUp()
    {
        $this->container = new Container();
        $service = new Service();
        $service->register($this->container, new Config('validatorName'));
    }

    public function testInstance()
    {
        $validatorService = $this->container->get(Config::getServiceName());
        $this->assertInstanceOf('Symfony\Component\Validator\Validator\RecursiveValidator', $validatorService);
    }

    public function testHelper()
    {
        $validator = $this->getValidatorService();
        $violations = $validator->validate('Hellofresh', array(
            new Length(array('min' => 12)),
            new NotBlank(),
        ));
        $this->assertContains('This value is too short', $violations[0]->getMessage());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWithWrongConfig()
    {
        $service = new Service();
        $service->register($this->container, new SimpleConfig());
    }


    /**
     * Returns container.
     *
     * @return \League\Container\Container
     */
    protected function getContainer()
    {
        return $this->container;
    }
}
