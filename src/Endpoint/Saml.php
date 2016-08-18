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
 * Class Saml.
 *
 *
 * @link   http://developers.gigya.com/display/GD/FIdM+SAML+REST
 *
 * @method ResponseInterface delIdP(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.delIdP+REST
 * @method ResponseInterface getConfig(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.getConfig+REST
 * @method ResponseInterface getRegisteredIdPs(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.getRegisteredIdPs+REST
 * @method ResponseInterface importIdPMetadata(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.importIdPMetadata+REST
 * @method ResponseInterface registerIdP(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.registerIdP+REST
 * @method ResponseInterface setConfig(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.setConfig+REST
 */
class Saml extends Client
{
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getMethodNamespace()
    {
        return Gigya::NAMESPACE_FIDM_SAML;
    }

    /**
     * @return SamlIdp
     */
    public function idp()
    {
        return $this->endpointFactory(SamlIdp::class);
    }
}
