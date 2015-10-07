<?php

namespace Graze\Gigya\Test\Unit\Response;

use Graze\Gigya\Response\ErrorCode;
use Graze\Gigya\Test\TestCase;

class ErrorCodeTest extends TestCase
{
    public function testGetName()
    {
        static::assertEquals('Session migration error', ErrorCode::getName(ErrorCode::ERROR_SESSION_MIGRATION_ERROR));
        static::assertEquals('Invalid Secret', ErrorCode::getName(ErrorCode::ERROR_INVALID_SECRET));
    }

    public function testGetNameWithUnknownErrorCodeWillReturnNull()
    {
        static::assertNull(ErrorCode::getName(3271863217367182));
    }

    public function testGetDescription()
    {
        static::assertEquals(
            'When accounts.login, accounts.socialLogin, accounts.finalizeRegistration, socialize.notifyLogin, or socialize.login is called and the policy (in the site Policies) requires 2-factor authentication, and the device is not already in the verified device list for the account. The first time the method is called, the device needs to be registered, and for the following calls, the device needs to be verified.',
            ErrorCode::getDescription(ErrorCode::ERROR_ACCOUNT_PENDING_TFA_VERIFICATION)
        );
        static::assertEquals(
            'If Protect Against Account Harvesting policy is enabled and neither Email Validation nor CAPTCHA Validation policies are enabled.',
            ErrorCode::getDescription(ErrorCode::ERROR_INVALID_POLICY_CONFIGURATION)
        );
    }

    public function testGetDescriptionWithUnknownErrorCodeWillReturnNull()
    {
        static::assertNull(ErrorCode::getDescription(3271863217367182));
    }
}
