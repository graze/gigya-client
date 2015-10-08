<?php

namespace Graze\Gigya\Test\Functional;

use Graze\Gigya\Gigya;
use Graze\Gigya\Test\TestCase;
use Graze\Gigya\Test\TestFixtures;
use GuzzleHttp\Event\Emitter;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\History;
use GuzzleHttp\Subscriber\Mock;

class GigyaTest extends TestCase
{
    /**
     * @param Gigya $gigya
     * @return History
     */
    public function setUpGigyaHistory(Gigya $gigya)
    {
        $emitter = new Emitter();
        $history = new History();
        $mock = new Mock([
            new Response(
                '200',
                ['test-encoding' => 'application/json'],
                Stream::factory(TestFixtures::getFixture('basic'))
            )
        ]);
        $emitter->attach($history);
        $emitter->attach($mock);

        $gigya->setGuzzleConfig(['emitter' => $emitter]);

        return $history;
    }

    public function testAuthInjectsKeyAndSecretIntoParams()
    {
        $client = new Gigya('key', 'secret');
        $history = $this->setUpGigyaHistory($client);

        $response = $client->accounts()->getAccountInfo();

        static::assertEquals(0, $response->getErrorCode());
        static::assertEquals(1, $history->count());
        $request = $history->getLastRequest();
        static::assertEquals('https://accounts.eu1.gigya.com/accounts.getAccountInfo', $request->getUrl());
        $params = $request->getConfig()->get('params');
        static::assertArrayHasKey('apiKey', $params);
        static::assertArrayHasKey('secret', $params);
        static::assertEquals('key', $params['apiKey']);
        static::assertEquals('secret', $params['secret']);
    }

    public function testAuthInjectsKeySecretAndUserKeyIntoParams()
    {
        $client = new Gigya('key', 'secret', Gigya::DC_EU, 'userKey');
        $history = $this->setUpGigyaHistory($client);

        $response = $client->accounts()->getAccountInfo();

        static::assertEquals(0, $response->getErrorCode());
        static::assertEquals(1, $history->count());
        $request = $history->getLastRequest();
        static::assertEquals('https://accounts.eu1.gigya.com/accounts.getAccountInfo', $request->getUrl());
        $params = $request->getConfig()->get('params');
        static::assertArrayHasKey('apiKey', $params);
        static::assertArrayHasKey('secret', $params);
        static::assertArrayHasKey('userKey', $params);
        static::assertEquals('key', $params['apiKey']);
        static::assertEquals('secret', $params['secret']);
        static::assertEquals('userKey', $params['userKey']);
    }
}
