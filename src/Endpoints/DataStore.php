<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Response\ResponseInterface;

/**
 * Class DataStore
 *
 * @package  Graze\Gigya\Endpoints
 *
 * @link     http://developers.gigya.com/display/GD/Data+Store+REST
 *
 * @method ResponseInterface delete(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ds.delete+REST
 * @method ResponseInterface get(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ds.get+REST
 * @method ResponseInterface getSchema(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ds.getSchema+REST
 * @method ResponseInterface search(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ds.search+REST
 * @method ResponseInterface setSchema(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ds.setSchema+REST
 * @method ResponseInterface store(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/ds.store+REST
 */
class DataStore extends Client
{
}
