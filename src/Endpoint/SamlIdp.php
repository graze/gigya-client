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
 * Class SamlIdp.
 *
 *
 * @link     http://developers.gigya.com/display/GD/fidm.saml.idp+REST
 *
 * @method ResponseInterface delSP(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.idp.delSP+REST
 * @method ResponseInterface getConfig(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.idp.getConfig+REST
 * @method ResponseInterface getRegisteredSPs(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.idp.getRegisteredSPs+REST
 * @method ResponseInterface importSPMetadata(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.idp.importSPMetadata+REST
 * @method ResponseInterface registerSP(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.idp.registerSP+REST
 * @method ResponseInterface setConfig(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.idp.setConfig+REST
 */
class SamlIdp extends Client
{
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getMethodNamespace()
    {
        return Gigya::NAMESPACE_FIDM_SAML_IDP;
    }
}
