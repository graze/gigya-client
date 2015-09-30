<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Model\ModelInterface;

/**
 * Class DataStore
 *
 * @package Graze\Gigya\Endpoints
 *
 * @link     http://developers.gigya.com/display/GD/Data+Store+REST
 *
 * @method ModelInterface delete(array $params) @link http://developers.gigya.com/display/GD/ds.delete+REST
 * @method ModelInterface get(array $params) @link http://developers.gigya.com/display/GD/ds.get+REST
 * @method ModelInterface getSchema(array $params) @link http://developers.gigya.com/display/GD/ds.getSchema+REST
 * @method ModelInterface search(array $params) @link http://developers.gigya.com/display/GD/ds.search+REST
 * @method ModelInterface setSchema(array $params) @link http://developers.gigya.com/display/GD/ds.setSchema+REST
 * @method ModelInterface store(array $params) @link http://developers.gigya.com/display/GD/ds.store+REST
 */
class DataStore extends Client
{}
