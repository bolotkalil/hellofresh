<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/19/18
 * Time: 12:28 PM
 */

namespace Hellofresh\Http\Error\Handler\Log;


use Hellofresh\Http\Error\Exception\BadRequest;
use PHPUnit\Framework\TestCase;

class TestLogger extends TestCase
{
    /**
     * @var \Monolog\Logger
     */
    private $monolog;

    /**
     * @var \Monolog\Handler\TestHandler
     */
    private $monologHandler;

    /**
     * @var Logger
     */
    private $loggerHandler;

    public function setUp()
    {
        $this->monolog = new \Monolog\Logger('test', [$this->monologHandler]);

        $this->monologHandler = new \Monolog\Handler\TestHandler();

        $this->loggerHandler = new Logger($this->monolog);
    }

    public function testException()
    {
        $this->assertFalse($this->monologHandler->hasCriticalRecords());

        $this->loggerHandler->handle(new \Exception('test exception'));

        $this->assertTrue($this->monologHandler->hasCriticalRecords());
    }

    public function testHellofreshException()
    {
        $this->assertFalse($this->monologHandler->hasCriticalRecords());

        $this->loggerHandler->handle(new BadRequest(9, ['a detail']));

        $this->assertTrue($this->monologHandler->hasCriticalRecords());
    }

    public function testErrorExceptionErrorLog()
    {
        $this->assertFalse($this->monologHandler->hasErrorRecords());

        $this->loggerHandler->handle(new \ErrorException('test exception', 0, E_ERROR));

        $this->assertTrue($this->monologHandler->hasErrorRecords());
    }

    public function testNoticeExceptionErrorLog()
    {
        $this->assertFalse($this->monologHandler->hasNoticeRecords());

        $this->loggerHandler->handle(new \ErrorException('test exception', 0, E_NOTICE));

        $this->assertTrue($this->monologHandler->hasNoticeRecords());
    }

    public function testWarningExceptionErrorLog()
    {
        $this->assertFalse($this->monologHandler->hasWarningRecords());

        $this->loggerHandler->handle(new \ErrorException('test exception', 0, E_WARNING));

        $this->assertTrue($this->monologHandler->hasWarningRecords());
    }

    public function testInfoExceptionErrorLog()
    {
        $this->assertFalse($this->monologHandler->hasInfoRecords());

        $this->loggerHandler->handle(new \ErrorException('test exception', 0, E_STRICT));

        $this->assertTrue($this->monologHandler->hasInfoRecords());
    }
}
