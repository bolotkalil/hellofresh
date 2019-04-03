<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 6:07 PM
 */

namespace Hellofresh\Router;

use PHPUnit\Framework\TestCase;
use Hellofresh\Router;

class AnnotationTest extends TestCase
{
    /**
     * @param string $method
     * @dataProvider methodProvider
     */
    public function testSuccessValidation($method)
    {
        $route = new Router\Annotation([
            'method' => $method,
            'path' => '/root',
        ]);

        $this->assertEquals($method, $route->method);
        $this->assertEquals('/root', $route->path);
    }

    public function methodProvider()
    {
        return [
            ['GET'], ['POST'], ['PUT'], ['PATCH'], ['OPTIONS'], ['DELETE'], ['HEAD']
        ];
    }

    public function testSince()
    {
        $route = new Router\Annotation([
            'method' => 'GET',
            'path' => '/root',
            'since' => '2.3'
        ]);

        $this->assertEquals('{version:(?:[2-9]\.[3-9])|(?:[3-9]\.\d)}', $route->version);
    }

    public function testUntil()
    {
        $route = new Router\Annotation([
            'method' => 'GET',
            'path' => '/root',
            'until' => '3.2'
        ]);

        $this->assertEquals('{version:(?:[0-3]\.[0-2])|(?:[0-2]\.\d)}', $route->version);
    }

    public function testSinceAndUntilWithOneVersionNumDiff()
    {
        $route = new Router\Annotation([
            'method' => 'GET',
            'path' => '/root',
            'since' => '2.3',
            'until' => '3.2'
        ]);

        $this->assertEquals('{version:(?:2\.[3-9])|(?:3\.[0-2])}', $route->version);
    }

    public function testSinceAndUntilWithMoreThanOneVersionNumDiff()
    {
        $route = new Router\Annotation([
            'method' => 'GET',
            'path' => '/root',
            'since' => '2.3',
            'until' => '5.2'
        ]);

        $this->assertEquals('{version:(?:2\.[3-9])|(?:5\.[0-2])|(?:[3-4]\.\d)}', $route->version);
    }

    public function testSinceAndUntilWithEqualFirstNum()
    {
        $route = new Router\Annotation([
            'method' => 'GET',
            'path' => '/root',
            'since' => '2.3',
            'until' => '2.7'
        ]);

        $this->assertEquals('{version:(?:2\.[3-7])}', $route->version);
    }

    /**
     * @expectedException \LogicException
     */
    public function testInvalidSinceAndUntil()
    {
        new Router\Annotation([
            'method' => 'GET',
            'path' => '/root',
            'since' => '2.3',
            'until' => '1.7'
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMethodMissingOnValidation()
    {
        new Router\Annotation(['path' => '/root']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMethodIsNotCorrectOnValidation()
    {
        new Router\Annotation(['method' => 'resi']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPathMissingOnValidation()
    {
        new Router\Annotation(['method' => 'POST']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidSinceVersionOnValidation()
    {
        new Router\Annotation(['method' => 'DELETE', 'path' => '/', 'since' => '1.0.1.0']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidUntilVersionOnValidation()
    {
        new Router\Annotation(['method' => 'DELETE', 'path' => '/', 'until' => '-5']);
    }
}