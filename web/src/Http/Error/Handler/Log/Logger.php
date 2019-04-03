<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/19/18
 * Time: 12:18 PM
 */

namespace Hellofresh\Http\Error\Handler\Log;

use Hellofresh\Http\Error\Exception\Exception;
use League\BooBoo\Handler\HandlerInterface;
use Psr\Log\LoggerInterface;

class Logger implements HandlerInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Log constructor.
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger=null)
    {
        if (!is_null($logger)) {
            $this->setLogger($logger);
        }
    }

    public function handle($exception)
    {
        if ($exception instanceof \ErrorException) {
            $this->handleErrorException($exception);

            return;
        }

        $this->logger->critical($this->buildLogMessage($exception));
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \ErrorException $exception
     *
     * @return bool
     */
    protected function handleErrorException(\ErrorException $exception)
    {
        switch ($exception->getSeverity()) {
            case E_ERROR:
            case E_RECOVERABLE_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_PARSE:
                $this->logger->error($this->buildLogMessage($exception));
                break;

            case E_WARNING:
            case E_USER_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
                $this->logger->warning($this->buildLogMessage($exception));
                break;

            case E_NOTICE:
            case E_USER_NOTICE:
                $this->logger->notice($this->buildLogMessage($exception));
                break;

            case E_STRICT:
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                $this->logger->info($this->buildLogMessage($exception));
                break;
        }

        return true;
    }

    /**
     * @param $exception
     *
     * @return string
     */
    protected function buildLogMessage($exception)
    {
        $message = $exception->getMessage() . "({$exception->getCode()})";

        if ($exception instanceof Exception && $exception->getDetails()) {
            $message .= ' Details :: ' . json_encode($exception->getDetails());
        }

        $message .= ' Stack trace :: ' . $exception->getTraceAsString();

        return $message;
    }
}