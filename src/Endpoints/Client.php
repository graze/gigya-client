<?php

namespace Graze\Gigya\Endpoints;

use Exception;
use Graze\Gigya\Exceptions\UnknownResponseException;
use Graze\Gigya\Model\ModelFactory;
use Graze\Gigya\Model\ModelInterface;
use Graze\Gigya\SignatureValidation;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class Client
{
    const DOMAIN = 'gigya.com';

    /**
     * @var string (Default: eu1)
     */
    protected $dataCenter;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var ModelFactory
     */
    protected $factory;

    /**
     * @param string $namespace
     * @param array  $options [:apiKey,:secret,:userKey]
     * @param string $dataCenter
     */
    public function __construct($namespace, array $options, $dataCenter)
    {
        $this->namespace = $namespace;
        $this->options = $options;
        $this->dataCenter = $dataCenter;
        $this->client = new GuzzleClient();
        $this->factory = new ModelFactory();
    }

    /**
     * Get the method namespace for each method call
     *
     * Overload this to handle different method namespaces
     *
     * @return mixed
     */
    public function getMethodNamespace()
    {
        return $this->namespace;
    }

    /**
     * Get the endpoint for a method
     *
     * @param string $method
     * @return string
     * @throws Exception
     */
    public function getEndpoint($method)
    {
        return sprintf(
            'https://%s.%s.%s/%s.%s',
            $this->namespace,
            $this->dataCenter,
            static::DOMAIN,
            $this->getMethodNamespace(),
            $method
        );
    }

    /**
     * @param string $method
     * @param array  $arguments
     * @return ModelInterface
     * @throws Exception
     */
    public function request($method, $arguments)
    {
        try {
            $options['query'] = array_merge($this->options, $arguments);
            $response = $this->client->get($this->getEndpoint($method), $options);
            $this->validateResponse($response);
            return $this->factory->getModel($response);
        } catch (ClientException $e) {
            throw new Exception($e->getResponse()->getBody());
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response instanceof ResponseInterface) {
                throw new Exception($e->getResponse()->getBody());
            }
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param ResponseInterface $response
     * @return bool
     * @throws UnknownResponseException
     */
    private function validateResponse(ResponseInterface $response)
    {
        $data = json_decode($response->getBody(), true);
        if (!(is_array($data) && array_key_exists('statusCode', $data))) {
            throw new UnknownResponseException($response);
        }

        if ((array_key_exists('UID', $data)) &&
            (array_key_exists('UIDSignature', $data)) &&
            (array_key_exists('timestamp', $data))
        ) {
            $validator = new SignatureValidation();
            return $validator->assertUid(
                $data['UID'],
                $data['timestamp'],
                $this->options['secret'],
                $data['UIDSignature']
            );
        }

        return true;
    }

    /**
     * @param string $method
     * @param array  $arguments
     * @return ModelInterface
     * @throws Exception
     */
    public function __call($method, $arguments)
    {
        return $this->request($method, isset($arguments[0]) ? $arguments[0] : []);
    }
}
