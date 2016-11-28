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

namespace Graze\Gigya\Test\Performance;

use Graze\Gigya\Gigya;
use Graze\Gigya\Test\TestCase;
use Graze\Gigya\Test\TestFixtures;
use Graze\Gigya\Validation\Signature;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

/**
 * @group performance
 */
class GigyaTest extends TestCase
{
    /**
     * @var Gigya
     */
    protected $gigya;

    /**
     * @var float
     */
    private $time;

    /**
     * @var int
     */
    private $memory;

    public function createBasicHandler()
    {
        $handler = new MockHandler(array_pad([], 1000, new Response(200, [], TestFixtures::getFixture('basic'))));
        $this->gigya = new Gigya('key', 'secret', null, null, [
            'guzzle' => [
                'handler' => new HandlerStack($handler),
            ],
        ]);
    }

    public function createAccountInfoHandler()
    {
        $handler = new MockHandler();
        for ($i = 0; $i < 1000; $i++) {
            $handler->append(function () {
                $uid = 'diofu90ifgdf';
                $timestamp = time();

                $signatureValidator = new Signature();
                $signature = $signatureValidator->calculateSignature($timestamp . '_' . $uid, 'secret');

                return new Response(
                    200,
                    [],
                    sprintf(
                        '{
                    "UID": "%s",
                    "UIDSignature": "%s",
                    "signatureTimestamp": "%d",
                    "statusCode": 200,
                    "errorCode": 0,
                    "statusReason": "OK",
                    "callId": "123456",
                    "time": "2015-03-22T11:42:25.943Z"
                }',
                        $uid,
                        $signature,
                        $timestamp
                    )
                );
            });
        }

        $this->gigya = new Gigya('key', 'secret', null, null, [
            'guzzle' => [
                'handler' => new HandlerStack($handler),
            ],
        ]);
    }

    private function startBenchmark()
    {
        $this->time = microtime(true);
        $this->memory = memory_get_usage(true);
    }

    /**
     * @return array
     */
    private function endBenchmark()
    {
        $duration = microtime(true) - $this->time;
        $memoryUsed = memory_get_usage(true) - $this->memory;

        return [$duration, $memoryUsed];
    }

    /**
     * @param string $name
     * @param int    $iterations
     */
    private function printBenchmark($name, $iterations)
    {
        list($duration, $memoryUsed) = $this->endBenchmark();
        printf(
            "\nRun: %s\n  Total Duration : %.3fs\n  Per Request    : %.3fms\n  Memory used    : %.2fMB\n\n",
            $name,
            $duration,
            $duration * 1000 / $iterations,
            $memoryUsed / 1000 / 1000
        );
    }

    public function testSingleCall()
    {
        $this->createBasicHandler();
        $this->startBenchmark();

        $this->gigya->accounts()->getAccountInfo(['uid' => 'some_uid']);

        $this->printBenchmark(__METHOD__, 1);
    }

    public function testSingleChildCall()
    {
        $this->createBasicHandler();
        $this->startBenchmark();

        $num = 1000;
        for ($i = 0; $i < $num; $i++) {
            $this->gigya->accounts()->getAccountInfo(['uid' => $i]);
        }

        $this->printBenchmark(__METHOD__, $num);
    }

    public function testDoubleChildCall()
    {
        $this->createBasicHandler();
        $this->startBenchmark();

        $num = 1000;
        for ($i = 0; $i < $num; $i++) {
            $this->gigya->accounts()->tfa()->finalizeTFA(['uid' => $i]);
        }

        $this->printBenchmark(__METHOD__, $num);

        list($duration) = $this->endBenchmark();
        static::assertLessThan(
            2,
            $duration * 1000 / $num,
            'An individual request should take less than 2ms of prep and response validation'
        );
    }

    public function testUidValidationResponse()
    {
        $this->createAccountInfoHandler();
        $this->startBenchmark();

        $num = 1000;
        for ($i = 0; $i < $num; $i++) {
            $this->gigya->accounts()->getAccountInfo(['uid' => $i]);
        }

        $this->printBenchmark(__METHOD__, $num);
        list($duration) = $this->endBenchmark();
        static::assertLessThan(
            2,
            $duration * 1000 / $num,
            'An individual request should take less than 2ms of prep and response validation'
        );
    }

    public function testSingleCallAgain()
    {
        $this->createBasicHandler();
        $this->startBenchmark();

        $this->gigya->accounts()->getAccountInfo(['uid' => 'some_uid']);

        $this->printBenchmark(__METHOD__, 1);
    }
}
