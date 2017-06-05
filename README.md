# gigya-client

<img align="right" src="http://stuffpoint.com/family-guy/image/15298-family-guy-giggedy.gif" width="250" />

[![Latest Version on Packagist](https://img.shields.io/packagist/v/graze/gigya-client.svg?style=flat-square)](https://packagist.org/packages/graze/gigya-client)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/graze/gigya-client/master.svg?style=flat-square)](https://travis-ci.org/graze/gigya-client)
[![Total Downloads](https://img.shields.io/packagist/dt/graze/gigya-client.svg?style=flat-square)](https://packagist.org/packages/graze/gigya-client)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/graze/gigya-client.svg?style=flat-square)](https://scrutinizer-ci.com/g/graze/gigya-client/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/graze/gigya-client.svg?style=flat-square)](https://scrutinizer-ci.com/g/graze/gigya-client)

Client for Gigya's REST API

* Endpoint call hierarchy: `$gigya->accounts()->tfa()->getCertificate()`
* List of endpoints: `accounts`, `accounts->tfa`, `audit`, `socialize`, `comments`, `gameMechanics`, `reports`, `dataStore`, `identityStorage`, `saml`, `saml->idp`
* Populated classes with auto completion helpers for the available methods from Gigya
* Different authentication methods:
  * `gigya`: add `api_key` and `secret` to https web requests
  * `credentials`: uses `client_id` and `client_secret` for use with oauth2 token retrieval
  * `gigya-oauth2`: uses an automatically retrieved OAuth2 token
  * `custom`: use your own custom authentication (or use oauth2 with a provided token)

## Install

The simplest way to install the client is with composer and running:

```bash
$ composer require graze/gigya-client
```

## Usage

By Default the Gigya client uses `gigya` auth and appends the api_key and secret onto the query string when calling gigya over https.

```php
$gigya = new Gigya($key, $secret);

$response = $gigya->accounts()->getAccountInfo(['uid' => $uid]);
if ($response->getErrorCode() === ErrorCode::OK) {
    $account = $response->getData();
}
```

### OAuth 2

You can also use `oauth2` in server mode and retrieve information about all accounts

```php
$gigya = new Gigya($key, $secret, $region, $user, ['auth'=>'gigya-oauth2']);
$response = $gigya->accounts()->getAccountInfo(['uid' => $uid]);
$account = $response->getData();
```

#### Social OAuth 2

OAuth2 can also be used to retrieve information about a single account without knowledge of the `uid`.

```php
$grant = new ManualGrant();
$gigya = new Gigya($key, $secret, $region, null, ['auth' => 'oauth2-custom']);
$gigya->addHandler(OAuth2Subscriber::middleware($grant, 'oauth2-custom'));

$tokenResponse = $gigya->socialize()->getToken([
    'grant_type' => 'code',
    'authorization_code' => '<xxxxx>',
    'redirect_uri' => '<xxxxx>',
], ['auth' => 'credentials']);

$grant->setToken($tokenResponse->getData()->get('access_token'));

$response = $gigya->accounts()->getAccountInfo();
$account = $response->getData();
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```bash
$ make install
$ make test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email [security@graze.com](security@graze.com) instead of using the issue tracker.

## Credits

- [Harry Bragg](https://github.com/h-bragg)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
