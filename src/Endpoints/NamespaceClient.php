<?php

namespace Graze\Gigya\Endpoints;

use Exception;
use Graze\Gigya\Model\ModelFactory;
use Graze\Gigya\Model\ModelInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

abstract class NamespaceClient
{
    const DOMAIN = 'gigya.com';

    const NAMESPACE_AUDIT            = 'audit';
    const NAMESPACE_ACCOUNTS         = 'accounts';
    const NAMESPACE_ACCOUNTS_TFA     = 'accounts.tfa';
    const NAMESPACE_SOCIALIZE        = 'socialize';
    const NAMESPACE_COMMENTS         = 'comments';
    const NAMESPACE_GAME_MECHANICS   = 'gameMechanics';
    const NAMESPACE_REPORTS          = 'reports';
    const NAMESPACE_DATA_STORE       = 'ds';
    const NAMESPACE_IDENTITY_STORAGE = 'ids';
    const NAMESPACE_FIDM             = 'fidm';
    const NAMESPACE_FIDM_SAML        = 'fidm.saml';
    const NAMESPACE_FIDM_SAML_IDP    = 'fidm.saml.idp';

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
     * @param array  $options [:apiKey,:secret,:userKey]
     * @param string $dataCenter
     */
    public function __construct(array $options, $dataCenter)
    {
        $this->options = $options;
        $this->dataCenter = $dataCenter;
        $this->client = new GuzzleClient();
        $this->factory = new ModelFactory();
    }

    /**
     * Get the top level namespace (for the hostname)
     *
     * @return string
     */
    abstract public function getNamespace();

    /**
     * Get the method namespace for each method call
     *
     * By default this is the hostname namespace
     *
     * @return mixed
     */
    public function getMethodNamespace()
    {
        return $this->getNamespace();
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
            $this->getNamespace(),
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
