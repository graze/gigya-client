<?php

namespace Graze\Gigya\Endpoints;

use Exception;
use Graze\Gigya\Auth\GigyaAuthInterface;
use Graze\Gigya\Response\ResponseFactory;
use Graze\Gigya\Response\ResponseInterface;
use Graze\Gigya\Validation\Signature;
use Graze\Gigya\Validation\UidSignatureValidator;
use Graze\Gigya\Validation\ValidGigyaResponseSubscriber;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

// use Psr\Http\Message\ResponseInterface; Guzzle v6

class Client
{
    const DOMAIN = 'gigya.com';

    /**
     * @var string
     */
    protected $dataCenter;

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
     * @var string
     */
    protected $namespace;

    /**
     * @var GigyaAuthInterface
     */
    protected $auth;

    /**
     * @var GuzzleClient
     */
    protected $client;

    /**
     * @var array
     */
    protected $guzzleConfig;

    /**
     * @param string             $namespace
     * @param GigyaAuthInterface $auth
     * @param string             $dataCenter
     * @param array              $guzzleConfig
     * @param array              $options
     */
    public function __construct(
        $namespace,
        GigyaAuthInterface $auth,
        $dataCenter,
        array $guzzleConfig = [],
        array $options = []
    ) {
        $this->namespace = $namespace;
        $this->auth = $auth;
        $this->dataCenter = $dataCenter;
        $this->guzzleConfig = $guzzleConfig;
        $this->options = $options;

        $this->client = new GuzzleClient($guzzleConfig);
        $this->client->getEmitter()->attach(new ValidGigyaResponseSubscriber());
        $this->client->getEmitter()->attach($auth);

        $this->factory = new ResponseFactory();
        $this->factory->addValidator(new UidSignatureValidator(
            new Signature(),
            $auth->getSecret()
        ));
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
     *
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
     *
     * @return ResponseInterface
     * @throws RequestException When an error is encountered
     */
    public function request($method, array $params = [], array $options = [])
    {
        $requestOptions = array_merge($this->options, $options);
        $requestOptions['query'] = $params;
        $response = $this->client->get($this->getEndpoint($method), $requestOptions);
        return $this->factory->getResponse($response);
    }

    /**
     * @param string $method
     * @param array  $arguments [params, options]
     *
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
