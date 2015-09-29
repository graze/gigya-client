<?php

namespace Graze\Gigya;

use Graze\Gigya\Endpoints\Accounts;
use Graze\Gigya\Endpoints\Audit;
use Graze\Gigya\Endpoints\Comments;
use Graze\Gigya\Endpoints\DataStore;
use Graze\Gigya\Endpoints\GameMechanics;
use Graze\Gigya\Endpoints\IdentityStorage;
use Graze\Gigya\Endpoints\Reports;
use Graze\Gigya\Endpoints\Saml;
use Graze\Gigya\Endpoints\Socialize;
use InvalidArgumentException;

class Client
{
    const DC_EU = 'eu1';
    const DC_US = 'us1';
    const DC_AU = 'au1';

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
     * @param string       $apiKey
     * @param string       $secretKey
     * @param string       $userKey
     */
    public function __construct($apiKey, $secretKey, $userKey = null)
    {
        $this->options = [
            'apiKey' => $apiKey,
            'secret' => $secretKey
        ];
        if ($userKey) {
            $this->options['userKey'] = $userKey;
        }
        $this->dataCenter = static::DC_EU;
    }

    /**
     * @param $dataCenter
     * @return $this
     */
    public function setDataCenter($dataCenter)
    {
        if (!in_array($dataCenter, [static::DC_EU, static::DC_US, static::DC_AU])) {
            throw new InvalidArgumentException("Invalid DataCenter specified: $dataCenter");
        }
        $this->dataCenter = $dataCenter;
        return $this;
    }

    /**
     * @return Accounts
     */
    public function accounts()
    {
        return new Accounts($this->options, $this->dataCenter);
    }

    /**
     * @return Audit
     */
    public function audit()
    {
        return new Audit($this->options, $this->dataCenter);
    }

    /**
     * @return Socialize
     */
    public function socialize()
    {
        return new Socialize($this->options, $this->dataCenter);
    }

    /**
     * @return Comments
     */
    public function comments()
    {
        return new Comments($this->options, $this->dataCenter);
    }

    /**
     * @return GameMechanics
     */
    public function gameMechanics()
    {
        return new GameMechanics($this->options, $this->dataCenter);
    }

    /**
     * @return Reports
     */
    public function reports()
    {
        return new Reports($this->options, $this->dataCenter);
    }

    /**
     * @return DataStore
     */
    public function dataStore()
    {
        return new DataStore($this->options, $this->dataCenter);
    }

    /**
     * @return IdentityStorage
     */
    public function identityStorage()
    {
        return new IdentityStorage($this->options, $this->dataCenter);
    }

    /**
     * @return Saml
     */
    public function saml()
    {
        return new Saml($this->options, $this->dataCenter);
    }
}
