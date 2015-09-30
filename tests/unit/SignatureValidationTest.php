<?php

namespace Graze\Gigya\Test\Unit;

use Graze\Gigya\SignatureValidation;
use Graze\Gigya\Test\TestCase;

class SignatureValidationTest extends TestCase
{
    /**
     * @var SignatureValidation
     */
    private $validator;

    public function setUp()
    {
        $this->validator = new SignatureValidation();
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

    public function testUidSignature()
    {
        $uid = "g8f7d6gd7s6t23t4gekfs";
        $secret = "8j9h0g-opko;dk]=id0f[sjo";
        $time = time();
        $base = $time . "_" . $uid;
        $expected = $this->validator->calculateSignature($base, $secret);
        static::assertTrue($this->validator->validateUid($uid, $time, $secret, $expected));
    }

    public function testInvalidUidSignature()
    {
        $uid = "g8f7d6gd7s6t23t4gekfs";
        $secret = "8j9h0g-opko;dk]=id0f[sjo";
        $time = time();
        static::assertFalse($this->validator->validateUid($uid, $time, $secret, "invalidSignature"));
    }

    public function testUidSignatureAccepts180secondDifferencesToNowInTimestamp()
    {
        $uid = "g8f7d6gd7s6t23t4gekfs";
        $secret = "8j9h0g-opko;dk]=id0f[sjo";
        $time = time() - 180;
        $base = $time . "_" . $uid;
        $expected = $this->validator->calculateSignature($base, $secret);
        static::assertTrue($this->validator->validateUid($uid, $time, $secret, $expected));

        $time = time() + 180;
        $base = $time . "_" . $uid;
        $expected = $this->validator->calculateSignature($base, $secret);
        static::assertTrue($this->validator->validateUid($uid, $time, $secret, $expected));
    }

    public function testUidSignatureDoesNotAccept181secondDifferentToNowInTimestamp()
    {
        $uid = "g8f7d6gd7s6t23t4gekfs";
        $secret = "8j9h0g-opko;dk]=id0f[sjo";
        $time = time() - 181;
        $base = $time . "_" . $uid;
        $expected = $this->validator->calculateSignature($base, $secret);
        static::assertFalse($this->validator->validateUid($uid, $time, $secret, $expected));

        $uid = "g8f7d6gd7s6t23t4gekfs";
        $secret = "8j9h0g-opko;dk]=id0f[sjo";
        $time = time() + 181;
        $base = $time . "_" . $uid;
        $expected = $this->validator->calculateSignature($base, $secret);
        static::assertFalse($this->validator->validateUid($uid, $time, $secret, $expected));
    }

    public function testAssertUidWillNotThrowExceptionForValidSignature()
    {
        $uid = "g8f7d6gd7s6t23t4gekfs";
        $secret = "8j9h0g-opko;dk]=id0f[sjo";
        $time = time();
        $base = $time . "_" . $uid;
        $expected = $this->validator->calculateSignature($base, $secret);
        static::assertTrue($this->validator->assertUid($uid, $time, $secret, $expected));
    }

    public function testAssertUidWillThrowExceptionForInvalidSignature()
    {
        $uid = "g8f7d6gd7s6t23t4gekfs";
        $secret = "8j9h0g-opko;dk]=id0f[sjo";
        $time = time();
        $base = $time . "_" . $uid;
        $expected = $this->validator->calculateSignature($base, $secret);
        static::setExpectedException(
            'Graze\Gigya\Exceptions\InvalidUidSignatureException',
            sprintf("The supplied signature for uid: g8f7d6gd7s6t23t4gekfs does not match.\n Expected '%s'\n Supplied 'invalidSignature'",
                $expected
            )
        );

        $this->validator->assertUid($uid, $time, $secret, "invalidSignature");
    }

    public function testAssertUidWillThrowExceptionForTimestampOutOfRange()
    {
        $uid = "g8f7d6gd7s6t23t4gekfs";
        $secret = "8j9h0g-opko;dk]=id0f[sjo";
        $time = time() - 181;
        $base = $time . "_" . $uid;
        $expected = $this->validator->calculateSignature($base, $secret);

        static::setExpectedException(
            'Graze\Gigya\Exceptions\InvalidTimestampException',
            sprintf("The supplied timestamp: %d is more than 180 seconds different to now: %d",
                $time, time()
            )
        );

        $this->validator->assertUid($uid, $time, $secret, $expected);
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
