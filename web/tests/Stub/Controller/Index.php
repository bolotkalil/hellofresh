<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 6:44 PM
 */

namespace Hellofresh\Stub\Controller;

use Hellofresh\Http\Controller\BaseController;
use Hellofresh\Http\Response\Ok;

class Index extends BaseController
{
    /**
     * @Hellofresh\Router\Annotation(method="GET", path="/foos/{id}", since=1.2, until=2.8)
     */
    public static function getFoo() {
        return new Ok("foo");
    }

    /**
     * @Hellofresh\Router\Annotation(method="POST", path="bars", since=0.5, until=0.7)
     */
    public static function postBar() {
        return new Ok("foo");
    }
}