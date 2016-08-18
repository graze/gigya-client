<?php
/**
 * This file is part of graze/gigya-client
 *
 * Copyright (c) 2016 Nature Delivered Ltd. <https://www.graze.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://github.com/graze/gigya-client/blob/master/LICENSE.md
 * @link    https://github.com/graze/gigya-client
 */

namespace Graze\Gigya\Endpoint;

use Exception;
use Graze\Gigya\Response\ResponseFactory;
use Graze\Gigya\Response\ResponseFactoryInterface;
use Graze\Gigya\Response\ResponseInterface;
use Graze\Gigya\Validation\ResponseValidatorInterface;
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
     * @var ResponseFactoryInterface
     */
    protected $factory;

    /**
     * Collection of options to pass to Guzzle.
     *
     * @var array
     */
    protected $options;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var GuzzleClient
     */
    protected $client;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var ResponseValidatorInterface[]
     */
    protected $validators = [];

    /**
     * @param GuzzleClient             $client
     * @param string                   $namespace
     * @param string                   $dataCenter
     * @param array                    $config     List of configuration settings for Guzzle
     * @param array                    $options    Options to pass to each request
     * @param array                    $validators Response validators
     * @param ResponseFactoryInterface $factory
     */
    public function __construct(
        GuzzleClient $client,
        $namespace,
        $dataCenter,
        array $config = [],
        array $options = [],
        array $validators = [],
        ResponseFactoryInterface $factory = null
    ) {
        $this->client     = $client;
        $this->namespace  = $namespace;
        $this->dataCenter = $dataCenter;
        $this->config     = $config;
        $this->options    = $options;
        $this->factory    = $factory ?: new ResponseFactory();
        array_map([$this, 'addValidator'], $validators);
    }

    /**
     * @param ResponseValidatorInterface $validator
     */
    public function addValidator(ResponseValidatorInterface $validator)
    {
        $this->validators[] = $validator;
    }

    /**
     * Get the method namespace for each method call.
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
     * Get the endpoint for a method.
     *
     * @param string $method
     *
     * @throws Exception
     *
     * @return string
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
     * @throws RequestException When an error is encountered
     *
     * @return ResponseInterface
     */
    public function request($method, array $params = [], array $options = [])
    {
        $requestOptions          = array_merge($this->options, $options);
        $requestOptions['query'] = $params;
        $guzzleResponse          = $this->client->get($this->getEndpoint($method), $requestOptions);
        $response                = $this->factory->getResponse($guzzleResponse);

        $this->assert($response);

        return $response;
    }

    /**
     * @param string $method
     * @param array  $arguments [params, options]
     *
     * @throws RequestException
     *
     * @return ResponseInterface
     */
    public function __call($method, array $arguments = [])
    {
        return $this->request(
            $method,
            isset($arguments[0]) ? $arguments[0] : [],
            isset($arguments[1]) ? $arguments[1] : []
        );
    }

    /**
     * @param string $className
     *
     * @return Client
     */
    protected function endpointFactory($className = self::class)
    {
        return new $className(
            $this->client,
            $this->namespace,
            $this->dataCenter,
            $this->config,
            $this->options,
            $this->validators,
            $this->factory
        );
    }

    /**
     * Throws exceptions if any errors are found.
     *
     * @param ResponseInterface $response
     *
     * @return void
     */
    public function assert(ResponseInterface $response)
    {
        foreach ($this->validators as $validator) {
            if ($validator->canValidate($response)) {
                $validator->assert($response);
            }
        }
    }
}
