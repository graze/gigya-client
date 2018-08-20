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
 * Class Accounts.
 *
 *
 * @link     http://developers.gigya.com/display/GD/Accounts+REST
 *
 * @method ResponseInterface deleteAccount(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.deleteAccount+REST
 * @method ResponseInterface deleteScreenSet(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.deleteScreenSet+REST
 * @method ResponseInterface exchangeUIDSignature(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.exchangeUIDSignature+REST
 * @method ResponseInterface finalizeRegistration(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.finalizeRegistration+REST
 * @method ResponseInterface getAccountInfo(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.getAccountInfo+REST
 * @method ResponseInterface getConflictingAccount(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.getConflictingAccount+REST
 * @method ResponseInterface getCounters(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.getCounters+REST
 * @method ResponseInterface getPolicies(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.getPolicies+REST
 * @method ResponseInterface getRegisteredCounters(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.getRegisteredCounters+REST
 * @method ResponseInterface getSchema(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.getSchema+REST
 * @method ResponseInterface getScreenSets(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.getScreenSets+REST
 * @method ResponseInterface importProfilePhoto(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.importProfilePhoto+REST
 * @method ResponseInterface incrementCounters(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.incrementCounters+REST
 * @method ResponseInterface initRegistration(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.initRegistration+REST
 * @method ResponseInterface isAvailableLoginID(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.isAvailableLoginID+REST
 * @method ResponseInterface linkAccounts(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.linkAccounts+REST
 * @method ResponseInterface login(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.login+REST
 * @method ResponseInterface logout(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.logout+REST
 * @method ResponseInterface notifyLogin(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.notifyLogin+REST
 * @method ResponseInterface publicProfilePhoto(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.publicProfilePhoto+REST
 * @method ResponseInterface registerCounters(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.registerCounters+REST
 * @method ResponseInterface register(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.register+REST
 * @method ResponseInterface resendVerificationCode(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.resendVerificationCode+REST
 * @method ResponseInterface resetPassword(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.resetPassword+REST
 * @method ResponseInterface search(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.search+REST
 * @method ResponseInterface setAccountInfo(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.setAccountInfo+REST
 * @method ResponseInterface setPassword(array $params = [], array $options = []) Undocumented call to set the hashed
 *         value of a password
 * @method ResponseInterface setPolicies(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.setPolicies+REST
 * @method ResponseInterface setProfilePhoto(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.setProfilePhoto+REST
 * @method ResponseInterface setSchema(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.setSchema+REST
 * @method ResponseInterface setScreenSet(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.setScreenSet+REST
 * @method ResponseInterface socialLogin(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.socialLogin+REST
 * @method ResponseInterface unregisterCounters(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.unregisterCounters+REST
 * @method ResponseInterface verifyLogin(array $params = [], array $options = []) @link
 *         http://developers.gigya.com/display/GD/accounts.verifyLogin+REST
 * @method ResponseInterface getJWT(array $params = [], array $options = []) @link
 *         https://developers.gigya.com/display/GD/accounts.getJWT+REST
 * @method ResponseInterface getJWTPublicKey(array $params = [], array $options = []) @link
 *         https://developers.gigya.com/display/GD/accounts.getJWTPublicKey+REST
 */
class Accounts extends Client
{
    /**
     * @return AccountsTfa
     */
    public function tfa()
    {
        return $this->endpointFactory(AccountsTfa::class);
    }
}
