<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Response\ResponseInterface;

/**
 * Class IdentityStorage.
 *
 *
 * @link     http://developers.gigya.com/display/GD/Identity+Storage+REST
 *
 * @method ResponseInterface deleteAccount(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ids.deleteAccount+REST
 * @method ResponseInterface getAccountInfo(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ids.getAccountInfo+REST
 * @method ResponseInterface getCounters(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ids.getCounters+REST
 * @method ResponseInterface getRegisteredCounters(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ids.getRegisteredCounters+REST
 * @method ResponseInterface getSchema(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ids.getSchema+REST
 * @method ResponseInterface importAccount(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ids.importAccount+REST
 * @method ResponseInterface incrementCounters(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ids.incrementCounters+REST
 * @method ResponseInterface registerCounters(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ids.registerCounters+REST
 * @method ResponseInterface search(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ids.search+REST
 * @method ResponseInterface setAccountInfo(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ids.setAccountInfo+REST
 * @method ResponseInterface setSchema(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ids.setSchema+REST
 * @method ResponseInterface unregisterCounters(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ids.unregisterCounters+REST
 */
class IdentityStorage extends Client
{
}
