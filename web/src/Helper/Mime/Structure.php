<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/17/18
 * Time: 2:01 PM
 */

namespace Hellofresh\Helper\Mime;

class Structure
{
    /**
     * @var string
     */
    public $mime;

    /**
     * @var string
     */
    public $format;

    /**
     * @var string|integer
     */
    public $apiVersion;

    /**
     * @var string
     */
    public $vendor;

    /**
     * Structure constructor.
     * @param string $mime
     * @param string $format
     * @param string|integer $apiVersion
     * @param string $vendor
     */
    public function __construct($mime, $format, $apiVersion, $vendor)
    {
        $this->mime       = $mime;
        $this->format     = $format;
        $this->apiVersion = $apiVersion;
        $this->vendor     = $vendor;
    }
}