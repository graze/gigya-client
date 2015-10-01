<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Gigya;
use Graze\Gigya\Response\ResponseInterface;

/**
 * Class SamlIdp
 *
 * @package  Graze\Gigya\Endpoints
 *
 * @link     http://developers.gigya.com/display/GD/fidm.saml.idp+REST
 *
 * @method ResponseInterface delSP(array $params = []) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.idp.delSP+REST
 * @method ResponseInterface getConfig(array $params = []) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.idp.getConfig+REST
 * @method ResponseInterface getRegisteredSPs(array $params = []) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.idp.getRegisteredSPs+REST
 * @method ResponseInterface importSPMetadata(array $params = []) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.idp.importSPMetadata+REST
 * @method ResponseInterface registerSP(array $params = []) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.idp.registerSP+REST
 * @method ResponseInterface setConfig(array $params = []) @link
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
