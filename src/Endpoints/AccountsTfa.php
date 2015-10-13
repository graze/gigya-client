<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Gigya;
use Graze\Gigya\Response\ResponseInterface;

/**
 * Class Accounts.
 *
 *
 * @link     http://developers.gigya.com/display/GD/Accounts+REST
 *
 * @method ResponseInterface deactivateProvider(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.tfa.deactivateProvider+REST
 * @method ResponseInterface finalizeTFA(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.tfa.finalizeTFA+REST
 * @method ResponseInterface getCertificate(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.tfa.getCertificate+REST
 * @method ResponseInterface getProviders(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.tfa.getProviders+REST
 * @method ResponseInterface initTFA(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.tfa.initTFA+REST
 * @method ResponseInterface unregisterDevice(array $params = [], array $options = []) @link
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
