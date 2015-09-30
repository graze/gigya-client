<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Model\ModelInterface;

/**
 * Class IdentityStorage
 *
 * @package Graze\Gigya\Endpoints
 *
 * @link     http://developers.gigya.com/display/GD/Identity+Storage+REST
 *
 * @method ModelInterface deleteAccount(array $params) @link
 *         http://developers.gigya.com/display/GD/ids.deleteAccount+REST
 * @method ModelInterface getAccountInfo(array $params) @link
 *         http://developers.gigya.com/display/GD/ids.getAccountInfo+REST
 * @method ModelInterface getCounters(array $params) @link
 *         http://developers.gigya.com/display/GD/ids.getCounters+REST
 * @method ModelInterface getRegisteredCounters(array $params) @link
 *         http://developers.gigya.com/display/GD/ids.getRegisteredCounters+REST
 * @method ModelInterface getSchema(array $params) @link http://developers.gigya.com/display/GD/ids.getSchema+REST
 * @method ModelInterface importAccount(array $params) @link
 *         http://developers.gigya.com/display/GD/ids.importAccount+REST
 * @method ModelInterface incrementCounters(array $params) @link
 *         http://developers.gigya.com/display/GD/ids.incrementCounters+REST
 * @method ModelInterface registerCounters(array $params) @link
 *         http://developers.gigya.com/display/GD/ids.registerCounters+REST
 * @method ModelInterface search(array $params) @link http://developers.gigya.com/display/GD/ids.search+REST
 * @method ModelInterface setAccountInfo(array $params) @link
 *         http://developers.gigya.com/display/GD/ids.setAccountInfo+REST
 * @method ModelInterface setSchema(array $params) @link http://developers.gigya.com/display/GD/ids.setSchema+REST
 * @method ModelInterface unregisterCounters(array $params) @link
 *         http://developers.gigya.com/display/GD/ids.unregisterCounters+REST
 */
class IdentityStorage extends Client
{}
