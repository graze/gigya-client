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

namespace Graze\Gigya\Response;

/**
 * Class ErrorCode.
 *
 * List of error codes taken from Gigya website
 *
 * @link    http://developers.gigya.com/display/GD/Response+Codes+and+Errors+REST
 */
class ErrorCode
{
    /** Success. */
    const OK = 0;
    /** Data is still being processed. Please query again for the response. */
    const DATA_PENDING = 100001;
    /** User canceled during the login process. */
    const ERROR_OPERATION_CANCELED = 200001;
    /** For reports purposes, when OK is returned but there were acceptable errors in the process. */
    const OK_WITH_ERRORS = 200008;
    /** The accounts have been linked successfully. */
    const ACCOUNTS_LINKED = 200009;
    /**
     * When a new account is created and the login identifier already exists, the server handles the conflict according
     * to the conflictHandling parameter. If saveProfileAndFail is passed, the profile data is saved, a registration
     * token is returned for account linking, and this error is returned.
     */
    const OK_WITH_ERROR_LOGIN_IDENTIFIER_EXISTS = 200010;
    /**
     * A method has been called that performs social login, but the registration process has not been finalized, or a
     * required field is missing from the user profile or data. See Accounts API Error Codes and Messages for more
     * information.
     */
    const ERROR_ACCOUNT_PENDING_REGISTRATION = 206001;
    /**
     * An account has already been verified and a user tries to log in with a loginID (usually an email address) whose
     * connection to the user has not been verified. See Accounts API Error Codes and Messages for more information.
     */
    const ERROR_ACCOUNT_PENDING_VERIFICATION = 206002;
    /**
     * The registration policy requires a loginID when a user uses Social Login to register to the site, but there are
     * no login identifiers or a password associated with the account. See Accounts API Error Codes and Messages for
     * more information.
     */
    const ERROR_ACCOUNT_MISSING_LOGIN_ID = 206003;
    /**
     * The API key is served by another data center. The error occurs when an API request is received at the wrong data
     * center.
     */
    const ERROR_INVALID_DATA_CENTER = 301001;
    /**
     * This error may be caused by various faults in the request. For example: wrong authentication header, non-secure
     * request that should be secured.
     */
    const ERROR_INVALID_REQUEST_FORMAT = 400001;
    /**
     * The method requires some parameters. One of the required parameters was not set in this method call. The error
     * message will include the name of the missing parameter.
     */
    const ERROR_MISSING_REQUIRED_PARAMETER = 400002;
    /**
     * A user tries to register or set the account information with an email or username that already exists in the
     * accounts database. See Accounts API Error Codes and Messages for more information.  Some possible response
     * messages are:.
     *
     * - If a chosen Username already exists the returned message is "Username already exists".
     * - If a chosen Email already exists the returned message is "Email already exists".
     */
    const ERROR_UNIQUE_IDENTIFIER_EXISTS = 400003;
    /** One of the parameters of this request has been set with a value which is not in the expected format. */
    const ERROR_INVALID_PARAMETER_FORMAT = 400004;
    /**
     * One of the parameters of this request has been set with a value which is not within the parameter's defined
     * value bounds. Please refer to the method's parameter table, and check the definition of valid values per
     * parameter. The error message will include the name of the specific parameter.
     */
    const ERROR_INVALID_PARAMETER_VALUE = 400006;
    /** Internal error. */
    const ERROR_DUPLICATE_VALUE = 400007;
    /** An OAuth2 error. See OAuth2 Error Response for more information. */
    const ERROR_INVALID_AUTHENTICATION_HEADER = 400008;
    /**
     * In accounts.register, whenever there is a validation error. Some possible response messages are:.
     *
     * - If input Password Doesn't meet policy requirements the returned message is "Password does not meet complexity
     * requirements".
     * - If input Password Confirmation does not match Password field the returned message is "Passwords
     * do not match".
     * - If any Invalid or unsupported input (all fields) is detected the returned message is "Invalid
     * %fieldname".
     */
    const ERROR_VALIDATION = 400009;
    /** An OAuth2 error. See OAuth2 Error Response for more information. */
    const ERROR_INVALID_REDIRECT_URI = 400011;
    /** An OAuth2 error. See OAuth2 Error Response for more information. */
    const ERROR_INVALID_RESPONSE_TYPE = 400012;
    /** An OAuth2 error. See OAuth2 Error Response for more information. */
    const ERROR_UNSUPPORTED_GRANT_TYPE = 400013;
    /** An OAuth2 error. See OAuth2 Error Response for more information. */
    const ERROR_INVALID_GRANT = 400014;
    /** An OAuth2 error. See OAuth2 Error Response for more information. */
    const ERROR_CODE_EXPIRED = 400015;
    /**
     * There was an attempt to write to fields from the client side. By default, only signed requests coming from the
     * server are allowed to write into the data fields.
     */
    const ERROR_SCHEMA_VALIDATION_FAILED = 400020;
    /**
     * The registration policy requires the user to pass a CAPTCHA test in order to register, and the CAPTCHA
     * verification has failed. See Accounts API Error Codes and Messages for more information.
     */
    const ERROR_CAPTCHA_VERIFICATION_FAILED = 400021;
    /** Used mostly for DS, where custom unique indexes are supported. */
    const ERROR_UNIQUE_INDEX_VALIDATION = 400022;
    /** When the internal type (string, int, date, etc) does not match the type of the provided value. */
    const ERROR_INVALID_TYPE_VALIDATION = 400023;
    /**
     * A validation error is returned whenever there is a data validation error regarding one of the following required
     * fields: username, password, secretQuestion, secretAnswer, email.
     */
    const ERROR_DYNAMIC_FIELDS_VALIDATION = 400024;
    /**
     * A write access error regarding one of the following required fields: username, password, secretQuestion,
     * secretAnswer, email.
     */
    const ERROR_WRITE_ACCESS_VALIDATION = 400025;
    /** Invalid regex format. */
    const ERROR_INVALID_FORMAT_VALIDATION = 400026;
    /**
     * A required value is missing or has an error in one of the following required fields: username, password,
     * secretQuestion, secretAnswer, email. Some possible response messages are:.
     *
     * - If CAPTCHA input is blank or incorrect the returned message is "The characters you entered didn't match the
     * word verification. Please try again".
     * - If a required field (all fields) is not complete the returned message is "This field is required".
     */
    const ERROR_REQUIRED_VALUE_VALIDATION = 400027;
    /** The email address provided has not been verified. */
    const ERROR_EMAIL_NOT_VERIFIED = 400028;
    /** An error was encountered while indexing the object.Internal error. */
    const ERROR_SCHEMA_CONFLICT = 400029;
    /**
     * This error is returned if a user logs in with a SAML provider, and multiple identities are not allowed, and a
     * call to socialize.addConnection or to socialize.removeConnection is attempted.
     */
    const ERROR_OPERATION_NOT_ALLOWED = 400030;
    /** With accounts.resetPassword when the provided credentials could not be verified. */
    const ERROR_SECURITY_VERIFICATION_FAILED = 400050;
    /** The provided API key is invalid. */
    const ERROR_INVALID_APIKEY_PARAMETER = 400093;
    /** The function is not supported by any of the currently connected providers. */
    const ERROR_NOT_SUPPORTED = 400096;
    /**
     * The user is attempting to access Gigya services from an insecure/unsupported browser. User should switch
     * browsers.
     */
    const ERROR_BROWSER_INSECURE = 400097;
    /** With accounts.tfa.importTFA or accounts.tfa.resetTFA when no such TFA provider exists. */
    const ERROR_NO_PROVIDERS = 400100;
    /** The containerID specified does not exist. */
    const ERROR_INVALID_CONTAINER_ID = 400103;
    /** User is not connected to the required network or to any network. */
    const ERROR_NOT_CONNECTED = 400106;
    /** The current domain does not match the domain configured for the api key. */
    const ERROR_INVALID_SITE_DOMAIN = 400120;
    /** An error originated from a provider. */
    const ERROR_PROVIDER_CONFIGURATION_ERROR = 400122;
    /**
     * Refers generally to any reached limits, either in Game Mechanics or in Comments. In Loyalty, when a user
     * performed more actions than the allowed daily cap (maximum actions per 24hs), or when a user performed actions
     * more frequently than the allowed frequency cap (minimum interval between consecutive actions). So the error can
     * be DailyCap exceeded or FreqCap exceeded. In commenting, the error is returned when a user reaches the daily
     * limit of new comments threads per stream.
     */
    const ERROR_LIMIT_REACHED = 400124;
    /** In comments/feed the spam cap was reached. */
    const ERROR_FREQUENCY_LIMIT_REACHED = 400125;
    /** In Gamification when the action is invalid. */
    const ERROR_INVALID_ACTION = 400126;
    /**
     * When the gamification method redeemPoints is called, and the user does not have enough points, the operation
     * fails and this error occurs.
     */
    const ERROR_INSUFFICIENT_POINTS_TO_REDEEM = 400127;
    /**
     * If Protect Against Account Harvesting policy is enabled and neither Email Validation nor CAPTCHA Validation
     * policies are enabled.
     */
    const ERROR_INVALID_POLICY_CONFIGURATION = 401000;
    /** When media items are not allowed for this category. */
    const ERROR_MEDIA_ITEMS_NOT_SUPPORTED = 401001;
    /**
     * If someone is trying to use Gigya to send an email with a URL that does not match any of the client's domains.
     */
    const ERROR_SUSPECTED_SPAM = 401010;
    /**
     * If accounts.login is attempted and the CAPTCHA threshold has been reached. The CAPTCHA threshold is set in the
     * site Policies (security.captcha.failedLoginThreshold policy).
     */
    const ERROR_LOGIN_FAILED_CAPTCHA_REQUIRED = 401020;
    /**
     * If accounts.login is attempted and the CAPTCHA threshold has been reached and the provided CAPTCHA text is
     * wrong. The CAPTCHA threshold is set in the site Policies (security.captcha.failedLoginThreshold policy).
     */
    const ERROR_LOGIN_FAILED_WRONG_CAPTCHA = 401021;
    /**
     * The password provided is not the correct, current password; however, it is a password previously associated with
     * the account. This may appear in the following cases:.
     *
     * - When accounts.login is attempted with a password that doesn't match the current password, but does match the
     *   previous one. In this case, the server will return this error with a message saying that "the password was
     *   modified on" the date when the current password was set.
     * - When accounts.resetPassword is attempted with a password that has previously been used with the account.
     *   In this case, the server will return this error with a message stating "invalid password: the provided
     *   password was already in use by this account".
     */
    const ERROR_OLD_PASSWORD_USED = 401030;
    /** You do not have permission to invoke the method. */
    const ERROR_FORBIDDEN = 403000;
    /**
     * The timestamp or expiration of the token used exceeded the allowed time window.
     * The most common cause for this error is when your server's clock is not accurately set. This causes a gap
     * between your time and Gigya's time. Even a gap of two minutes is enough to create this error. Please refer to
     * Signing requests for more details.
     */
    const ERROR_REQUEST_HAS_EXPIRED = 403002;
    /** The request is not signed with a valid signature. Please refer to Signing requests for more details. */
    const ERROR_INVALID_REQUEST_SIGNATURE = 403003;
    /**
     * The value of the nonce parameter that was passed with this request is not unique. Gigya requires that in each
     * REST API call the nonce string will be unique. If Gigya receives two API calls with the same nonce, the second
     * API call is rejected. Please refer to Signing requests for more details.
     */
    const ERROR_DUPLICATE_NONCE = 403004;
    /** The user ID that is passed is not valid for this site. */
    const ERROR_UNAUTHORIZED_USER = 403005;
    /** When sending the secret key in REST it has to be over HTTPS. */
    const ERROR_SECRET_SENT_OVER_HTTP = 403006;
    /**
     * Returned when a user lacks the necessary permissions to perform the requested action, or when the user's
     * credentials are not configured properly.
     */
    const ERROR_PERMISSION_DENIED = 403007;
    /** Cannot find an openId endpoint on the url or cannot find the username given for the openId login. */
    const ERROR_INVALID_OPENID_URL = 403008;
    /** The user session for this provider has expired. */
    const ERROR_PROVIDER_SESSION_EXPIRED = 403009;
    /** The request has an invalid secret key. */
    const ERROR_INVALID_SECRET = 403010;
    /** The session for this user has expired. */
    const ERROR_SESSION_HAS_EXPIRED = 403011;
    /** Requested user has no valid session. */
    const ERROR_NO_VALID_SESSION = 403012;
    /** We can't validate the request because the referrer header is missing. */
    const ERROR_MISSING_REQUEST_REFERRER = 403015;
    /** The user currently logged in to the requested provider is not the same as the one logged in to the site. */
    const ERROR_UNEXPECTED_PROVIDER_USER = 403017;
    /**
     * This operation needs a user permission and it was not requested. You may use the method
     * socialize.requestPermissions to request the user permission. After gaining user permission you may retry to
     * execute this operation.
     */
    const ERROR_PERMISSION_NOT_REQUESTED = 403022;
    /**
     * This operation needs a user permission and the user did not grant your application with the necessary
     * permission.
     */
    const ERROR_NO_USER_PERMISSION = 403023;
    /**
     * Limit reached: Status is a duplicate. This error occurs when a user shares content multiple times, and is
     * returned with the provider name, e.g., "provider" : "twitter".
     */
    const ERROR_PROVIDER_LIMIT_REACHED = 403024;
    /** Invalid OAuth2 token. Read more in Using Gigya's REST API in compliance with OAuth 2.0. */
    const ERROR_INVALID_TOKEN = 403025;
    /**
     * Returned from the accounts.isAvailableLoginID method, when Protect Against Account Harvesting policy is enabled.
     */
    const ERROR_UNAUTHORIZED_ACCESS_ERROR = 403026;
    /** Can't flag comment, it was approved by the moderator already. */
    const ERROR_APPROVED_BY_MODERATOR = 403031;
    /** The request is missing user credentials. */
    const ERROR_NO_USER_COOKIE = 403035;
    /** The relevant Gigya product is not enabled for this partner. */
    const ERROR_UNAUTHORIZED_PARTNER = 403036;
    /** Comments - Post denied when the user tried to review twice. */
    const ERROR_POST_DENIED = 403037;
    /** No login ticket in callback URL. */
    const ERROR_NO_LOGIN_TICKET = 403040;
    /**
     * A user has tried to log into an inactive account. See Accounts API Error Codes and Messages for more
     * information.
     */
    const ERROR_ACCOUNT_DISABLED = 403041;
    /**
     * A user passes an incorrect password or a login ID that doesn't exist in our accounts database. See Accounts API
     * Error Codes and Messages for more information.
     */
    const ERROR_INVALID_LOGIN_ID = 403042;
    /**
     * The username/email address provided by the user exists in the database but is associated with a different user.
     * See Accounts API Error Codes and Messages for more information.
     */
    const ERROR_LOGIN_IDENTIFIER_EXISTS = 403043;
    /**
     * A user under the age of 13 has tried to log in. For COPPA compliance (Children's Online Privacy Protection Act).
     * Please refer to the Age Limit section in the Policies guide.
     */
    const ERROR_UNDERAGE_USER = 403044;
    /** If Registration-as-a-Service (RaaS) is enabled for your site, but the storage size has not been configured. */
    const ERROR_INVALID_SITE_CONFIGURATION_ERROR = 403045;
    /**
     * There is no user with that username or email. In the "Forgot Password" screen of the Screen-sets tool, this
     * error is returned if a user fills in an email of a user that doesn't exist.
     */
    const ERROR_LOGIN_ID_DOES_NOT_EXIST = 403047;
    /** The daily API call limit has been reached. */
    const ERROR_API_RATE_LIMIT_EXCEEDED = 403048;
    /**
     * When accounts.login is attempted and the password change interval has passed since the last password change. The
     * interval is set in the site Policies (security.passwordChangeInterval policy).
     */
    const ERROR_PENDING_PASSWORD_CHANGE = 403100;
    /**
     * When accounts.login, accounts.socialLogin, accounts.finalizeRegistration, socialize.notifyLogin, or
     * socialize.login is called and the policy (in the site Policies) requires 2-factor authentication, and the device
     * is not already in the verified device list for the account. The first time the method is called, the device
     * needs to be registered, and for the following calls, the device needs to be verified.
     */
    const ERROR_ACCOUNT_PENDING_TFA_VERIFICATION = 403101;
    /**
     * When accounts.login, accounts.socialLogin, accounts.finalizeRegistration, socialize.notifyLogin, or
     * socialize.login is called and the policy (in the site Policies) requires 2-factor authentication, and the device
     * is not already in the verified device list for the account. The first time the method is called, the device
     * needs to be registered, and for the following calls, the device needs to be verified.
     */
    const ERROR_ACCOUNT_PENDING_TFA_REGISTRATION = 403102;
    /**
     * When there is an attempt to deactivate a TFA provider for a user (with accounts.tfa.deactivateProvider) or to
     * register a user (with accounts.tfa.initTFA) and the user did not login through the device in the last few
     * minutes.
     */
    const ERROR_ACCOUNT_PENDING_RECENT_LOGIN = 403110;
    /**
     * When accounts.login is attempted and the account is locked out or the originating IP is locked out. This occurs
     * after a set number of failed login attempts. The number is set in the site Policies -
     * security.accountLockout.failedLoginThreshold policy and security.ipLockout.hourlyFailedLoginThreshold policy.
     */
    const ERROR_ACCOUNT_TEMPORARILY_LOCKED_OUT = 403120;
    /** When the client performs an operation that is redundant. */
    const ERROR_REDUNDANT_OPERATION = 403200;
    /** When the provided app ID is different from the one configured for the site. */
    const ERROR_INVALID_APPLICATION_ID = 403201;
    /** In the comments server: category not found, in accounts: email verification failed. */
    const ERROR_NOT_FOUND = 404000;
    /** The friend user ID provided is not a friend for the current user. */
    const ERROR_FRIEND_NOT_FOUND = 404001;
    /** Comments - Category not found. */
    const ERROR_CATEGORY_NOT_FOUND = 404002;
    /** Caused by an invalid UID. */
    const ERROR_UID_NOT_FOUND = 404003;
    /** An embed.ly 404 error message returned when the URL is invalid. */
    const ERROR_INVALID_URL = 404004;
    /** Internal for Gigya JavaScript API. */
    const ERROR_INVALID_API_METHOD = 405001;
    /** When attempting to connect to a provider that is already connected or to link to an already linked account. */
    const ERROR_IDENTITY_EXISTS = 409001;
    /**
     * When calling accounts.getProfilePhoto, accounts.publishProfilePhoto or accounts.uploadProfilePhoto. The user
     * photo requested does not exist or the photo provided is not valid.
     */
    const ERROR_MISSING_USER_PHOTO = 409010;
    /**
     * There was an attempt to set or retrieve information in a counter that the system cannot find. See
     * accounts.incrementCounters.
     */
    const ERROR_COUNTER_NOT_REGISTERED = 409011;
    /** See 3rd Party Cookies for information about using gmid tickets. */
    const ERROR_INVALID_GMID_TICKET = 409012;
    /** When a mapped attribute value for the providerUID cannot be retrieved. */
    const ERROR_SAML_MAPPED_ATTRIBUTE_NOT_FOUND = 409013;
    /** When the SAML certificate cannot be retrieved. */
    const ERROR_SAML_CERTIFICATE_NOT_FOUND = 409014;
    /** Resource is no longer available. */
    const ERROR_GONE = 410000;
    /** Comments plugin received a request that was too large. */
    const ERROR_REQUEST_ENTITY_TOO_LARGE = 413001;
    /** Comments plugin received a comment with too much text. */
    const ERROR_COMMENT_TEXT_TOO_LARGE = 413002;
    /** The data store object size is too large, it is limited to 512KB. */
    const ERROR_OBJECT_TOO_LARGE = 413003;
    /** The profile photo is too large. */
    const ERROR_PROFILE_PHOTO_TOO_LARGE = 413004;
    /** General security warning. */
    const ERROR_GENERAL_SECURITY_WARNING = 500000;
    /** General server error. */
    const ERROR_GENERAL_SERVER_ERROR = 500001;
    /** General error during the login process. */
    const ERROR_SERVER_LOGIN_ERROR = 500002;
    /** For multiple Data Centers (DCs) when no default application can be found. */
    const ERROR_DEFAULT_APPLICATION_CONFIGURATION = 500003;
    /** Error while migrating old Facebook session to new Graph API Facebook session. */
    const ERROR_SESSION_MIGRATION_ERROR = 500014;
    /** General error from the provider. */
    const ERROR_PROVIDER_ERROR = 500023;
    /** Various network errors, e.g., when JSONP request fails. */
    const ERROR_NETWORK_ERROR = 500026;
    /** General database error. */
    const ERROR_DATABASE_ERROR = 500028;
    /**
     * There is no definition of provider application for this site. Please refer to Opening External Applications to
     * learn how to define provider application.
     */
    const ERROR_NO_PROVIDER_APPLICATION = 500031;
    /** When there is no target environment in the config file. */
    const ERROR_INVALID_ENVIRONMENT_CONFIG = 500033;
    /** Internal error. */
    const ERROR_ERROR_DURING_BACKEND_OPERATION = 500034;
    /** Client-side error. */
    const ERROR_TIMEOUT = 504001;
    /** A timeout that was defined in the request is out. */
    const ERROR_REQUEST_TIMEOUT = 504002;

    /**
     * @var array [CODE => [Name, Description]]
     */
    private static $errors = [
        self::OK                                      => ['OK ', 'Success.'],
        self::DATA_PENDING                            => [
            'Data pending',
            'Data is still being processed. Please query again for the response.',
        ],
        self::ERROR_OPERATION_CANCELED                => [
            'Operation canceled',
            'User canceled during the login process.',
        ],
        self::OK_WITH_ERRORS                          => [
            'OK with errors',
            'For reports purposes, when OK is returned but there were acceptable errors in the process.',
        ],
        self::ACCOUNTS_LINKED                         => [
            'Accounts linked',
            'The accounts have been linked successfully.',
        ],
        self::OK_WITH_ERROR_LOGIN_IDENTIFIER_EXISTS   => [
            'OK with error login identifier exists',
            'When a new account is created and the login identifier already exists, the server handles the conflict according to the conflictHandling parameter. If saveProfileAndFail is passed, the profile data is saved, a registration token is returned for account linking, and this error is returned.',
        ],
        self::ERROR_ACCOUNT_PENDING_REGISTRATION      => [
            'Account pending registration',
            'A method has been called that performs social login, but the registration process has not been finalized, or a required field is missing from the user profile or data. See Accounts API Error Codes and Messages for more information.',
        ],
        self::ERROR_ACCOUNT_PENDING_VERIFICATION      => [
            'Account pending verification',
            'An account has already been verified and a user tries to log in with a loginID (usually an email address) whose connection to the user has not been verified. See Accounts API Error Codes and Messages for more information.',
        ],
        self::ERROR_ACCOUNT_MISSING_LOGIN_ID          => [
            'Account missing loginID',
            'The registration policy requires a loginID when a user uses Social Login to register to the site, but there are no login identifiers or a password associated with the account. See Accounts API Error Codes and Messages for more information.',
        ],
        self::ERROR_INVALID_DATA_CENTER               => [
            'Invalid data center',
            'The API key is served by another data center. The error occurs when an API request is received at the wrong data center.',
        ],
        self::ERROR_INVALID_REQUEST_FORMAT            => [
            'Invalid request format',
            'This error may be caused by various faults in the request. For example: wrong authentication header, non-secure request that should be secured.',
        ],
        self::ERROR_MISSING_REQUIRED_PARAMETER        => [
            'Missing required parameter',
            'The method requires some parameters. One of the required parameters was not set in this method call. The error message will include the name of the missing parameter.',
        ],
        self::ERROR_UNIQUE_IDENTIFIER_EXISTS          => [
            'Unique identifier exists',
            'A user tries to register or set the account information with an email or username that already exists in the accounts database. See Accounts API Error Codes and Messages for more information.  Some possible response messages are:
If a chosen Username already exists the returned message is "Username already exists".
If a chosen Email already exists the returned message is "Email already exists".',
        ],
        self::ERROR_INVALID_PARAMETER_FORMAT          => [
            'Invalid parameter format',
            'One of the parameters of this request has been set with a value which is not in the expected format.',
        ],
        self::ERROR_INVALID_PARAMETER_VALUE           => [
            'Invalid parameter value',
            'One of the parameters of this request has been set with a value which is not within the parameter\'s defined value bounds. Please refer to the method\'s parameter table, and check the definition of valid values per parameter. The error message will include the name of the specific parameter.',
        ],
        self::ERROR_DUPLICATE_VALUE                   => ['Duplicate value', 'Internal error.'],
        self::ERROR_INVALID_AUTHENTICATION_HEADER     => [
            'Invalid authentication header',
            'An OAuth2 error. See OAuth2 Error Response for more information.',
        ],
        self::ERROR_VALIDATION                        => [
            'Validation',
            'In accounts.register, whenever there is a validation error. Some possible response messages are:
If input Password Doesn\'t meet policy requirements the returned message is "Password does not meet complexity requirements".
If input Password Confirmation does not match Password field the returned message is "Passwords do not match".
If any Invalid or unsupported input (all fields) is detected the returned message is "Invalid %fieldname".',
        ],
        self::ERROR_INVALID_REDIRECT_URI              => [
            'Invalid redirect URI',
            'An OAuth2 error. See OAuth2 Error Response for more information.',
        ],
        self::ERROR_INVALID_RESPONSE_TYPE             => [
            'Invalid response type',
            'An OAuth2 error. See OAuth2 Error Response for more information.',
        ],
        self::ERROR_UNSUPPORTED_GRANT_TYPE            => [
            'Unsupported grant type',
            'An OAuth2 error. See OAuth2 Error Response for more information.',
        ],
        self::ERROR_INVALID_GRANT                     => [
            'Invalid grant',
            'An OAuth2 error. See OAuth2 Error Response for more information.',
        ],
        self::ERROR_CODE_EXPIRED                      => [
            'Code expired',
            'An OAuth2 error. See OAuth2 Error Response for more information.',
        ],
        self::ERROR_SCHEMA_VALIDATION_FAILED          => [
            'Schema validation failed',
            'There was an attempt to write to fields from the client side. By default, only signed requests coming from the server are allowed to write into the data fields.',
        ],
        self::ERROR_CAPTCHA_VERIFICATION_FAILED       => [
            'CAPTCHA verification failed',
            'The registration policy requires the user to pass a CAPTCHA test in order to register, and the CAPTCHA verification has failed. See Accounts API Error Codes and Messages for more information.',
        ],
        self::ERROR_UNIQUE_INDEX_VALIDATION           => [
            'Unique index validation ',
            'Used mostly for DS, where custom unique indexes are supported.',
        ],
        self::ERROR_INVALID_TYPE_VALIDATION           => [
            'Invalid type validation ',
            'When the internal type (string, int, date, etc) does not match the type of the provided value.',
        ],
        self::ERROR_DYNAMIC_FIELDS_VALIDATION         => [
            'Dynamic fields validation ',
            'A validation error is returned whenever there is a data validation error regarding one of the following required fields: username, password, secretQuestion, secretAnswer, email.',
        ],
        self::ERROR_WRITE_ACCESS_VALIDATION           => [
            'Write access validation',
            'A write access error regarding one of the following required fields: username, password, secretQuestion, secretAnswer, email.',
        ],
        self::ERROR_INVALID_FORMAT_VALIDATION         => ['Invalid format validation ', 'Invalid regex format.'],
        self::ERROR_REQUIRED_VALUE_VALIDATION         => [
            'Required value validation',
            'A required value is missing or has an error in one of the following required fields: username, password, secretQuestion, secretAnswer, email. Some possible response messages are:
If CAPTCHA input is blank or incorrect the returned message is "The characters you entered didn\'t match the word verification. Please try again".
If a required field (all fields) is not complete the returned message is "This field is required".',
        ],
        self::ERROR_EMAIL_NOT_VERIFIED                => [
            'Email not verified',
            'The email address provided has not been verified.',
        ],
        self::ERROR_SCHEMA_CONFLICT                   => [
            'Schema conflict',
            'An error was encountered while indexing the object.Internal error.',
        ],
        self::ERROR_OPERATION_NOT_ALLOWED             => [
            'Operation not allowed',
            'This error is returned if a user logs in with a SAML provider, and multiple identities are not allowed, and a call to socialize.addConnection or to socialize.removeConnection is attempted.',
        ],
        self::ERROR_SECURITY_VERIFICATION_FAILED      => [
            'Security verification failed ',
            'With accounts.resetPassword when the provided credentials could not be verified.',
        ],
        self::ERROR_INVALID_APIKEY_PARAMETER          => [
            'Invalid ApiKey parameter',
            'The provided API key is invalid.',
        ],
        self::ERROR_NOT_SUPPORTED                     => [
            'Not supported',
            'The function is not supported by any of the currently connected providers.',
        ],
        self::ERROR_BROWSER_INSECURE                  => [
            'Browser insecure',
            'The user is attempting to access Gigya services from an insecure/unsupported browser. User should switch browsers.',
        ],
        self::ERROR_NO_PROVIDERS                      => [
            'No providers',
            'With accounts.tfa.importTFA or accounts.tfa.resetTFA when no such TFA provider exists.',
        ],
        self::ERROR_INVALID_CONTAINER_ID              => [
            'Invalid containerID',
            'The containerID specified does not exist.',
        ],
        self::ERROR_NOT_CONNECTED                     => [
            'Not connected',
            'User is not connected to the required network or to any network.',
        ],
        self::ERROR_INVALID_SITE_DOMAIN               => [
            'Invalid site domain',
            'The current domain does not match the domain configured for the api key.',
        ],
        self::ERROR_PROVIDER_CONFIGURATION_ERROR      => [
            'Provider configuration error',
            'An error originated from a provider.',
        ],
        self::ERROR_LIMIT_REACHED                     => [
            'Limit reached',
            'Refers generally to any reached limits, either in Game Mechanics or in Comments. In Loyalty, when a user performed more actions than the allowed daily cap (maximum actions per 24hs), or when a user performed actions more frequently than the allowed frequency cap (minimum interval between consecutive actions). So the error can be DailyCap exceeded or FreqCap exceeded. In commenting, the error is returned when a user reaches the daily limit of new comments threads per stream.',
        ],
        self::ERROR_FREQUENCY_LIMIT_REACHED           => [
            'Frequency limit reached',
            'In comments/feed the spam cap was reached.',
        ],
        self::ERROR_INVALID_ACTION                    => [
            'Invalid action',
            'In Gamification when the action is invalid.',
        ],
        self::ERROR_INSUFFICIENT_POINTS_TO_REDEEM     => [
            'Insufficient points to redeem',
            'When the gamification method redeemPoints is called, and the user does not have enough points, the operation fails and this error occurs.',
        ],
        self::ERROR_INVALID_POLICY_CONFIGURATION      => [
            'Invalid policy configuration',
            'If Protect Against Account Harvesting policy is enabled and neither Email Validation nor CAPTCHA Validation policies are enabled.',
        ],
        self::ERROR_MEDIA_ITEMS_NOT_SUPPORTED         => [
            'Media items not supported',
            'When media items are not allowed for this category.',
        ],
        self::ERROR_SUSPECTED_SPAM                    => [
            'Suspected spam',
            'If someone is trying to use Gigya to send an email with a URL that does not match any of the client\'s domains.',
        ],
        self::ERROR_LOGIN_FAILED_CAPTCHA_REQUIRED     => [
            'Login Failed Captcha Required',
            'If accounts.login is attempted and the CAPTCHA threshold has been reached. The CAPTCHA threshold is set in the site Policies (security.captcha.failedLoginThreshold policy).',
        ],
        self::ERROR_LOGIN_FAILED_WRONG_CAPTCHA        => [
            'Login Failed Wrong Captcha',
            'If accounts.login is attempted and the CAPTCHA threshold has been reached and the provided CAPTCHA text is wrong. The CAPTCHA threshold is set in the site Policies (security.captcha.failedLoginThreshold policy).',
        ],
        self::ERROR_OLD_PASSWORD_USED                 => [
            'Old password used',
            'The password provided is not the correct, current password; however, it is a password previously associated with the account. This may appear in the following cases:
When accounts.login is attempted with a password that doesn\'t match the current password, but does match the previous one. In this case, the server will return this error with a message saying that "the password was modified on" the date when the current password was set.
When accounts.resetPassword is attempted with a password that has previously been used with the account. In this case, the server will return this error with a message stating "invalid password: the provided password was already in use by this account".',
        ],
        self::ERROR_FORBIDDEN                         => [
            'Forbidden',
            'You do not have permission to invoke the method.',
        ],
        self::ERROR_REQUEST_HAS_EXPIRED               => [
            'Request has expired',
            'The timestamp or expiration of the token used exceeded the allowed time window.
The most common cause for this error is when your server\'s clock is not accurately set. This causes a gap between your time and Gigya\'s time. Even a gap of two minutes is enough to create this error.
Please refer to Signing requests for more details.',
        ],
        self::ERROR_INVALID_REQUEST_SIGNATURE         => [
            'Invalid request signature',
            'The request is not signed with a valid signature. Please refer to Signing requests for more details.',
        ],
        self::ERROR_DUPLICATE_NONCE                   => [
            'Duplicate nonce',
            'The value of the nonce parameter that was passed with this request is not unique. Gigya requires that in each REST API call the nonce string will be unique. If Gigya receives two API calls with the same nonce, the second API call is rejected. Please refer to Signing requests for more details.',
        ],
        self::ERROR_UNAUTHORIZED_USER                 => [
            'Unauthorized user',
            'The user ID that is passed is not valid for this site.',
        ],
        self::ERROR_SECRET_SENT_OVER_HTTP             => [
            'Secret Sent Over Http',
            'When sending the secret key in REST it has to be over HTTPS.',
        ],
        self::ERROR_PERMISSION_DENIED                 => [
            'Permission denied',
            'Returned when a user lacks the necessary permissions to perform the requested action, or when the user\'s credentials are not configured properly.',
        ],
        self::ERROR_INVALID_OPENID_URL                => [
            'Invalid OpenID Url',
            'Cannot find an openId endpoint on the url or cannot find the username given for the openId login.',
        ],
        self::ERROR_PROVIDER_SESSION_EXPIRED          => [
            'Provider session expired',
            'The user session for this provider has expired.',
        ],
        self::ERROR_INVALID_SECRET                    => [
            'Invalid Secret',
            'The request has an invalid secret key.',
        ],
        self::ERROR_SESSION_HAS_EXPIRED               => [
            'Session has expired',
            'The session for this user has expired.',
        ],
        self::ERROR_NO_VALID_SESSION                  => [
            'No valid session',
            'Requested user has no valid session.',
        ],
        self::ERROR_MISSING_REQUEST_REFERRER          => [
            'Missing request referrer',
            'We can\'t validate the request because the referrer header is missing.',
        ],
        self::ERROR_UNEXPECTED_PROVIDER_USER          => [
            'Unexpected provider user',
            'The user currently logged in to the requested provider is not the same as the one logged in to the site.',
        ],
        self::ERROR_PERMISSION_NOT_REQUESTED          => [
            'Permission not requested',
            'This operation needs a user permission and it was not requested. You may use the method socialize.requestPermissions to request the user permission. After gaining user permission you may retry to execute this operation.',
        ],
        self::ERROR_NO_USER_PERMISSION                => [
            'No user permission',
            'This operation needs a user permission and the user did not grant your application with the necessary permission.',
        ],
        self::ERROR_PROVIDER_LIMIT_REACHED            => [
            'Provider limit reached',
            'Limit reached: Status is a duplicate. This error occurs when a user shares content multiple times, and is returned with the provider name, e.g., "provider" : "twitter".',
        ],
        self::ERROR_INVALID_TOKEN                     => [
            'Invalid token',
            'Invalid OAuth2 token. Read more in Using Gigya\'s REST API in compliance with OAuth 2.0.',
        ],
        self::ERROR_UNAUTHORIZED_ACCESS_ERROR         => [
            'Unauthorized access error',
            'Returned from the accounts.isAvailableLoginID method, when Protect Against Account Harvesting policy is enabled.',
        ],
        self::ERROR_APPROVED_BY_MODERATOR             => [
            'Approved by moderator',
            'Can\'t flag comment, it was approved by the moderator already.',
        ],
        self::ERROR_NO_USER_COOKIE                    => [
            'No user cookie',
            'The request is missing user credentials.',
        ],
        self::ERROR_UNAUTHORIZED_PARTNER              => [
            'Unauthorized partner',
            'The relevant Gigya product is not enabled for this partner.',
        ],
        self::ERROR_POST_DENIED                       => [
            'Post denied',
            'Comments - Post denied when the user tried to review twice.',
        ],
        self::ERROR_NO_LOGIN_TICKET                   => ['No login ticket', 'No login ticket in callback URL.'],
        self::ERROR_ACCOUNT_DISABLED                  => [
            'Account disabled',
            'A user has tried to log into an inactive account. See Accounts API Error Codes and Messages for more information.',
        ],
        self::ERROR_INVALID_LOGIN_ID                  => [
            'Invalid loginID',
            'A user passes an incorrect password or a login ID that doesn\'t exist in our accounts database. See Accounts API Error Codes and Messages for more information.',
        ],
        self::ERROR_LOGIN_IDENTIFIER_EXISTS           => [
            'Login identifier exists',
            'The username/email address provided by the user exists in the database but is associated with a different user. See Accounts API Error Codes and Messages for more information.',
        ],
        self::ERROR_UNDERAGE_USER                     => [
            'Underage user',
            'A user under the age of 13 has tried to log in. For COPPA compliance (Children\'s Online Privacy Protection Act). Please refer to the Age Limit section in the Policies guide.',
        ],
        self::ERROR_INVALID_SITE_CONFIGURATION_ERROR  => [
            'Invalid site configuration error',
            'If Registration-as-a-Service (RaaS) is enabled for your site, but the storage size has not been configured.',
        ],
        self::ERROR_LOGIN_ID_DOES_NOT_EXIST           => [
            'Login ID does not exist',
            'There is no user with that username or email. In the "Forgot Password" screen of the Screen-sets tool, this error is returned if a user fills in an email of a user that doesn\'t exist.',
        ],
        self::ERROR_API_RATE_LIMIT_EXCEEDED           => [
            'API Rate Limit Exceeded',
            'The daily API call limit has been reached.',
        ],
        self::ERROR_PENDING_PASSWORD_CHANGE           => [
            'Pending password change',
            'When accounts.login is attempted and the password change interval has passed since the last password change. The interval is set in the site Policies (security.passwordChangeInterval policy).',
        ],
        self::ERROR_ACCOUNT_PENDING_TFA_VERIFICATION  => [
            'Account Pending TFA Verification',
            'When accounts.login, accounts.socialLogin, accounts.finalizeRegistration, socialize.notifyLogin, or socialize.login is called and the policy (in the site Policies) requires 2-factor authentication, and the device is not already in the verified device list for the account. The first time the method is called, the device needs to be registered, and for the following calls, the device needs to be verified.',
        ],
        self::ERROR_ACCOUNT_PENDING_TFA_REGISTRATION  => [
            'Account Pending TFA Registration',
            'When accounts.login, accounts.socialLogin, accounts.finalizeRegistration, socialize.notifyLogin, or socialize.login is called and the policy (in the site Policies) requires 2-factor authentication, and the device is not already in the verified device list for the account. The first time the method is called, the device needs to be registered, and for the following calls, the device needs to be verified.',
        ],
        self::ERROR_ACCOUNT_PENDING_RECENT_LOGIN      => [
            'Account Pending Recent Login ',
            'When there is an attempt to deactivate a TFA provider for a user (with accounts.tfa.deactivateProvider) or to register a user (with accounts.tfa.initTFA) and the user did not login through the device in the last few minutes.',
        ],
        self::ERROR_ACCOUNT_TEMPORARILY_LOCKED_OUT    => [
            'Account Temporarily Locked Out',
            'When accounts.login is attempted and the account is locked out or the originating IP is locked out. This occurs after a set number of failed login attempts. The number is set in the site Policies - security.accountLockout.failedLoginThreshold policy and security.ipLockout.hourlyFailedLoginThreshold policy.',
        ],
        self::ERROR_REDUNDANT_OPERATION               => [
            'Redundant operation',
            'When the client performs an operation that is redundant.',
        ],
        self::ERROR_INVALID_APPLICATION_ID            => [
            'Invalid application ID',
            'When the provided app ID is different from the one configured for the site.',
        ],
        self::ERROR_NOT_FOUND                         => [
            'Not found',
            'In the comments server: category not found, in accounts: email verification failed.',
        ],
        self::ERROR_FRIEND_NOT_FOUND                  => [
            'Friend not found',
            'The friend user ID provided is not a friend for the current user.',
        ],
        self::ERROR_CATEGORY_NOT_FOUND                => ['Category not found', 'Comments - Category not found.'],
        self::ERROR_UID_NOT_FOUND                     => ['UID not found', 'Caused by an invalid UID.'],
        self::ERROR_INVALID_URL                       => [
            'Invalid URL',
            'An embed.ly 404 error message returned when the URL is invalid.',
        ],
        self::ERROR_INVALID_API_METHOD                => [
            'Invalid API method',
            'Internal for Gigya JavaScript API.',
        ],
        self::ERROR_IDENTITY_EXISTS                   => [
            'Identity exists',
            'When attempting to connect to a provider that is already connected or to link to an already linked account.',
        ],
        self::ERROR_MISSING_USER_PHOTO                => [
            'Missing user photo',
            'When calling accounts.getProfilePhoto, accounts.publishProfilePhoto or accounts.uploadProfilePhoto. The user photo requested does not exist or the photo provided is not valid.',
        ],
        self::ERROR_COUNTER_NOT_REGISTERED            => [
            'Counter not registered',
            'There was an attempt to set or retrieve information in a counter that the system cannot find. See accounts.incrementCounters.',
        ],
        self::ERROR_INVALID_GMID_TICKET               => [
            'Invalid gmid ticket',
            'See 3rd Party Cookies for information about using gmid tickets.',
        ],
        self::ERROR_SAML_MAPPED_ATTRIBUTE_NOT_FOUND   => [
            'SAML mapped attribute not found',
            'When a mapped attribute value for the providerUID cannot be retrieved.',
        ],
        self::ERROR_SAML_CERTIFICATE_NOT_FOUND        => [
            'SAML certificate not found',
            'When the SAML certificate cannot be retrieved.',
        ],
        self::ERROR_GONE                              => ['Gone', 'Resource is no longer available.'],
        self::ERROR_REQUEST_ENTITY_TOO_LARGE          => [
            'Request entity too large',
            'Comments plugin received a request that was too large.',
        ],
        self::ERROR_COMMENT_TEXT_TOO_LARGE            => [
            'Comment text too large',
            'Comments plugin received a comment with too much text.',
        ],
        self::ERROR_OBJECT_TOO_LARGE                  => [
            'Object too large',
            'The data store object size is too large, it is limited to 512KB.',
        ],
        self::ERROR_PROFILE_PHOTO_TOO_LARGE           => [
            'Profile photo too large',
            'The profile photo is too large.',
        ],
        self::ERROR_GENERAL_SECURITY_WARNING          => ['General security warning ', 'General security warning.'],
        self::ERROR_GENERAL_SERVER_ERROR              => ['General Server error', 'General server error.'],
        self::ERROR_SERVER_LOGIN_ERROR                => [
            'Server login error',
            'General error during the login process.',
        ],
        self::ERROR_DEFAULT_APPLICATION_CONFIGURATION => [
            'Default application configuration',
            'For multiple Data Centers (DCs) when no default application can be found.',
        ],
        self::ERROR_SESSION_MIGRATION_ERROR           => [
            'Session migration error',
            'Error while migrating old Facebook session to new Graph API Facebook session.',
        ],
        self::ERROR_PROVIDER_ERROR                    => ['Provider error', 'General error from the provider.'],
        self::ERROR_NETWORK_ERROR                     => [
            'Network error',
            'Various network errors, e.g., when JSONP request fails.',
        ],
        self::ERROR_DATABASE_ERROR                    => ['Database error', 'General database error.'],
        self::ERROR_NO_PROVIDER_APPLICATION           => [
            'No provider application',
            'There is no definition of provider application for this site. Please refer to Opening External Applications to learn how to define provider application.',
        ],
        self::ERROR_INVALID_ENVIRONMENT_CONFIG        => [
            'Invalid environment config',
            'When there is no target environment in the config file.',
        ],
        self::ERROR_ERROR_DURING_BACKEND_OPERATION    => ['Error during backend operation', 'Internal error.'],
        self::ERROR_TIMEOUT                           => ['Timeout', 'Client-side error.'],
        self::ERROR_REQUEST_TIMEOUT                   => [
            'Request Timeout',
            'A timeout that was defined in the request is out.',
        ],
    ];

    /**
     * @param int $errorCode
     *
     * @return string|null
     */
    public static function getName($errorCode)
    {
        return array_key_exists($errorCode, static::$errors) ?
            static::$errors[$errorCode][0] :
            null;
    }

    /**
     * @param int $errorCode
     *
     * @return string|null
     */
    public static function getDescription($errorCode)
    {
        return array_key_exists($errorCode, static::$errors) ?
            static::$errors[$errorCode][1] :
            null;
    }
}
