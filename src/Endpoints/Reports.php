<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Response\ResponseInterface;

/**
 * Class Reports
 *
 * @package  Graze\Gigya\Endpoints
 *
 * @link     http://developers.gigya.com/display/GD/Reports+REST
 *
 * @method ResponseInterface getAccountsStats(array $params = []) @link
 *         http://developers.gigya.com/display/GD/reports.getAccountsStats+REST
 * @method ResponseInterface getChatStats(array $params = []) @link
 *         http://developers.gigya.com/display/GD/reports.getChatStats+REST
 * @method ResponseInterface getCommentsStats(array $params = []) @link
 *         http://developers.gigya.com/display/GD/reports.getCommentsStats+REST
 * @method ResponseInterface getFeedStats(array $params = []) @link
 *         http://developers.gigya.com/display/GD/reports.getFeedStats+REST
 * @method ResponseInterface getGMRedeemablePoints(array $params = []) @link
 *         http://developers.gigya.com/display/GD/reports.getGMRedeemablePoints+REST
 * @method ResponseInterface getGMStats(array $params = []) @link
 *         http://developers.gigya.com/display/GD/reports.getGMStats+REST
 * @method ResponseInterface getGMTopUsers(array $params = []) @link
 *         http://developers.gigya.com/display/GD/reports.getGMTopUsers+REST
 * @method ResponseInterface getGMUserStats(array $params = []) @link
 *         http://developers.gigya.com/display/GD/reports.getGMUserStats+REST
 * @method ResponseInterface getIRank(array $params = []) @link
 *         http://developers.gigya.com/display/GD/reports.getIRank+REST
 * @method ResponseInterface getReactionsStats(array $params = []) @link
 *         http://developers.gigya.com/display/GD/reports.getReactionsStats+REST
 * @method ResponseInterface getSocializeStats(array $params = []) @link
 *         http://developers.gigya.com/display/GD/reports.getSocializeStats+REST
 */
class Reports extends Client
{
}
