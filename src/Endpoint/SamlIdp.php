<?php

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
     */
    public function getMethodNamespace()
    {
        return Gigya::NAMESPACE_FIDM_SAML_IDP;
    }
}
