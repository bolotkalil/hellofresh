<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/16/18
 * Time: 1:37 AM
 */

namespace Hellofresh\Stub\Controller;

use Hellofresh\Http\Response;
use Hellofresh\Http\Response\Ok;

class Simple
{
    /**
     * @return Ok
     */
    public static function getProcess()
    {
        return new Ok('Hello World from HelloFresh');
    }
}