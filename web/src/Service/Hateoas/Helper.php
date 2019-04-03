<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/15/18
 * Time: 1:43 PM
 */

namespace Hellofresh\Service\Hateoas;

use Exception;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Stream\Stream;
use Hellofresh\Helper\Mime\Mime;
use Hellofresh\Http\Error\Exception\NotAcceptable;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use Negotiation\Negotiator;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

trait Helper
{
    use Mime, \Hellofresh\Contract\Service\Helper;

    /**
     * @param mixed $content
     * @param ServerRequestInterface $request
     * @param Response $response
     * @throws \Exception
     * @return ResponseInterface
     */
    protected function serialize($content, ServerRequestInterface $request, Response $response)
    {
        $accept = (isset($request->getServerParams()['HTTP_ACCEPT']) ? $request->getServerParams()['HTTP_ACCEPT'] : '*/*');
        $structure = $this->getMimeStructure($accept);

        if (isset($structure->mime) && $structure->mime === '*/*') {
            $structure->mime = 'application/vnd.' . $structure->vendor .
                '+json; version=' . $structure->apiVersion;
            $structure->format = 'json';
        }

        if (in_array($structure->format, ['json', 'xml'])) {

            $response = $response->withHeader('Content-Type', $structure->mime);

            $stream = Stream::factory($this->getHateoasService()->serialize(
                $content,
                $structure->format,
                SerializationContext::create()->setVersion($structure->apiVersion)
            ));

            return $response->withBody((new \GuzzleHttp\Psr7\Stream($stream->detach())));
        }

        throw new NotAcceptable(0,[($accept ? $accept:'Undefined') . ' is not supported']);
    }

    /**
     * @param  string $type
     * @param  ServerRequestInterface $request
     * @throws Exception
     * @return mixed
     */
    protected function deserialize($type, ServerRequestInterface $request)
    {
        $structure = $this->getMimeStructure(isset($request->getServerParams()['HTTP_CONTENT_TYPE'])?$request->getServerParams()['HTTP_CONTENT_TYPE']:null);

        if (is_null($structure->format)) {
            throw new Exception("Unsupported format");
        }

        return $this->getHateoasService()->getSerializer()->deserialize(
            $request->getBody()->getContents(),
            $type,
            $structure->format,
            DeserializationContext::create()->setVersion($structure->apiVersion)
        );
    }

    /**
     * @return \Hateoas\Hateoas
     */
    protected function getHateoasService()
    {
        return $this->getContainer()->get(Config::getServiceName());
    }
}