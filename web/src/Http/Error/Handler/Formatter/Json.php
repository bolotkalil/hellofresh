<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/18/18
 * Time: 3:33 PM
 */

namespace Hellofresh\Http\Error\Handler\Formatter;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Stream\Stream;
use Hellofresh\App;
use Hellofresh\Config;
use Hellofresh\Contract\Http\Error\Handler\Formatter\AbstractFormatter;
use Hellofresh\Http\Error\Error;
use Hellofresh\Http\Error\ErrorDebug;
use Hellofresh\Http\Request\Request;
use Hellofresh\Service\Hateoas\Helper;

class Json extends AbstractFormatter
{
    use Helper;
    private $config;
    private $request;

    public function __construct(Config $config, Request $request = null)
    {
        $this->config  = $config;
        $this->request = $request;
    }

    public function format($exception)
    {
        $response = new Response();
        try {
            $response = $this->serialize(
                $this->config->isDebug() ? new ErrorDebug($exception) : new Error($exception),
                is_null($this->request) ? Request::fromGlobals() : $this->request,
                $response
            );
        } catch (\Exception $e) {
            $stream = Stream::factory(
                $this->getHateoasService()->getSerializer()->serialize(
                    $this->config->isDebug() ? new ErrorDebug($e) : new Error($e),
                    'json'
                )
            );
            $response = $response->withBody((new \GuzzleHttp\Psr7\Stream($stream->detach())));

            $vendor = $this->getContainer()->get(App::VENDOR_ID);
            $apiVersion = $this->getContainer()->get(App::API_VERSION_ID);
            $response = $response->withAddedHeader('Content-Type', 'application/vnd.' . $vendor . '-v' . $apiVersion . '+json');

        }
        $response = $response->withStatus(method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500);

        return $response->getBody()->getContents();
    }

    /**
     * Returns container.
     *
     * @return \League\Container\Container
     */
    protected function getContainer()
    {
        return $this->config->getContainer();
    }
}