<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Gigya;
use Graze\Gigya\Response\ResponseInterface;

/**
 * Class Accounts
 *
 * @package  Graze\Gigya\Endpoints
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
 */
class Accounts extends Client
{
    /**
     * @return AccountsTfa
     */
    public function tfa()
    {
        return new AccountsTfa(Gigya::NAMESPACE_ACCOUNTS, $this->auth, $this->dataCenter, $this->options);
    }
}
