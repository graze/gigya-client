<?php

namespace Graze\Gigya\Endpoints;

use Graze\Gigya\Model\ModelInterface;

/**
 * Class Accounts
 *
 * @package Graze\Gigya\Endpoints
 *
 * @link     http://developers.gigya.com/display/GD/Accounts+REST
 *
 * @method ModelInterface deleteAccount(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.deleteAccount+REST
 * @method ModelInterface deleteScreenSet(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.deleteScreenSet+REST
 * @method ModelInterface exchangeUIDSignature(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.exchangeUIDSignature+REST
 * @method ModelInterface finalizeRegistration(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.finalizeRegistration+REST
 * @method ModelInterface getAccountInfo(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.getAccountInfo+REST
 * @method ModelInterface getConflictingAccount(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.getConflictingAccount+REST
 * @method ModelInterface getCounters(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.getCounters+REST
 * @method ModelInterface getPolicies(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.getPolicies+REST
 * @method ModelInterface getRegisteredCounters(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.getRegisteredCounters+REST
 * @method ModelInterface getSchema(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.getSchema+REST
 * @method ModelInterface getScreenSets(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.getScreenSets+REST
 * @method ModelInterface importProfilePhoto(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.importProfilePhoto+REST
 * @method ModelInterface incrementCounters(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.incrementCounters+REST
 * @method ModelInterface initRegistration(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.initRegistration+REST
 * @method ModelInterface isAvailableLoginID(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.isAvailableLoginID+REST
 * @method ModelInterface linkAccounts(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.linkAccounts+REST
 * @method ModelInterface login(array $params) @link http://developers.gigya.com/display/GD/accounts.login+REST
 * @method ModelInterface logout(array $params) @link http://developers.gigya.com/display/GD/accounts.logout+REST
 * @method ModelInterface notifyLogin(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.notifyLogin+REST
 * @method ModelInterface publicProfilePhoto(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.publicProfilePhoto+REST
 * @method ModelInterface registerCounters(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.registerCounters+REST
 * @method ModelInterface register(array $params) @link http://developers.gigya.com/display/GD/accounts.register+REST
 * @method ModelInterface resendVerificationCode(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.resendVerificationCode+REST
 * @method ModelInterface resetPassword(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.resetPassword+REST
 * @method ModelInterface search(array $params) @link http://developers.gigya.com/display/GD/accounts.search+REST
 * @method ModelInterface setAccountInfo(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.setAccountInfo+REST
 * @method ModelInterface setPolicies(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.setPolicies+REST
 * @method ModelInterface setProfilePhoto(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.setProfilePhoto+REST
 * @method ModelInterface setSchema(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.setSchema+REST
 * @method ModelInterface setScreenSet(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.setScreenSet+REST
 * @method ModelInterface socialLogin(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.socialLogin+REST
 * @method ModelInterface unregisterCounters(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.unregisterCounters+REST
 * @method ModelInterface verifyLogin(array $params) @link
 *         http://developers.gigya.com/display/GD/accounts.verifyLogin+REST
 */
class Accounts extends Client
{
    /**
     * @return AccountsTfa
     */
    public function tfa()
    {
        return new AccountsTfa(Client::NAMESPACE_ACCOUNTS, $this->options, $this->dataCenter);
    }
}
