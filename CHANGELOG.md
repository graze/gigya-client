# Change Log

All Notable changes to `gigya-client` will be documented in this file

## 2.0 - 2017-10-17

### Changed

- [BC] Move all requests to use POST instead of GET to gigya.
  - Prevents email/password leakage and some people are having issues with credentials in query params
  - If you use any customer `Handlers` that assume GET request or look at query params then they will need to change

## 1.0 - 2016-12-02

### Changed

- Upgrade Guzzle from v5 to v6
- All Subscribers are now Guzzle Middleware
- Renamed `->addSubscriber` to `->addHandler`
- Renamed `->removeSubscriber` to `->removeHandler`

## 0.3 - 2016-04-02

### Added

- OAuth2 authentication

## 0.2 - 2015-10-20

### Changed

- Moved Subscribers and Validators to the Gigya object, call these directly
- Constructor of Gigya takes a configuration array with properties
  - modification to the order of arguments
- Authentication is done using a Guzzle subscriber and you can supply your own
- You can supply your own response factory for custom response handling

## 0.1 - 2015-10-03

### Added

- initial Release
