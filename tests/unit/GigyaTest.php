<?php

namespace Graze\Gigya\Test\Unit;

use Graze\Gigya\Gigya;
use Graze\Gigya\Test\TestCase;
use Graze\Gigya\Test\TestFixtures;
use Mockery as m;
use Mockery\MockInterface;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class GigyaTest extends TestCase
{
    /**
     * @var MockInterface|\GuzzleHttp\Client
     */
    private $guzzleClient;

    /**
     * @var MockInterface|\Graze\Gigya\Response\ResponseFactory
     */
    private $factory;

    public function setUp()
    {
        $this->guzzleClient = m::mock('overload:GuzzleHttp\Client');
        $this->factory = m::mock('overload:Graze\Gigya\Response\ResponseFactory');
    }

    public function tearDown()
    {
        $this->guzzleClient = $this->factory = null;
    }

    /**
     * @param string|null $dc
     * @return Gigya
     */
    public function createClient($dc = null)
    {
        return new Gigya('key', 'secret', $dc ?: Gigya::DC_EU, null);
    }

    public function testSettingKeyAndSecretWillPassToGuzzleClient()
    {
        $key = 'key' . rand(1, 1000);
        $secret = 'secret' . rand(1001, 2000002);
        $client = new Gigya($key, $secret, Gigya::DC_EU, null);

        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('accounts.getAccountInfo'));

        $this->guzzleClient
            ->shouldReceive('get')
            ->with(
                'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
                [
                    'query' => [
                        'apiKey' => $key,
                        'secret' => $secret
                    ]
                ]
            )
            ->andReturn($response);

        $model = m::mock('Graze\Gigya\Response\ModelInterface');

        $this->factory->shouldReceive('getModel')
                      ->with($response)
                      ->andReturn($model);

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($model, $result);
    }

    public function testSettingDataCenterToAuWillCallAuUri()
    {
        $client = $this->createClient(Gigya::DC_AU);

        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('accounts.getAccountInfo'));

        $this->guzzleClient
            ->shouldReceive('get')
            ->with(
                'https://accounts.au1.gigya.com/accounts.getAccountInfo',
                [
                    'query' => [
                        'apiKey' => 'key',
                        'secret' => 'secret'
                    ]
                ]
            )
            ->andReturn($response);

        $model = m::mock('Graze\Gigya\Response\ModelInterface');

        $this->factory->shouldReceive('getModel')
                      ->with($response)
                      ->andReturn($model);

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($model, $result);
    }

    public function testSettingDataCenterToUsWillCallUsUri()
    {
        $client = $this->createClient(Gigya::DC_US);

        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('accounts.getAccountInfo'));

        $this->guzzleClient
            ->shouldReceive('get')
            ->with(
                'https://accounts.us1.gigya.com/accounts.getAccountInfo',
                [
                    'query' => [
                        'apiKey' => 'key',
                        'secret' => 'secret'
                    ]
                ]
            )
            ->andReturn($response);

        $model = m::mock('Graze\Gigya\Response\ModelInterface');

        $this->factory->shouldReceive('getModel')
                      ->with($response)
                      ->andReturn($model);

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($model, $result);
    }

    public function testSettingTheUserKeyWillPassItThroughToGuzzle()
    {
        $client = new Gigya('key', 'userSecret', Gigya::DC_EU, 'userKey');

        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('accounts.getAccountInfo'));

        $this->guzzleClient
            ->shouldReceive('get')
            ->with(
                'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
                [
                    'query' => [
                        'apiKey'  => 'key',
                        'secret'  => 'userSecret',
                        'userKey' => 'userKey',
                    ],
                ]
            )
            ->andReturn($response);

        $model = m::mock('Graze\Gigya\Response\ModelInterface');

        $this->factory->shouldReceive('getModel')
                      ->with($response)
                      ->andReturn($model);

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($model, $result);
    }

    public function testPassingParamsThroughToTheMethodWillPassThroughToGuzzle()
    {
        $client = $this->createClient();

        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('accounts.getAccountInfo'));

        $this->guzzleClient
            ->shouldReceive('get')
            ->with(
                'https://socialize.eu1.gigya.com/socialize.notifyLogin',
                [
                    'query' => [
                        'apiKey' => 'key',
                        'secret' => 'secret',
                        'param'  => 'passedThrough'
                    ],
                ]
            )
            ->andReturn($response);

        $model = m::mock('Graze\Gigya\Response\ModelInterface');

        $this->factory->shouldReceive('getModel')
                      ->with($response)
                      ->andReturn($model);

        $result = $client->socialize()->notifyLogin(['param' => 'passedThrough']);

        static::assertSame($model, $result);
    }

    public function testCallingChildMethodsCallTheCorrectUri()
    {
        $client = $this->createClient();

        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('accounts.getAccountInfo'));

        $this->guzzleClient
            ->shouldReceive('get')
            ->with(
                'https://fidm.eu1.gigya.com/fidm.saml.idp.getConfig',
                [
                    'query' => [
                        'apiKey' => 'key',
                        'secret' => 'secret',
                        'params' => 'passedThrough'
                    ],
                ]
            )
            ->andReturn($response);

        $model = m::mock('Graze\Gigya\Response\ModelInterface');

        $this->factory->shouldReceive('getModel')
                      ->with($response)
                      ->andReturn($model);

        $result = $client->saml()->idp()->getConfig(['params' => 'passedThrough']);

        static::assertSame($model, $result);
    }
}
