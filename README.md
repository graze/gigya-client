# gigya-client

<img align="right" src="http://stuffpoint.com/family-guy/image/15298-family-guy-giggedy.gif" width="250" />

[![Latest Version on Packagist](https://img.shields.io/packagist/v/graze/gigya-client.svg?style=flat-square)](https://packagist.org/packages/graze/gigya-client)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/graze/gigya-client/master.svg?style=flat-square)](https://travis-ci.org/graze/gigya-client)
[![Total Downloads](https://img.shields.io/packagist/dt/graze/gigya-client.svg?style=flat-square)](https://packagist.org/packages/graze/gigya-client)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/graze/gigya-client.svg?style=flat-square)](https://scrutinizer-ci.com/g/graze/gigya-client/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/graze/gigya-client.svg?style=flat-square)](https://scrutinizer-ci.com/g/graze/gigya-client)

Client for Gigya's REST API

## Install

The simplest way to install the client is with composer and running:

```bash
$ composer require graze/gigya-client
```

## Usage

```php
$gigya = new Gigya($key, $secret);
$response = $gigya->accounts()->getAccountInfo(['uid' => $uid]);
$account = $response->getData();
```

**Endpoints**

 - `accounts`
   - `tfa`
 - `audit`
 - `socialize`
 - `comments`
 - `gameMechanics`
 - `reports`
 - `dataStore`
 - `identityStorage`
 - `saml`
   - `idp`

### OAuth 2

```php
$gigya = new Gigya($key, $secret, $region, $user, ['auth'=>'gigya-oauth2']);
$response = $gigya->accounts()->getAccountInfo(['uid' => $uid]);
$account = $response->getData();
```

#### Social OAuth 2

```php
$grant = new ManualGrant();
$subsciber = new OAuth2Subscriber($grant);
$gigya = new Gigya($key, $secret, $region, null, ['auth' => 'oauth2-custom']);

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
