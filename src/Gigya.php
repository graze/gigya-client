<?php

namespace Graze\Gigya;

use BadMethodCallException;
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

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $userKey;

    /**
     * Data Center ID to use
     *
     * - us1 - for the US datacenter
     * - eu1 - for the European datacenter
     * - au1 - for the Australian datacenter
     *
     * @var string (Default: eu1)
     */
    protected $dataCenter;

    /**
     * @param string $apiKey
     * @param string $secretKey
     * @param string|null $dataCenter
     * @param string|null $userKey
     */
    public function __construct($apiKey, $secretKey, $dataCenter = null, $userKey = null)
    {
        $this->options = [
            'apiKey' => $apiKey,
            'secret' => $secretKey
        ];
        if ($userKey) {
            $this->options['userKey'] = $userKey;
        }
        $this->dataCenter = $dataCenter ?: Gigya::DC_EU;
    }

    /**
     * @param string $method
     * @param array  $arguments
     * @return Client
     */
    public function __call($method, array $arguments)
    {
        if (count($arguments) > 0) {
            throw new BadMethodCallException("No Arguments should be supplied for Gigya call");
        }

        return new Client($method, $this->options, $this->dataCenter);
    }

    /**
     * @return Accounts
     */
    public function accounts()
    {
        return new Accounts(static::NAMESPACE_ACCOUNTS, $this->options, $this->dataCenter);
    }

    /**
     * @return Audit
     */
    public function audit()
    {
        return new Audit(static::NAMESPACE_AUDIT, $this->options, $this->dataCenter);
    }

    /**
     * @return Socialize
     */
    public function socialize()
    {
        return new Socialize(static::NAMESPACE_SOCIALIZE, $this->options, $this->dataCenter);
    }

    /**
     * @return Comments
     */
    public function comments()
    {
        return new Comments(static::NAMESPACE_COMMENTS, $this->options, $this->dataCenter);
    }

    /**
     * @return GameMechanics
     */
    public function gameMechanics()
    {
        return new GameMechanics(static::NAMESPACE_GAME_MECHANICS, $this->options, $this->dataCenter);
    }

    /**
     * @return Reports
     */
    public function reports()
    {
        return new Reports(static::NAMESPACE_REPORTS, $this->options, $this->dataCenter);
    }

    /**
     * @return DataStore
     */
    public function dataStore()
    {
        return new DataStore(static::NAMESPACE_DATA_STORE, $this->options, $this->dataCenter);
    }

    /**
     * @return IdentityStorage
     */
    public function identityStorage()
    {
        return new IdentityStorage(static::NAMESPACE_IDENTITY_STORAGE, $this->options, $this->dataCenter);
    }

    /**
     * @return Saml
     */
    public function saml()
    {
        return new Saml(static::NAMESPACE_FIDM, $this->options, $this->dataCenter);
    }
}
