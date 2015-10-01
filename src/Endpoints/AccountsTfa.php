<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Gigya;
use Graze\Gigya\Response\ResponseInterface;

/**
 * Class Accounts
 *
 * @package  Graze\Gigya\Endpoints
 *
 * @link     http://developers.gigya.com/display/GD/Accounts+REST
 *
 * @method ResponseInterface deactivateProvider(array $params = []) @link
 *         http://developers.gigya.com/display/GD/accounts.tfa.deactivateProvider+REST
 * @method ResponseInterface finalizeTFA(array $params = []) @link
 *         http://developers.gigya.com/display/GD/accounts.tfa.finalizeTFA+REST
 * @method ResponseInterface getCertificate(array $params = []) @link
 *         http://developers.gigya.com/display/GD/accounts.tfa.getCertificate+REST
 * @method ResponseInterface getProviders(array $params = []) @link
 *         http://developers.gigya.com/display/GD/accounts.tfa.getProviders+REST
 * @method ResponseInterface initTFA(array $params = []) @link
 *         http://developers.gigya.com/display/GD/accounts.tfa.initTFA+REST
 * @method ResponseInterface unregisterDevice(array $params = []) @link
 *         http://developers.gigya.com/display/GD/accounts.tfa.unregisterDevice+REST
 */
class AccountsTfa extends Client
{
    /**
     * {@inheritdoc}
     */
    public function getMethodNamespace()
    {
        return Gigya::NAMESPACE_ACCOUNTS_TFA;
    }
}
