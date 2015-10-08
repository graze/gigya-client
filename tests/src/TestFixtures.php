<?php

namespace Graze\Gigya\Test;

class TestFixtures
{
    /**
     * @param string $name
     * @return string|null
     */
    public static function getFixture($name)
    {
        if (array_key_exists($name, static::$fixtures)) {
            return static::$fixtures[$name];
        } else {
            return null;
        }
    }

    private static $fixtures = [
        'accounts.getAccountInfo' => '{
  "UID": "_gid_30A3XVJciH95WEEnoRmfZS7ee3MY+lUAtpVxvUWNseU=",
  "UIDSignature": "HHPLo/TC7KobjnGB7JflcWvAXfg=",
  "signatureTimestamp": "1412516469",
  "loginProvider": "facebook",
  "isRegistered": true,
  "isActive": true,
  "isLockedOut": false,
  "isVerified": true,
  "iRank": 57.3400,
  "loginIDs": {
    "emails": [],
    "unverifiedEmails": []
  },
  "emails": {
    "verified": [
      "pinkray518@gmail.com"
    ],
    "unverified": []
  },
  "socialProviders": "facebook,site",
  "profile": {
    "firstName": "Pinky",
    "lastName": "Ray",
    "photoURL": "https://graph.facebook.com/v2.0/10204456750325770/picture?type=large",
    "thumbnailURL": "https://graph.facebook.com/v2.0/10204456750325770/picture?type=square",
    "birthYear": 1980,
    "birthMonth": 4,
    "birthDay": 22,
    "profileURL": "https://www.facebook.com/app_scoped_user_id/10204456750325770/",
    "city": "Athens, Greece",
    "gender": "m",
    "age": 34,
    "email": "pinkray518@gmail.com",
    "samlData": {}
  },
  "identities": [
    {
      "provider": "facebook",
      "providerUID": "10204456750325770",
      "isLoginIdentity": true,
      "photoURL": "https://graph.facebook.com/v2.0/10204456750325770/picture?type=large",
      "thumbnailURL": "https://graph.facebook.com/v2.0/10204456750325770/picture?type=square",
      "firstName": "Pinky",
      "lastName": "Ray",
      "gender": "m",
      "age": "34",
      "birthDay": "22",
      "birthMonth": "4",
      "birthYear": "1980",
      "email": "pinkray518@gmail.com",
      "city": "Athens, Greece",
      "profileURL": "https://www.facebook.com/app_scoped_user_id/10204456750325770/",
      "proxiedEmail": "",
      "allowsLogin": true,
      "isExpiredSession": false,
      "lastUpdated": "2014-10-05T13:35:14.039Z",
      "lastUpdatedTimestamp": 1412516114039,
      "oldestDataUpdated": "2014-10-05T13:35:13.421Z",
      "oldestDataUpdatedTimestamp": 1412516113421
    },
    {
      "provider": "site",
      "providerUID": "_gid_30A3XVJciH95WEEnoRmfZS7ee3MY+lUAtpVxvUWNseU=",
      "isLoginIdentity": false,
      "allowsLogin": false,
      "isExpiredSession": false,
      "lastUpdated": "2014-10-05T13:39:53.455Z",
      "lastUpdatedTimestamp": 1412516393455,
      "oldestDataUpdated": "2014-10-05T13:39:53.455Z",
      "oldestDataUpdatedTimestamp": 1412516393455
    }
  ],
  "data": {
    "hair": "blond"
  },
  "password": {},
  "tfaStatus": "forceOff",
  "created": "2014-09-27T23:47:41.527Z",
  "createdTimestamp": 1411861661527,
  "lastLogin": "2014-10-05T13:35:13.437Z",
  "lastLoginTimestamp": 1412516113437,
  "lastUpdated": "2014-10-05T13:39:53.455Z",
  "lastUpdatedTimestamp": 1412516393455,
  "oldestDataUpdated": "2014-10-05T13:35:13.421Z",
  "oldestDataUpdatedTimestamp": 1412516113421,
  "registered": "2014-09-27T23:47:41.59Z",
  "registeredTimestamp": 1411861661590,
  "verified": "2014-09-27T23:47:41.543Z",
  "verifiedTimestamp": 1411861661543,
  "regSource": "",
  "lastLoginLocation": {
    "country": "IL",
    "coordinates": {
      "lat": 31.5,
      "lon": 34.75
    }
  },
  "statusCode": 200,
  "errorCode": 0,
  "statusReason": "OK",
  "callId": "e6f891ac17f24810bee6eb533524a152",
  "time": "2015-03-22T11:42:25.943Z"
}',
        'accounts.search_simple'  => '{"results": [
    {"profile": {"email": "g1@gmail.com"} },
    {"profile": {"firstName": "George", "lastName": "Lucas", "email": "g2@gmail.com" } },
    {"profile": {"firstName": "Paris", "lastName": "Radisson" } },
    {"profile": {"firstName": "Barry", "lastName": "Ray", "email": "g4@gmail.com" } },
    {"profile": {"firstName": "Tina", "lastName": "Tuna", "email": "g5@gmail.com" } }  ],
"objectsCount": 5,
"totalCount": 1840,
"statusCode": 200,
"errorCode": 0,
"statusReason": "OK",
"callId": "123456",
"time": "2015-03-22T11:42:25.943Z"
}',
        'basic'                   => '{
"statusCode": 200,
"errorCode": 0,
"statusReason": "OK",
"callId": "123456",
"time": "2015-03-22T11:42:25.943Z"
}',
        'failure_403'             => '{
"errorCode": 403005,
"errorMessage": "Unauthorized user",
"errorDetails": "The user billyBob cannot login",
"statusCode": 403,
"statusReason": "Forbidden",
"callId": "d8b041336e354a789553830705203779",
"time": "2015-03-22T11:42:25.943Z"
}',
        'missing_field'           => '{
"errorCode": 403005,
"errorMessage": "Unauthorized user",
"errorDetails": "The user billyBob cannot login",
"statusCode": 403,
"callId": "d8b041336e354a789553830705203779",
"time": "2015-03-22T11:42:25.943Z"
}',
        'invalid_json'            => '{
"errorCode": 403005,
"errorMessage": "Unauthorized user",
"statusCode": 403,',
    ];
}
