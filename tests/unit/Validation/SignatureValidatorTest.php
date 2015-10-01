<?php

namespace Graze\Gigya\Test\Unit\Validation;

use Graze\Gigya\Test\TestCase;
use Graze\Gigya\Validation\SignatureValidator;

class SignatureValidatorTest extends TestCase
{
    const UID        = "g8f7d6gd7s6t23t4gekfs";
    const FRIEND_UID = "890fd7tg97d08sbg";
    const SECRET     = "8j9h0g-opko;dk]=id0f[sjo";

    /**
     * @var SignatureValidator
     */
    private $validator;

    public function setUp()
    {
        $this->validator = new SignatureValidator();
    }

    /**
     * @dataProvider signatureTests
     * @param string $base
     * @param string $secret
     * @param string $expected
     */
    public function testCalculateSignature($base, $secret, $expected)
    {
        static::assertEquals($expected, $this->validator->calculateSignature($base, $secret));
    }

    /**
     * @param int $time
     * @return array
     */
    private function getUidSignature($time)
    {
        $uid = static::UID;
        $secret = static::SECRET;
        $base = $time . "_" . $uid;
        $expected = $this->validator->calculateSignature($base, $secret);
        return [$uid, $secret, $expected];
    }

    public function testUidSignature()
    {
        $time = time();
        list($uid, $secret, $expected) = $this->getUidSignature($time);
        static::assertTrue($this->validator->validateUid($uid, $time, $secret, $expected));
    }

    public function testInvalidUidSignature()
    {
        $time = time();
        list($uid, $secret, $expected) = $this->getUidSignature($time);
        static::assertFalse($this->validator->validateUid($uid, $time, $secret, "invalidSignature"));
    }

    public function testUidSignatureAccepts180secondDifferencesToNowInTimestamp()
    {
        $time = time() - 180;
        list($uid, $secret, $expected) = $this->getUidSignature($time);
        static::assertTrue($this->validator->validateUid($uid, $time, $secret, $expected));

        $time = time() + 180;
        list($uid, $secret, $expected) = $this->getUidSignature($time);
        static::assertTrue($this->validator->validateUid($uid, $time, $secret, $expected));
    }

    public function testUidSignatureDoesNotAccept181secondDifferentToNowInTimestamp()
    {
        $time = time() - 181;
        list($uid, $secret, $expected) = $this->getUidSignature($time);
        static::assertFalse($this->validator->validateUid($uid, $time, $secret, $expected));

        $time = time() + 181;
        list($uid, $secret, $expected) = $this->getUidSignature($time);
        static::assertFalse($this->validator->validateUid($uid, $time, $secret, $expected));
    }

    public function testAssertUidWillNotThrowExceptionForValidSignature()
    {
        $time = time();
        list($uid, $secret, $expected) = $this->getUidSignature($time);
        static::assertTrue($this->validator->assertUid($uid, $time, $secret, $expected));
    }

    public function testAssertUidWillThrowExceptionForInvalidSignature()
    {
        $time = time();
        list($uid, $secret, $expected) = $this->getUidSignature($time);
        static::setExpectedException(
            'Graze\Gigya\Exceptions\InvalidUidSignatureException',
            sprintf("The supplied signature for uid: %s does not match.\n Expected '%s'\n Supplied 'invalidSignature'",
                static::UID,
                $expected
            )
        );

        $this->validator->assertUid($uid, $time, $secret, "invalidSignature");
    }

    public function testAssertUidWillThrowExceptionForTimestampOutOfRange()
    {
        $time = time() - 181;
        list($uid, $secret, $expected) = $this->getUidSignature($time);

        static::setExpectedException(
            'Graze\Gigya\Exceptions\InvalidTimestampException',
            sprintf("The supplied timestamp: %d is more than 180 seconds different to now: %d",
                $time, time()
            )
        );

        $this->validator->assertUid($uid, $time, $secret, $expected);
    }

    /**
     * @param int $time
     * @return array
     */
    private function getFriendSignature($time)
    {
        $uid = static::UID;
        $friendUid = static::FRIEND_UID;
        $secret = static::SECRET;
        $base = $time . "_" . $friendUid . "_" . $uid;
        $expected = $this->validator->calculateSignature($base, $secret);
        return [$uid, $friendUid, $secret, $expected];
    }

    public function testFriendUidSignature()
    {
        $time = time();
        list($uid, $friendUid, $secret, $expected) = $this->getFriendSignature($time);
        static::assertTrue($this->validator->validateFriendUid($uid, $friendUid, $time, $secret, $expected));
    }

    public function testInvalidFriendUidSignature()
    {
        $time = time();
        list($uid, $friendUid, $secret, $expected) = $this->getFriendSignature($time);
        static::assertFalse($this->validator->validateFriendUid($uid, $friendUid, $time, $secret, "invalidSignature"));
    }

    public function testFriendUidSignatureAccepts180secondDifferencesToNowInTimestamp()
    {
        $time = time() - 180;
        list($uid, $friendUid, $secret, $expected) = $this->getFriendSignature($time);
        static::assertTrue($this->validator->validateFriendUid($uid, $friendUid, $time, $secret, $expected));

        $time = time() + 180;
        list($uid, $friendUid, $secret, $expected) = $this->getFriendSignature($time);
        static::assertTrue($this->validator->validateFriendUid($uid, $friendUid, $time, $secret, $expected));
    }

    public function testFriendUidSignatureDoesNotAccept181secondDifferentToNowInTimestamp()
    {
        $time = time() - 181;
        list($uid, $friendUid, $secret, $expected) = $this->getFriendSignature($time);
        static::assertFalse($this->validator->validateFriendUid($uid, $friendUid, $time, $secret, $expected));

        $time = time() + 181;
        list($uid, $friendUid, $secret, $expected) = $this->getFriendSignature($time);
        static::assertFalse($this->validator->validateFriendUid($uid, $friendUid, $time, $secret, $expected));
    }

    public function testAssertFriendUidWillNotThrowExceptionForValidSignature()
    {
        $time = time();
        list($uid, $friendUid, $secret, $expected) = $this->getFriendSignature($time);
        static::assertTrue($this->validator->assertFriendUid($uid, $friendUid, $time, $secret, $expected));
    }

    public function testAssertFriendUidWillThrowExceptionForInvalidSignature()
    {
        $time = time();
        list($uid, $friendUid, $secret, $expected) = $this->getFriendSignature($time);
        static::setExpectedException(
            'Graze\Gigya\Exceptions\InvalidFriendUidSignatureException',
            sprintf("The supplied signature for uid: %s and friendUid: %s does not match.\n Expected '%s'\n Supplied 'invalidSignature'",
                static::UID,
                static::FRIEND_UID,
                $expected
            )
        );

        $this->validator->assertFriendUid($uid, $friendUid, $time, $secret, "invalidSignature");
    }

    public function testAssertFriendUidWillThrowExceptionForTimestampOutOfRange()
    {
        $time = time() - 181;
        list($uid, $friendUid, $secret, $expected) = $this->getFriendSignature($time);

        static::setExpectedException(
            'Graze\Gigya\Exceptions\InvalidTimestampException',
            sprintf("The supplied timestamp: %d is more than 180 seconds different to now: %d",
                $time, time()
            )
        );

        $this->validator->assertFriendUid($uid, $friendUid, $time, $secret, $expected);
    }

    public function signatureTests()
    {
        return [
            [
                "3242342342_fbdjsfdksbfs",
                "hdsvddhlrhtbs[]34o543]",
                "PqX1gioG5KKWu2V+nz9d1rKe6rU="
            ],
            [
                "67686768_fdhfgsvk",
                "dsbfjsdkdsaivfdsk",
                "/MPK8HnKtMB1n2oxWYTiWdU0S5k="
            ],
            [
                "129048972362_dhskgafgslad",
                "3e54ght879r8ei0[w-4t5",
                "QFkNQ6JVk8QuDLjFbm3QtE39lkw="
            ],
            [
                "k43je5hgy78efu9w0i-oip4h5u",
                "=-`z0icd9ufohgutreb4kj342lnejpfud0",
                "03NWb/fPekvUmXUlmGVPyPRSDpo="
            ],
        ];
    }
}
