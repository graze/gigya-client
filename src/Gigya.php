<?php

namespace Graze\Gigya;

use BadMethodCallException;
use Graze\Gigya\Auth\GigyaHttpsAuth;
use Graze\Gigya\Endpoints\Accounts;
use Graze\Gigya\Endpoints\Audit;
use Graze\Gigya\Endpoints\Client;
use Graze\Gigya\Endpoints\Comments;
use Graze\Gigya\Endpoints\DataStore;
use Graze\Gigya\Endpoints\GameMechanics;
use Graze\Gigya\Endpoints\IdentityStorage;
use Graze\Gigya\Endpoints\Reports;
use Graze\Gigya\Endpoints\Saml;
use Graze\Gigya\Endpoints\Socialize;

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
    protected $guzzleConfig = [];

    /**
     * @param string      $apiKey
     * @param string      $secretKey
     * @param string|null $dataCenter
     * @param string|null $userKey
     */
    public function __construct($apiKey, $secretKey, $dataCenter = null, $userKey = null)
    {
        $this->auth       = new GigyaHttpsAuth($apiKey, $secretKey, $userKey);
        $this->dataCenter = $dataCenter ?: self::DC_EU;
        $this->addOption('auth', 'gigya');
        $this->addOption('verify', __DIR__ . '/' . static::CERTIFICATE_FILE);
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
     * Set the configuration information to pass to Guzzle.
     *
     * @param array $config
     */
    public function setGuzzleConfig(array $config)
    {
        $this->guzzleConfig = $config;
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

        return new Client($method, $this->auth, $this->dataCenter, $this->guzzleConfig, $this->options);
    }

    /**
     * @return Accounts
     */
    public function accounts()
    {
        return new Accounts(
            static::NAMESPACE_ACCOUNTS,
            $this->auth,
            $this->dataCenter,
            $this->guzzleConfig,
            $this->options
        );
    }

    /**
     * @return Audit
     */
    public function audit()
    {
        return new Audit(static::NAMESPACE_AUDIT, $this->auth, $this->dataCenter, $this->guzzleConfig, $this->options);
    }

    /**
     * @return Socialize
     */
    public function socialize()
    {
        return new Socialize(
            static::NAMESPACE_SOCIALIZE,
            $this->auth,
            $this->dataCenter,
            $this->guzzleConfig,
            $this->options
        );
    }

    /**
     * @return Comments
     */
    public function comments()
    {
        return new Comments(
            static::NAMESPACE_COMMENTS,
            $this->auth,
            $this->dataCenter,
            $this->guzzleConfig,
            $this->options
        );
    }

    /**
     * @return GameMechanics
     */
    public function gameMechanics()
    {
        return new GameMechanics(
            static::NAMESPACE_GAME_MECHANICS,
            $this->auth,
            $this->dataCenter,
            $this->guzzleConfig,
            $this->options
        );
    }

    /**
     * @return Reports
     */
    public function reports()
    {
        return new Reports(
            static::NAMESPACE_REPORTS,
            $this->auth,
            $this->dataCenter,
            $this->guzzleConfig,
            $this->options
        );
    }

    /**
     * @return DataStore
     */
    public function dataStore()
    {
        return new DataStore(
            static::NAMESPACE_DATA_STORE,
            $this->auth,
            $this->dataCenter,
            $this->guzzleConfig,
            $this->options
        );
    }

    /**
     * @return IdentityStorage
     */
    public function identityStorage()
    {
        return new IdentityStorage(
            static::NAMESPACE_IDENTITY_STORAGE,
            $this->auth,
            $this->dataCenter,
            $this->guzzleConfig,
            $this->options
        );
    }

    /**
     * @return Saml
     */
    public function saml()
    {
        return new Saml(static::NAMESPACE_FIDM, $this->auth, $this->dataCenter, $this->guzzleConfig, $this->options);
    }
}
