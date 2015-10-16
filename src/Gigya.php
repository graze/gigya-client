<?php

namespace Graze\Gigya;

use BadMethodCallException;
use Graze\Gigya\Auth\GigyaHttpsAuth;
use Graze\Gigya\Endpoint\Accounts;
use Graze\Gigya\Endpoint\Audit;
use Graze\Gigya\Endpoint\Client;
use Graze\Gigya\Endpoint\Comments;
use Graze\Gigya\Endpoint\DataStore;
use Graze\Gigya\Endpoint\GameMechanics;
use Graze\Gigya\Endpoint\IdentityStorage;
use Graze\Gigya\Endpoint\Reports;
use Graze\Gigya\Endpoint\Saml;
use Graze\Gigya\Endpoint\Socialize;
use Graze\Gigya\Response\ResponseFactoryInterface;
use Graze\Gigya\Validation\ResponseValidatorInterface;
use Graze\Gigya\Validation\Signature;
use Graze\Gigya\Validation\UidSignatureValidator;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Event\SubscriberInterface;

class Gigya
{
    const DC_EU = 'eu1';
    const DC_US = 'us1';
    const DC_AU = 'au1';

    const NAMESPACE_AUDIT            = 'audit';
    const NAMESPACE_ACCOUNTS         = 'accounts';
    const NAMESPACE_ACCOUNTS_TFA     = 'accounts.tfa';
    const NAMESPACE_SOCIALIZE        = 'socialize';
    const NAMESPACE_COMMENTS         = 'comments';
    const NAMESPACE_GAME_MECHANICS   = 'gm';
    const NAMESPACE_REPORTS          = 'reports';
    const NAMESPACE_DATA_STORE       = 'ds';
    const NAMESPACE_IDENTITY_STORAGE = 'ids';
    const NAMESPACE_FIDM             = 'fidm';
    const NAMESPACE_FIDM_SAML        = 'fidm.saml';
    const NAMESPACE_FIDM_SAML_IDP    = 'fidm.saml.idp';

    const CERTIFICATE_FILE = 'cacert.pem';

    /**
     * Data Center ID to use.
     *
     * - us1 - for the US datacenter
     * - eu1 - for the European datacenter
     * - au1 - for the Australian datacenter
     *
     * @var string (Default: eu1)
     */
    protected $dataCenter;

    /**
     * Collection of core options to be passed to each api request.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Configuration to pass to the constructor of guzzle.
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var ResponseValidatorInterface[]
     */
    protected $validators = [];

    /**
     * @var SubscriberInterface[]
     */
    protected $subscribers = [];

    /**
     * @var ResponseFactoryInterface
     */
    protected $factory = null;

    /**
     * @var ClientInterface
     */
    private $guzzle;

    /**
     * @param string      $apiKey
     * @param string      $secretKey
     * @param string|null $userKey
     * @param array       $config Gigya configuration:
     *                            - dataCenter <string> (Default: DC_EU) Data Center to use
     *                            - auth <string> (Default: gigya) Type of authentication, gigya (HttpsAuth) is the
     *                            default
     *                            - uidValidator <bool> (Default: true) Include Uid Signature Validation
     *                            - factory <object> (Default: null) A ResponseFactoryInterface to use, if none is
     *                            provided ResponseFactory will be used
     *                            - guzzle <array> (Default: []) A configuration to pass to guzzle if required
     *                            - options <array> (Default: []) A set of options to pass to each request
     */
    public function __construct($apiKey, $secretKey, $userKey = null, array $config = [])
    {
        $guzzleConfig = (isset($config['guzzle'])) ? $config['guzzle'] : [];
        $this->guzzle = new GuzzleClient($guzzleConfig);

        if (isset($config['options'])) {
            $this->addOptions($config['options']);
        }
        $this->addOption('verify', __DIR__ . '/' . static::CERTIFICATE_FILE);

        if (!isset($config['auth']) || $config['auth'] == 'gigya') {
            $this->addOption('auth', 'gigya');
            $this->addSubscriber(new GigyaHttpsAuth($apiKey, $secretKey, $userKey));
        } else {
            $this->addOption('auth', $config['auth']);
        }

        if (!isset($config['uidValidator']) || $config['uidValidator'] === true) {
            $this->addValidator(new UidSignatureValidator(new Signature(), $secretKey));
        }

        if (isset($config['factory'])) {
            $this->setFactory($config['factory']);
        }
        $this->dataCenter = (isset($config['dataCenter'])) ? $config['dataCenter'] : self::DC_EU;
    }

    /**
     * Add an option to be passed through to Guzzle for the request.
     *
     * N.B. This will overwrite any existing options apart from query and verify
     *
     * @param string $option
     * @param mixed  $value
     *
     * @return $this
     */
    public function addOption($option, $value)
    {
        $this->options[$option] = $value;

        return $this;
    }

    /**
     * Add a set of options as key value pairs. These will be passed to the Guzzle request.
     *
     * N.B. This will overwrite any existing options apart from query and verify
     *
     * @param array $options
     *
     * @return $this
     */
    public function addOptions(array $options)
    {
        foreach ($options as $option => $value) {
            $this->addOption($option, $value);
        }

        return $this;
    }

    /**
     * @param ResponseValidatorInterface $validator
     *
     * @return $this
     */
    public function addValidator(ResponseValidatorInterface $validator)
    {
        $this->validators[] = $validator;

        return $this;
    }

    /**
     * @param SubscriberInterface $subscriber
     *
     * @return $this
     */
    public function addSubscriber(SubscriberInterface $subscriber)
    {
        $this->guzzle->getEmitter()->attach($subscriber);

        return $this;
    }

    /**
     * @param ResponseFactoryInterface $factory
     *
     * @return $this
     */
    public function setFactory(ResponseFactoryInterface $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return Client
     */
    public function __call($method, array $arguments)
    {
        if (count($arguments) > 0) {
            throw new BadMethodCallException('No Arguments should be supplied for Gigya call');
        }

        return $this->clientFactory($method);
    }

    /**
     * @param string $namespace
     * @param string $className
     *
     * @return Client
     */
    private function clientFactory($namespace, $className = Client::class)
    {
        return new $className(
            $this->guzzle,
            $namespace,
            $this->dataCenter,
            $this->config,
            $this->options,
            $this->validators,
            $this->factory
        );
    }

    /**
     * @return Accounts
     */
    public function accounts()
    {
        return $this->clientFactory(static::NAMESPACE_ACCOUNTS, Accounts::class);
    }

    /**
     * @return Audit
     */
    public function audit()
    {
        return $this->clientFactory(static::NAMESPACE_AUDIT, Audit::class);
    }

    /**
     * @return Socialize
     */
    public function socialize()
    {
        return $this->clientFactory(static::NAMESPACE_SOCIALIZE, Socialize::class);
    }

    /**
     * @return Comments
     */
    public function comments()
    {
        return $this->clientFactory(static::NAMESPACE_COMMENTS, Comments::class);
    }

    /**
     * @return GameMechanics
     */
    public function gameMechanics()
    {
        return $this->clientFactory(static::NAMESPACE_GAME_MECHANICS, GameMechanics::class);
    }

    /**
     * @return Reports
     */
    public function reports()
    {
        return $this->clientFactory(static::NAMESPACE_REPORTS, Reports::class);
    }

    /**
     * @return DataStore
     */
    public function dataStore()
    {
        return $this->clientFactory(static::NAMESPACE_DATA_STORE, DataStore::class);
    }

    /**
     * @return IdentityStorage
     */
    public function identityStorage()
    {
        return $this->clientFactory(static::NAMESPACE_IDENTITY_STORAGE, IdentityStorage::class);
    }

    /**
     * @return Saml
     */
    public function saml()
    {
        return $this->clientFactory(static::NAMESPACE_FIDM, Saml::class);
    }
}
