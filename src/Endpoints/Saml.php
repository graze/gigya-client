<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Gigya;
use Graze\Gigya\Response\ResponseInterface;

/**
 * Class Saml
 *
 * @package  Graze\Gigya\Endpoints
 *
 * @link     http://developers.gigya.com/display/GD/FIdM+SAML+REST
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
        return new SamlIdp($this->namespace, $this->params, $this->dataCenter, $this->options);
    }
}
