<?php

namespace Graze\Gigya\Test\Unit;

use Graze\Gigya\Client;
use Graze\Gigya\Test\TestCase;
use Graze\Gigya\Test\TestFixtures;
use Mockery as m;
use Mockery\MockInterface;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ClientTest extends TestCase
{
    /**
     * @var MockInterface|\GuzzleHttp\Client
     */
    private $guzzleClient;

    /**
     * @var MockInterface|\Graze\Gigya\Model\ModelFactory
     */
    private $factory;

    public function setUp()
    {
        $this->guzzleClient = m::mock('overload:GuzzleHttp\Client');
        $this->factory = m::mock('overload:Graze\Gigya\Model\ModelFactory');
    }

    public function tearDown()
    {
        $this->guzzleClient = $this->factory = null;
    }

    /**
     * @return Client
     */
    public function createClient()
    {
        return (new Client('key', 'secret'))
            ->setDataCenter(Client::DC_EU);
    }

    public function testSettingKeyAndSecretWillPassToGuzzleClient()
    {
        $key = 'key'.rand(1,1000);
        $secret = 'secret'.rand(1001,2000002);
        $client = new Client($key, $secret);
        $client->setDataCenter(Client::DC_EU);

        $response = m::mock('Psr\Http\Message\ResponseInterface');
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

        $model = m::mock('Graze\Gigya\Model\ModelInterface');

        $this->factory->shouldReceive('getModel')
                      ->with($response)
                      ->andReturn($model);

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($model, $result);
    }

    public function testSettingDataCenterToAuWillCallAuUri()
    {
        $client = $this->createClient();
        $client->setDataCenter(Client::DC_AU);

        $response = m::mock('Psr\Http\Message\ResponseInterface');
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

        $model = m::mock('Graze\Gigya\Model\ModelInterface');

        $this->factory->shouldReceive('getModel')
                      ->with($response)
                      ->andReturn($model);

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($model, $result);
    }

    public function testSettingDataCenterToUsWillCallUsUri()
    {
        $client = $this->createClient();
        $client->setDataCenter(Client::DC_US);

        $response = m::mock('Psr\Http\Message\ResponseInterface');
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

        $model = m::mock('Graze\Gigya\Model\ModelInterface');

        $this->factory->shouldReceive('getModel')
                      ->with($response)
                      ->andReturn($model);

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($model, $result);
    }

    public function testSettingTheUserKeyWillPassItThroughToGuzzle()
    {
        $client = new Client('key', 'userSecret', 'userKey');

        $response = m::mock('Psr\Http\Message\ResponseInterface');
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

        $model = m::mock('Graze\Gigya\Model\ModelInterface');

        $this->factory->shouldReceive('getModel')
                      ->with($response)
                      ->andReturn($model);

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($model, $result);
    }

    public function testPassingParamsThroughToTheMethodWillPassThroughToGuzzle()
    {
        $client = $this->createClient();

        $response = m::mock('Psr\Http\Message\ResponseInterface');
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

        $model = m::mock('Graze\Gigya\Model\ModelInterface');

        $this->factory->shouldReceive('getModel')
                      ->with($response)
                      ->andReturn($model);

        $result = $client->socialize()->notifyLogin(['param' => 'passedThrough']);

        static::assertSame($model, $result);
    }

    public function testCallingChildMethodsCallTheCorrectUri()
    {
        $client = $this->createClient();

        $response = m::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('accounts.getAccountInfo'));

        $this->guzzleClient
            ->shouldReceive('get')
            ->with(
                'https://fidm.eu1.gigya.com/fidm.saml.idp.getConfig',
                [
                    'query' => [
                        'apiKey' => 'key',
                        'secret' => 'secret',
                        'params'  => 'passedThrough'
                    ],
                ]
            )
            ->andReturn($response);

        $model = m::mock('Graze\Gigya\Model\ModelInterface');

        $this->factory->shouldReceive('getModel')
                      ->with($response)
                      ->andReturn($model);

        $result = $client->saml()->idp()->getConfig(['params' => 'passedThrough']);

        static::assertSame($model, $result);
    }
}
