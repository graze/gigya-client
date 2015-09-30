<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Gigya;
use Graze\Gigya\Model\ModelInterface;

/**
 * Class SamlIdp
 *
 * @package Graze\Gigya\Endpoints
 *
 * @link     http://developers.gigya.com/display/GD/fidm.saml.idp+REST
 *
 * @method ModelInterface delSP(array $params) @link http://developers.gigya.com/display/GD/fidm.saml.idp.delSP+REST
 * @method ModelInterface getConfig(array $params) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.idp.getConfig+REST
 * @method ModelInterface getRegisteredSPs(array $params) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.idp.getRegisteredSPs+REST
 * @method ModelInterface importSPMetadata(array $params) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.idp.importSPMetadata+REST
 * @method ModelInterface registerSP(array $params) @link
 *         http://developers.gigya.com/display/GD/fidm.saml.idp.registerSP+REST
 * @method ModelInterface setConfig(array $params) @link
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
