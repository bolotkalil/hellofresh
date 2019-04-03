<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/18/18
 * Time: 2:49 PM
 */

namespace Hellofresh\Http\Error;

use JMS\Serializer\Annotation;

class ErrorDebug extends Error
{
    /**
     * @var int
     * @Annotation\Type("integer")
     */
    protected $line;

    /**
     * @var string
     * @Annotation\Type("string")
     */
    protected $file;

    /**
     * @var string
     * @Annotation\Type("string")
     */
    protected $trace;

    /**
     * Error constructor.
     * @param object $e
     */
    public function __construct($e)
    {
        parent::__construct($e);

        $this->line  = $e->getLine();
        $this->file  = $e->getFile();
        $this->trace = $e->getTraceAsString();
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getTrace()
    {
        return $this->trace;
    }
}