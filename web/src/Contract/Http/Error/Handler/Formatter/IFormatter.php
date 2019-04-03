<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/18/18
 * Time: 5:07 PM
 */

namespace Hellofresh\Contract\Http\Error\Handler\Formatter;

use League\BooBoo\Formatter\FormatterInterface;

interface IFormatter extends FormatterInterface
{
    public function format($e);

    public function setErrorLimit($limit);

    public function getErrorLimit();
}