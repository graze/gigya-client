<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Response\ResponseInterface;

/**
 * Class GameMechanics
 *
 * @package  Graze\Gigya\Endpoints
 *
 * @link     http://developers.gigya.com/display/GD/Game+Mechanics+REST
 *
 * @method ResponseInterface deleteAction(array $params = []) @link
 *         http://developers.gigya.com/display/GD/gm.deleteAction+REST
 * @method ResponseInterface deleteChallenge(array $params = []) @link
 *         http://developers.gigya.com/display/GD/gm.deleteChallenge+REST
 * @method ResponseInterface deleteChallengeVariant(array $params = []) @link
 *         http://developers.gigya.com/display/GD/gm.deleteChallengeVariant+REST
 * @method ResponseInterface getActionConfig(array $params = []) @link
 *         http://developers.gigya.com/display/GD/gm.getActionConfig+REST
 * @method ResponseInterface getActionsLog(array $params = []) @link
 *         http://developers.gigya.com/display/GD/gm.getActionsLog+REST
 * @method ResponseInterface getChallengeConfig(array $params = []) @link
 *         http://developers.gigya.com/display/GD/gm.getChallengeConfig+REST
 * @method ResponseInterface getChallengeStatus(array $params = []) @link
 *         http://developers.gigya.com/display/GD/gm.getChallengeStatus+REST
 * @method ResponseInterface getChallengeVariants(array $params = []) @link
 *         http://developers.gigya.com/display/GD/gm.getChallengeVariants+REST
 * @method ResponseInterface getGlobalConfig(array $params = []) @link
 *         http://developers.gigya.com/display/GD/gm.getGlobalConfig+REST
 * @method ResponseInterface getTopUsers(array $params = []) @link
 *         http://developers.gigya.com/display/GD/gm.getTopUsers+REST
 * @method ResponseInterface notifyAction(array $params = []) @link
 *         http://developers.gigya.com/display/GD/gm.notifyAction+REST
 * @method ResponseInterface redeemPoints(array $params = []) @link
 *         http://developers.gigya.com/display/GD/gm.redeemPoints+REST
 * @method ResponseInterface resetLevelStatus(array $params = []) @link
 *         http://developers.gigya.com/display/GD/gm.resetLevelStatus+REST
 * @method ResponseInterface setActionConfig(array $params = []) @link
 *         http://developers.gigya.com/display/GD/gm.setActionConfig+REST
 * @method ResponseInterface setChallengeConfig(array $params = []) @link
 *         http://developers.gigya.com/display/GD/gm.setChallengeConfig+REST
 * @method ResponseInterface setGlobalConfig(array $params = []) @link
 *         http://developers.gigya.com/display/GD/gm.setGlobalConfig+REST
 */
class GameMechanics extends Client
{
}
