<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/17/18
 * Time: 2:15 PM
 */

namespace Hellofresh\Helper\Mime;

use Hellofresh\App;
use Hellofresh\Contract\Service\Helper;

trait Mime
{
    use Helper;

    /**
     * @param $mime
     * @return Structure
     */
    public function getMimeStructure($mime)
    {
        $apiVersion = $this->getContainer()->get(App::API_VERSION_ID);
        $vendor     = $this->getContainer()->get(App::VENDOR_ID);
        $format     = null;

        if (preg_match(
            '@application/vnd\.' . $vendor . '-v' . App::API_VER_REG_EXP . '\+(xml|json)@',
            $mime,
            $matches
        )) {
            list($mime, $apiVersion, $format) = $matches;
        } elseif (preg_match(
            '@application/vnd\.' . $vendor . '\+(xml|json).*?version=' . App::API_VER_REG_EXP . '@',
            $mime,
            $matches
        )) {
            list($mime, $format, $apiVersion) = $matches;
        } elseif (preg_match('@.*(application/xml).*@', $mime, $matches)) {
            $format = 'xml';
            $mime   = 'application/vnd.' . $vendor . '-v' . $apiVersion . '+xml';
        }  elseif (preg_match('@.*(application/json).*@', $mime, $matches)) {
            $format = 'json';
            $mime   = 'application/vnd.' . $vendor . '-v' . $apiVersion . '+json';
        }

        return new Structure($mime, $format, $apiVersion, $vendor);
    }

}