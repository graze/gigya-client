<?php

namespace Graze\Gigya\Endpoints;

use Exception;
use Graze\Gigya\Response\ResponseFactory;
use Graze\Gigya\Response\ResponseInterface;
use Graze\Gigya\Validation\GigyaResponseValidator;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

// use Psr\Http\Message\ResponseInterface; Guzzle v6

class Client
{
    const DOMAIN           = 'gigya.com';
    const CERTIFICATE_FILE = 'cacert.pem';

    /**
     * @var string
     */
    protected $dataCenter;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var ResponseFactory
     */
    protected $factory;

    /**
     * Collection of options to pass to Guzzle
     *
     * @var array
     */
    protected $options;

    /**
     * @param string $namespace
     * @param array  $params [:apiKey,:secret,:userKey]
     * @param string $dataCenter
     * @param array  $options
     */
    public function __construct($namespace, array $params, $dataCenter, array $options = [])
    {
        $this->namespace = $namespace;
        $this->params = $params;
        $this->dataCenter = $dataCenter;
        $this->client = new GuzzleClient();
        $this->factory = new ResponseFactory(new GigyaResponseValidator(
            isset($this->params['secret']) ? $this->params['secret'] : ''
        ));
        $this->certificate = __DIR__ . '/' . static::CERTIFICATE_FILE;
        $this->options = $options;
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
     * @param array  $params  Parameters to pass as part the of the uri
     * @param array  $options Extra options to be passed to guzzle. These will overwrite any existing options defined
     *                        by using addOption
     * @return ResponseInterface
     * @throws RequestException When an error is encountered
     */
    public function request($method, array $params = [], array $options = [])
    {
        $requestOptions = array_merge($this->options, $options);
        $requestOptions['query'] = array_merge($params, $this->params);
        $requestOptions['cert'] = $this->certificate;
        $response = $this->client->get($this->getEndpoint($method), $requestOptions);
        return $this->factory->getResponse($response);
    }

    /**
     * @param string $method
     * @param array  $arguments [params, options]
     * @return ResponseInterface
     * @throws RequestException
     */
    public function __call($method, $arguments)
    {
        return $this->request(
            $method,
            isset($arguments[0]) ? $arguments[0] : [],
            isset($arguments[1]) ? $arguments[1] : []
        );
    }
}
