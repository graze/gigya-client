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

use Graze\Gigya\Response\ResponseInterface;

/**
 * Class DataStore.
 *
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
