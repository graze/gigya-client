<?php

namespace Graze\Gigya\Test\Unit\Validation;

use Graze\Gigya\Test\TestCase;
use Graze\Gigya\Validation\Signature;

class SignatureTest extends TestCase
{
    const UID        = "g8f7d6gd7s6t23t4gekfs";
    const FRIEND_UID = "890fd7tg97d08sbg";
    const SECRET     = "8j9h0g-opko;dk]=id0f[sjo";

    /**
     * @var Signature
     */
    private $validator;

    public function setUp()
    {
        $this->validator = new Signature();
    }

    public function teatDown()
    {
        $this->validator = null;
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

    public function signatureTests()
    {
        return [
            ["3242342342_fbdjsfdksbfs", "hdsvddhlrhtbs[]34o543]", "PqX1gioG5KKWu2V+nz9d1rKe6rU="],
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

    /**
     * @dataProvider getUidSignatures
     * @param $uid
     * @param $time
     * @param $secret
     * @param $expected
     */
    public function testGetUidSignature($uid, $time, $secret, $expected)
    {
        static::assertEquals($expected, $this->validator->getUidSignature($uid, $time, $secret));
    }

    public function getUidSignatures()
    {
        return [
            ['908dsf7g68d9', 23456754, 'jkl234h53k2', 'AzKUa+6a2+27Oqxu0nDksvDdjLI='],
            ['0-9vbn8m7gfd0ru', 46578543, 'mkl34n5jb4356', 'zGAMM6XlTamV4QH+g3C/RgptX+0='],
            ['nk34bjh5kl432njbh', 3456789, 'kln45j654l3kn5jb', 'y8fo2ebrzyuN/kbX0wbPul8VwI8='],
            ['b45432lkn4jb', 9876543, 'b4645kl35jb', 'VP93O4U8MeTCH5sJntEBVBRG/F0='],
            ['3m4lkn5jb645l43;2k', 345678232, '890fd7g69f0sfd7', 'sVrznUKLsDOJ1Z3Df4qNhClWZ8Q='],
            ['809dierpwojl4k23mr', 76823433, 'v65jbk4renlfioupb8', 'hqT0Y7rFL1khRLfwVDFk64vf28M=']
        ];
    }

    /**
     * @dataProvider getFriendUidSignatures
     * @param $uid
     * @param $time
     * @param $secret
     * @param $expected
     */
    public function testGetFriendUidSignature($uid, $friendUid, $time, $secret, $expected)
    {
        static::assertEquals($expected, $this->validator->getFriendUidSignature($uid, $friendUid, $time, $secret));
    }

    public function getFriendUidSignatures()
    {
        return [
            ['908dsf7g68d9', '7fcghjv5et4iogu9fp8', 23456754, 'jkl234h53k2', 'tq+VN8H/ygYJbSF+91Mak8XUjGY='],
            ['0-9vbn8m7gfd0ru', 'b5jkw4repshdf89vuilbt453w', 46578543, 'mkl34n5jb4356', 'g+oeLL0iZYC4C7tb34qdov5Puec='],
            [
                'nk34bjh5kl432njbh',
                '849357yturiehjwldaksfnbv',
                3456789,
                'kln45j654l3kn5jb',
                't4b9ggtficcKyL4YbHpdHEZbdrw='
            ],
            ['b45432lkn4jb', 'v5hjbkl34;ejapwfu8d9gyo7', 9876543, 'b4645kl35jb', 'WKiloNBnOvazdwary9EQdlAKovs='],
            ['3m4lkn5jb645l43;2k', 'b5yelrtgiufdbtker', 345678232, '890fd7g69f0sfd7', 'UnuJroSwujupzqwhaCQYItNQ0C4='],
            [
                '809dierpwojl4k23mr',
                '03-49ut8iryughdfkjsladm',
                76823433,
                'v65jbk4renlfioupb8',
                'dkcOPZlo35IE0IMkYtXmeaqF58g='
            ]
        ];
    }

    public function testCheckTimestamp()
    {
        static::assertTrue($this->validator->checkTimestamp(time()));
        static::assertTrue($this->validator->checkTimestamp(time() + 180));
        static::assertTrue($this->validator->checkTimestamp(time() - 180));
        static::assertFalse($this->validator->checkTimestamp(time() + 181));
        static::assertFalse($this->validator->checkTimestamp(time() - 181));
    }
}
