<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Model\ModelInterface;

/**
 * Class Reports
 *
 * @package Graze\Gigya\Endpoints
 *
 * @link     http://developers.gigya.com/display/GD/Reports+REST
 *
 * @method ModelInterface getAccountsStats(array $params) @link
 *         http://developers.gigya.com/display/GD/reports.getAccountsStats+REST
 * @method ModelInterface getChatStats(array $params) @link
 *         http://developers.gigya.com/display/GD/reports.getChatStats+REST
 * @method ModelInterface getCommentsStats(array $params) @link
 *         http://developers.gigya.com/display/GD/reports.getCommentsStats+REST
 * @method ModelInterface getFeedStats(array $params) @link
 *         http://developers.gigya.com/display/GD/reports.getFeedStats+REST
 * @method ModelInterface getGMRedeemablePoints(array $params) @link
 *         http://developers.gigya.com/display/GD/reports.getGMRedeemablePoints+REST
 * @method ModelInterface getGMStats(array $params) @link
 *         http://developers.gigya.com/display/GD/reports.getGMStats+REST
 * @method ModelInterface getGMTopUsers(array $params) @link
 *         http://developers.gigya.com/display/GD/reports.getGMTopUsers+REST
 * @method ModelInterface getGMUserStats(array $params) @link
 *         http://developers.gigya.com/display/GD/reports.getGMUserStats+REST
 * @method ModelInterface getIRank(array $params) @link http://developers.gigya.com/display/GD/reports.getIRank+REST
 * @method ModelInterface getReactionsStats(array $params) @link
 *         http://developers.gigya.com/display/GD/reports.getReactionsStats+REST
 * @method ModelInterface getSocializeStats(array $params) @link
 *         http://developers.gigya.com/display/GD/reports.getSocializeStats+REST
 */
class Reports extends NamespaceClient
{
    /**
     * @return string
     */
    public function getNamespace()
    {
        return static::NAMESPACE_REPORTS;
    }
}
