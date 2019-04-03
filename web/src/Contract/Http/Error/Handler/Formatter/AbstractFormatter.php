<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/18/18
 * Time: 5:07 PM
 */

namespace Hellofresh\Contract\Http\Error\Handler\Formatter;


abstract class AbstractFormatter implements IFormatter
{
    /**
     * @var int
     */
    protected $errorLimit = E_ALL;

    /**
     * @param $severity
     * @return string
     */
    protected function determineSeverityTextValue($severity)
    {
        switch ($severity) {
            case E_ERROR:
            case E_USER_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
                $severity = 'Fatal Error';
                break;

            case E_PARSE:
                $severity = 'Parse Error';
                break;

            case E_WARNING:
            case E_USER_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
                $severity = 'Warning';
                break;

            case E_NOTICE:
            case E_USER_NOTICE:
                $severity = 'Notice';
                break;

            case E_STRICT:
                $severity = 'Strict Standards';
                break;

            case E_RECOVERABLE_ERROR:
                $severity = 'Catchable Error';
                break;

            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                $severity = 'Deprecated';
                break;

            default:
                $severity = 'Unknown Error';
        }
        return $severity;
    }

    /**
     * @param int $limit
     */
    public function setErrorLimit($limit = E_ALL)
    {
        $this->errorLimit = $limit;
    }

    /**
     * @return int
     */
    public function getErrorLimit()
    {
        return $this->errorLimit;
    }
}