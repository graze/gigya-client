<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Model\ModelInterface;

/**
 * Class GameMechanics
 *
 * @package Graze\Gigya\Endpoints
 *
 * @link     http://developers.gigya.com/display/GD/Game+Mechanics+REST
 *
 * @method ModelInterface deleteAction(array $params) @link
 *         http://developers.gigya.com/display/GD/gm.deleteAction+REST
 * @method ModelInterface deleteChallenge(array $params) @link
 *         http://developers.gigya.com/display/GD/gm.deleteChallenge+REST
 * @method ModelInterface deleteChallengeVariant(array $params) @link
 *         http://developers.gigya.com/display/GD/gm.deleteChallengeVariant+REST
 * @method ModelInterface getActionConfig(array $params) @link
 *         http://developers.gigya.com/display/GD/gm.getActionConfig+REST
 * @method ModelInterface getActionsLog(array $params) @link
 *         http://developers.gigya.com/display/GD/gm.getActionsLog+REST
 * @method ModelInterface getChallengeConfig(array $params) @link
 *         http://developers.gigya.com/display/GD/gm.getChallengeConfig+REST
 * @method ModelInterface getChallengeStatus(array $params) @link
 *         http://developers.gigya.com/display/GD/gm.getChallengeStatus+REST
 * @method ModelInterface getChallengeVariants(array $params) @link
 *         http://developers.gigya.com/display/GD/gm.getChallengeVariants+REST
 * @method ModelInterface getGlobalConfig(array $params) @link
 *         http://developers.gigya.com/display/GD/gm.getGlobalConfig+REST
 * @method ModelInterface getTopUsers(array $params) @link http://developers.gigya.com/display/GD/gm.getTopUsers+REST
 * @method ModelInterface notifyAction(array $params) @link
 *         http://developers.gigya.com/display/GD/gm.notifyAction+REST
 * @method ModelInterface redeemPoints(array $params) @link
 *         http://developers.gigya.com/display/GD/gm.redeemPoints+REST
 * @method ModelInterface resetLevelStatus(array $params) @link
 *         http://developers.gigya.com/display/GD/gm.resetLevelStatus+REST
 * @method ModelInterface setActionConfig(array $params) @link
 *         http://developers.gigya.com/display/GD/gm.setActionConfig+REST
 * @method ModelInterface setChallengeConfig(array $params) @link
 *         http://developers.gigya.com/display/GD/gm.setChallengeConfig+REST
 * @method ModelInterface setGlobalConfig(array $params) @link
 *         http://developers.gigya.com/display/GD/gm.setGlobalConfig+REST
 */
class GameMechanics extends NamespaceClient
{
    /**
     * @return string
     */
    public function getNamespace()
    {
        return static::NAMESPACE_GAME_MECHANICS;
    }
}
