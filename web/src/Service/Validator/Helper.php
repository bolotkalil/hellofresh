<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/20/18
 * Time: 10:44 AM
 */

namespace Hellofresh\Service\Validator;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait Helper
{
    use \Hellofresh\Contract\Service\Helper;

    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint|\Symfony\Component\Validator\Constraint[] $constraints
     * @param array|null $groups
     *
     * @return ConstraintViolationListInterface
     */
    protected function getErrors($value, $constraints = null, $groups = null)
    {
        /** @var ValidatorInterface $validator */
        $validator = $this->getContainer()->get(Config::getServiceName());
        return $validator->validate($value, $constraints, $groups);
    }

    /**
     * @param ConstraintViolationListInterface $constraintViolations
     * @param callable $formatter
     *
     * @return array
     */
    protected function getFormattedErrors(ConstraintViolationListInterface $constraintViolations,
                                       callable $formatter = null)
    {
        $errors = [];
        if (is_null($formatter)) {
            $formatter = function(ConstraintViolationInterface $constraintViolation) {
                return new Error($constraintViolation->getPropertyPath(), $constraintViolation->getMessage());
            };
        }
        /** @var ConstraintViolationInterface $constraintViolation */
        foreach ($constraintViolations as $constraintViolation) {
            $errors[] = $formatter($constraintViolation);
        }
        return $errors;
    }

    /**
     * @return \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    public function getValidatorService()
    {
        return $this->getContainer()->get(Config::getServiceName());
    }
}