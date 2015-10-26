<?php

namespace Graze\Gigya\Test\Performance;

use Graze\Gigya\Gigya;
use Graze\Gigya\Test\TestCase;
use Graze\Gigya\Test\TestFixtures;
use Graze\Gigya\Validation\Signature;
use GuzzleHttp\Ring\Client\MockHandler;

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
        $handler     = new MockHandler([
            'status' => 200,
            'body'   => TestFixtures::getFixture('basic'),
        ]);
        $this->gigya = new Gigya('key', 'secret', null, [
            'guzzle' => [
                'handler' => $handler,
            ],
        ]);
    }

    public function createAccountInfoHandler()
    {
        $handler = new MockHandler(function ($request) {
            $uid       = 'diofu90ifgdf';
            $timestamp = time();

            $signatureValidator = new Signature();
            $signature          = $signatureValidator->calculateSignature($timestamp . '_' . $uid, 'secret');

            return [
                'status' => 200,
                'body'   => sprintf(
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
                ),
            ];
        });

        $this->gigya = new Gigya('key', 'secret', null, null, [
            'guzzle' => [
                'handler' => $handler,
            ],
        ]);
    }

    private function startBenchmark()
    {
        $this->time   = microtime(true);
        $this->memory = memory_get_usage(true);
    }

    private function endBenchmark()
    {
        $duration   = microtime(true) - $this->time;
        $memoryUsed = memory_get_usage(true) - $this->memory;

        return [$duration, $memoryUsed];
    }

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

        list($duration, $memory) = $this->endBenchmark();
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
        list($duration, $memory) = $this->endBenchmark();
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
