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
 * Class Reports.
 *
 *
 * @link     http://developers.gigya.com/display/GD/Reports+REST
 *
 * @method ResponseInterface getAccountsStats(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/reports.getAccountsStats+REST
 * @method ResponseInterface getChatStats(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/reports.getChatStats+REST
 * @method ResponseInterface getCommentsStats(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/reports.getCommentsStats+REST
 * @method ResponseInterface getFeedStats(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/reports.getFeedStats+REST
 * @method ResponseInterface getGMRedeemablePoints(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/reports.getGMRedeemablePoints+REST
 * @method ResponseInterface getGMStats(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/reports.getGMStats+REST
 * @method ResponseInterface getGMTopUsers(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/reports.getGMTopUsers+REST
 * @method ResponseInterface getGMUserStats(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/reports.getGMUserStats+REST
 * @method ResponseInterface getIRank(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/reports.getIRank+REST
 * @method ResponseInterface getReactionsStats(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/reports.getReactionsStats+REST
 * @method ResponseInterface getSocializeStats(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/reports.getSocializeStats+REST
 */
class Reports extends Client
{
}
