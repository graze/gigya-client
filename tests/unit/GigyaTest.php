<?php

namespace Graze\Gigya\Test\Unit;

use Graze\Gigya\Auth\GigyaAuthInterface;
use Graze\Gigya\Gigya;
use Graze\Gigya\Response\ResponseInterface;
use Graze\Gigya\Test\TestCase;
use Graze\Gigya\Test\TestFixtures;
use Graze\Gigya\Validation\ResponseValidatorInterface;
use Graze\Gigya\Validation\ValidGigyaResponseSubscriber;
use GuzzleHttp\Event\EmitterInterface;
use GuzzleHttp\Event\SubscriberInterface;
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
        $this->certPath = realpath(__DIR__ . '/../../src/' . Gigya::CERTIFICATE_FILE);
    }

    public function tearDown()
    {
        $this->guzzleClient = $this->factory = $this->auth = null;
    }

    /**
     * @param string|null $dc
     * @return Gigya
     */
    public function createClient($dc = null)
    {
        return new Gigya('key', 'secret', $dc ?: Gigya::DC_EU, null);
    }

    /**
     * @param string      $fixtureName
     * @param string      $uri
     * @param array       $getOptions
     * @param string      $key
     * @param string      $secret
     * @param string|null $userKey
     * @return ResponseInterface
     */
    private function setupCall($fixtureName, $uri, $getOptions, $key, $secret, $userKey = null)
    {
        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture($fixtureName));

        $this->factory->shouldReceive('addValidator')
                      ->with(m::type(ResponseValidatorInterface::class));

        $emitter = m::mock(EmitterInterface::class);
        $this->guzzleClient->shouldReceive('getEmitter')
                           ->andReturn($emitter);
        $this->guzzleClient
            ->shouldReceive('get')
            ->with(
                $uri,
                $getOptions
            )
            ->andReturn($response);

        $emitter->shouldReceive('attach')
                ->with(m::type(ValidGigyaResponseSubscriber::class))
                ->once();
        $emitter->shouldReceive('attach')
                ->with(m::on(function (SubscriberInterface $subscriber) use ($key, $secret, $userKey) {
                    if ($subscriber instanceof GigyaAuthInterface) {
                        static::assertEquals($key, $subscriber->getApiKey());
                        static::assertEquals($secret, $subscriber->getSecret());
                        static::assertEquals($userKey, $subscriber->getUserKey());
                    }
                    return true;
                }))
                ->once();

        $gigyaResponse = m::mock('Graze\Gigya\Response\ResponseInterface');

        $this->factory->shouldReceive('getResponse')
                      ->with($response)
                      ->andReturn($gigyaResponse);

        return $gigyaResponse;
    }

    public function testSettingKeyAndSecretWillPassToGuzzleClient()
    {
        $key = 'key' . rand(1, 1000);
        $secret = 'secret' . rand(1001, 2000002);
        $client = new Gigya($key, $secret, Gigya::DC_EU, null);

        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'   => 'gigya',
                'verify' => $this->certPath,
                'query'  => []
            ],
            $key,
            $secret
        );

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($gigyaResponse, $result);
    }

    public function testSettingDataCenterToAuWillCallAuUri()
    {
        $client = $this->createClient(Gigya::DC_AU);

        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.au1.gigya.com/accounts.getAccountInfo',
            [
                'auth'   => 'gigya',
                'verify' => $this->certPath,
                'query'  => []
            ],
            'key',
            'secret'
        );

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($gigyaResponse, $result);
    }

    public function testSettingDataCenterToUsWillCallUsUri()
    {
        $client = $this->createClient(Gigya::DC_US);

        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.us1.gigya.com/accounts.getAccountInfo',
            [
                'auth'   => 'gigya',
                'verify' => $this->certPath,
                'query'  => []
            ],
            'key',
            'secret'
        );

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($gigyaResponse, $result);
    }

    public function testSettingTheUserKeyWillPassItThroughToGuzzle()
    {
        $client = new Gigya('key', 'userSecret', Gigya::DC_EU, 'userKey');

        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'   => 'gigya',
                'verify' => $this->certPath,
                'query'  => []
            ],
            'key',
            'userSecret',
            'userKey'
        );

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($gigyaResponse, $result);
    }

    public function testPassingParamsThroughToTheMethodWillPassThroughToGuzzle()
    {
        $client = $this->createClient();

        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://socialize.eu1.gigya.com/socialize.notifyLogin',
            [
                'auth'   => 'gigya',
                'verify' => $this->certPath,
                'query'  => [
                    'param' => 'passedThrough'
                ],
            ],
            'key',
            'secret'
        );

        $result = $client->socialize()->notifyLogin(['param' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testCallingChildMethodsCallTheCorrectUri()
    {
        $client = $this->createClient();

        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://fidm.eu1.gigya.com/fidm.saml.idp.getConfig',
            [
                'auth'   => 'gigya',
                'verify' => $this->certPath,
                'query'  => [
                    'params' => 'passedThrough'
                ],
            ],
            'key',
            'secret'
        );

        $result = $client->saml()->idp()->getConfig(['params' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testTfaCallingChildMethodsCallTheCorrectUri()
    {
        $client = $this->createClient();

        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.tfa.getCertificate',
            [
                'auth'   => 'gigya',
                'verify' => $this->certPath,
                'query'  => [
                    'params' => 'passedThrough'
                ],
            ],
            'key',
            'secret'
        );

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

        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            $expectedUri,
            [
                'auth'   => 'gigya',
                'verify' => $this->certPath,
                'query'  => [
                    'params' => 'passedThrough'
                ],
            ],
            'key',
            'secret'
        );

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

    public function testAddingOptionsPassesThroughTheOptionsToGuzzle()
    {
        $client = $this->createClient();

        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'    => 'gigya',
                'verify'  => $this->certPath,
                'query'   => [
                    'params' => 'passedThrough'
                ],
                'option1' => 'value1',
                'option2' => false,
            ],
            'key',
            'secret'
        );

        $client->addOption('option1', 'value1');
        $client->addOption('option2', false);

        $result = $client->accounts()->getAccountInfo(['params' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testAddingOptionsWithASingleCallPassesThroughTheOptionsToGuzzle()
    {
        $client = $this->createClient();

        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'    => 'gigya',
                'verify'  => $this->certPath,
                'query'   => [
                    'params' => 'passedThrough'
                ],
                'option1' => 'value1',
                'option2' => true,
            ],
            'key',
            'secret'
        );

        $client->addOptions([
            'option1' => 'value1',
            'option2' => true,
        ]);

        $result = $client->accounts()->getAccountInfo(['params' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testAddingTheSameOptionAgainWillTakeTheLastValueSet()
    {
        $client = $this->createClient();

        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'    => 'gigya',
                'verify'  => $this->certPath,
                'query'   => [
                    'params' => 'passedThrough'
                ],
                'option1' => false,
            ],
            'key',
            'secret'
        );

        $client->addOption('option1', 'value1');
        $client->addOption('option1', false);

        $result = $client->accounts()->getAccountInfo(['params' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testAddingTheSameOptionAgainWithAddOptionsWillTakeTheLastValueSet()
    {
        $client = $this->createClient();

        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'    => 'gigya',
                'verify'  => $this->certPath,
                'query'   => [
                    'params' => 'passedThrough'
                ],
                'option1' => true,
            ],
            'key',
            'secret'
        );

        $client->addOption('option1', 'value1');
        $client->addOptions(['option1' => true]);

        $result = $client->accounts()->getAccountInfo(['params' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testAddingQueryOptionsWillBeIgnored()
    {
        $client = $this->createClient();

        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'   => 'gigya',
                'verify' => 'notAFile',
                'query'  => [
                    'params' => 'passedThrough'
                ],
            ],
            'key',
            'secret'
        );

        $client->addOption('query', 'random');
        $client->addOption('verify', 'notAFile');

        $result = $client->accounts()->getAccountInfo(['params' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testSettingOptionsAsPartOfTheQuery()
    {
        $client = $this->createClient();
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'   => 'gigya',
                'verify' => $this->certPath,
                'query'  => [
                    'params' => 'passedThrough'
                ],
                'custom' => 'value'
            ],
            'key',
            'secret'
        );

        $result = $client->accounts()->getAccountInfo(['params' => 'passedThrough'], ['custom' => 'value']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testSettingGlobalAndRequestOptionsTheRequestOptionsOverrideGlobalOptions()
    {
        $client = $this->createClient();
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'   => 'gigya',
                'verify' => $this->certPath,
                'query'  => [
                    'params' => 'passedThrough'
                ],
                'custom' => 'value'
            ],
            'key',
            'secret'
        );

        $client->addOption('custom', 'notUsed');

        $result = $client->accounts()->getAccountInfo(['params' => 'passedThrough'], ['custom' => 'value']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testSettingRequestOptionsDoOverrideTheParams()
    {
        $client = $this->createClient();
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'   => 'gigya',
                'verify' => false,
                'query'  => [
                    'params' => 'passedThrough'
                ],
            ],
            'key',
            'secret'
        );

        $result = $client->accounts()->getAccountInfo(
            ['params' => 'passedThrough'],
            ['query' => 'value', 'verify' => false]
        );

        static::assertSame($gigyaResponse, $result);
    }

    public function testSettingParamsWillNotOverwriteTheDefaultParams()
    {
        $client = $this->createClient();
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'   => 'gigya',
                'verify' => $this->certPath,
                'query'  => [
                    'secret' => 'newSecret'
                ],
            ],
            'key',
            'secret'
        );

        $result = $client->accounts()->getAccountInfo(
            ['secret' => 'newSecret']
        );

        static::assertSame($gigyaResponse, $result);
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
