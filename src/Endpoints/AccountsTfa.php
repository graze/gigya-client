<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Model\ModelInterface;

/**
 * Class Accounts
 *
 * @package Graze\Gigya\Endpoints
 *
 * @link     http://developers.gigya.com/display/GD/Accounts+REST
 *
 * @method ModelInterface deactivateProvider(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.tfa.deactivateProvider+REST
 * @method ModelInterface finalizeTFA(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.tfa.finalizeTFA+REST
 * @method ModelInterface getCertificate(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.tfa.getCertificate+REST
 * @method ModelInterface getProviders(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.tfa.getProviders+REST
 * @method ModelInterface initTFA(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.tfa.initTFA+REST
 * @method ModelInterface unregisterDevice(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.tfa.unregisterDevice+REST
 */
class AccountsTfa extends NamespaceClient
{
    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return static::NAMESPACE_ACCOUNTS;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethodNamespace()
    {
        return static::NAMESPACE_ACCOUNTS_TFA;
    }
}
