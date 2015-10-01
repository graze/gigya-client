<?php

namespace Graze\Gigya\Test\Unit;

use Graze\Gigya\Endpoints\Client;
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

    /**
     * @var string
     */
    private $certPath;

    public function setUp()
    {
        $this->guzzleClient = m::mock('overload:GuzzleHttp\Client');
        $this->factory = m::mock('overload:Graze\Gigya\Response\ResponseFactory');

        $this->certPath = realpath(__DIR__ . '/../../src/Endpoints/' . Client::CERTIFICATE_FILE);
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
                    ],
                    'cert'  => $this->certPath,
                ]
            )
            ->andReturn($response);

        $gigyaResponse = m::mock('Graze\Gigya\Response\ResponseInterface');

        $this->factory->shouldReceive('getResponse')
                      ->with($response)
                      ->andReturn($gigyaResponse);

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($gigyaResponse, $result);
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
                    ],
                    'cert'  => $this->certPath,
                ]
            )
            ->andReturn($response);

        $gigyaResponse = m::mock('Graze\Gigya\Response\ResponseInterface');

        $this->factory->shouldReceive('getResponse')
                      ->with($response)
                      ->andReturn($gigyaResponse);

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($gigyaResponse, $result);
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
                    ],
                    'cert'  => $this->certPath,
                ]
            )
            ->andReturn($response);

        $gigyaResponse = m::mock('Graze\Gigya\Response\ResponseInterface');

        $this->factory->shouldReceive('getResponse')
                      ->with($response)
                      ->andReturn($gigyaResponse);

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($gigyaResponse, $result);
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
                    'cert'  => $this->certPath,
                ]
            )
            ->andReturn($response);

        $gigyaResponse = m::mock('Graze\Gigya\Response\ResponseInterface');

        $this->factory->shouldReceive('getResponse')
                      ->with($response)
                      ->andReturn($gigyaResponse);

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($gigyaResponse, $result);
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
                    'cert'  => $this->certPath,
                ]
            )
            ->andReturn($response);

        $gigyaResponse = m::mock('Graze\Gigya\Response\ResponseInterface');

        $this->factory->shouldReceive('getResponse')
                      ->with($response)
                      ->andReturn($gigyaResponse);

        $result = $client->socialize()->notifyLogin(['param' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
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
                    'cert'  => $this->certPath,
                ]
            )
            ->andReturn($response);

        $gigyaResponse = m::mock('Graze\Gigya\Response\ResponseInterface');

        $this->factory->shouldReceive('getResponse')
                      ->with($response)
                      ->andReturn($gigyaResponse);

        $result = $client->saml()->idp()->getConfig(['params' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testTfaCallingChildMethodsCallTheCorrectUri()
    {
        $client = $this->createClient();

        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('accounts.getAccountInfo'));

        $this->guzzleClient
            ->shouldReceive('get')
            ->with(
                'https://accounts.eu1.gigya.com/accounts.tfa.getCertificate',
                [
                    'query' => [
                        'apiKey' => 'key',
                        'secret' => 'secret',
                        'params' => 'passedThrough'
                    ],
                    'cert'  => $this->certPath,
                ]
            )
            ->andReturn($response);

        $gigyaResponse = m::mock('Graze\Gigya\Response\ResponseInterface');

        $this->factory->shouldReceive('getResponse')
                      ->with($response)
                      ->andReturn($gigyaResponse);

        $result = $client->accounts()->tfa()->getCertificate(['params' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    /**
     * @dataProvider clientCallDataProvider
     * @param $namespace
     * @param $method
     * @param $expectedUri
     */
    public function testClientCalls($namespace, $method, $expectedUri)
    {
        $client = $this->createClient();

        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('accounts.getAccountInfo'));

        $this->guzzleClient
            ->shouldReceive('get')
            ->with(
                $expectedUri,
                [
                    'query' => [
                        'apiKey' => 'key',
                        'secret' => 'secret',
                        'params' => 'passedThrough'
                    ],
                    'cert'  => $this->certPath,
                ]
            )
            ->andReturn($response);

        $gigyaResponse = m::mock('Graze\Gigya\Response\ResponseInterface');

        $this->factory->shouldReceive('getResponse')
                      ->with($response)
                      ->andReturn($gigyaResponse);

        $result = $client->{$namespace}()->{$method}(['params' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testCallingMagicMethodWithArgumentsThrowsAnException()
    {
        static::setExpectedException(
            "BadMethodCallException",
            "No Arguments should be supplied for Gigya call"
        );

        $client = $this->createClient();
        $client->custom('params');
    }

    public function clientCallDataProvider()
    {
        return [
            ['accounts', 'getAccountInfo', 'https://accounts.eu1.gigya.com/accounts.getAccountInfo'],
            ['accounts', 'tfa.getCertificate', 'https://accounts.eu1.gigya.com/accounts.tfa.getCertificate'],
            ['audit', 'search', 'https://audit.eu1.gigya.com/audit.search'],
            ['comments', 'analyzeMediaItem', 'https://comments.eu1.gigya.com/comments.analyzeMediaItem'],
            ['dataStore', 'get', 'https://ds.eu1.gigya.com/ds.get'],
            ['ds', 'get', 'https://ds.eu1.gigya.com/ds.get'],
            ['gameMechanics', 'getChallengeStatus', 'https://gm.eu1.gigya.com/gm.getChallengeStatus'],
            ['gm', 'getChallengeStatus', 'https://gm.eu1.gigya.com/gm.getChallengeStatus'],
            ['identityStorage', 'getSchema', 'https://ids.eu1.gigya.com/ids.getSchema'],
            ['ids', 'getSchema', 'https://ids.eu1.gigya.com/ids.getSchema'],
            ['reports', 'getGMStats', 'https://reports.eu1.gigya.com/reports.getGMStats'],
            ['saml', 'setConfig', 'https://fidm.eu1.gigya.com/fidm.saml.setConfig'],
            ['fidm', 'saml.setConfig', 'https://fidm.eu1.gigya.com/fidm.saml.setConfig'],
            ['saml', 'idp.getConfig', 'https://fidm.eu1.gigya.com/fidm.saml.idp.getConfig'],
            ['fidm', 'saml.idp.getConfig', 'https://fidm.eu1.gigya.com/fidm.saml.idp.getConfig'],
            ['socialize', 'checkin', 'https://socialize.eu1.gigya.com/socialize.checkin'],
        ];
    }
}
