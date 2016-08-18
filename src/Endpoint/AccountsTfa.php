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
