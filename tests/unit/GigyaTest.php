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

namespace Graze\Gigya\Test\Unit;

use Exception;
use Graze\Gigya\Gigya;
use Graze\Gigya\Response\ResponseFactoryInterface;
use Graze\Gigya\Test\TestCase;
use Graze\Gigya\Test\TestFixtures;
use Graze\Gigya\Validation\ResponseValidatorInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Mockery as m;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class GigyaTest extends TestCase
{
    /** @var mixed */
    private $factory;
    /** @var string */
    private $certPath;
    /** @var mixed */
    private $handlerStack;
    /** @var mixed */
    private $guzzleClient;

    public function setUp()
    {
        $this->guzzleClient = m::mock('overload:GuzzleHttp\Client');
        $this->handlerStack = m::mock(new HandlerStack())->makePartial();
        $this->factory = m::mock(ResponseFactoryInterface::class);
        $this->certPath = realpath(__DIR__ . '/../../src/' . Gigya::CERTIFICATE_FILE);
    }

    /**
     * @param string      $key
     * @param string      $secret
     * @param string      $dc
     * @param string|null $userKey
     *
     * @return Gigya
     */
    public function createClient($key = 'key', $secret = 'secret', $dc = Gigya::DC_EU, $userKey = null)
    {
        $options = [
            'uidValidator' => false,
            'factory'      => $this->factory,
            'guzzle'       => ['handler' => $this->handlerStack],
        ];

        return new Gigya($key, $secret, $dc, $userKey, $options);
    }

    /**
     * @param string $fixtureName
     * @param string $uri
     * @param array  $getOptions
     *
     * @return mixed MockInterface and ResponseInterface
     */
    private function setupCall($fixtureName, $uri, array $getOptions)
    {
        $response = new Response(200, [], TestFixtures::getFixture($fixtureName));

        $this->guzzleClient->shouldReceive('post')
                           ->with(
                               $uri,
                               $getOptions
                           )
                           ->andReturn($response);

        $gigyaResponse = m::mock('Graze\Gigya\Response\ResponseInterface');

        $this->factory->shouldReceive('getResponse')
                      ->with($response)
                      ->andReturn($gigyaResponse);

        return $gigyaResponse;
    }

    public function testDefaultConstructor()
    {
        $client = new Gigya('key', 'secret');
        static::assertInstanceOf(Gigya::class, $client);
    }

    public function testConstructorConfig()
    {
        $this->guzzleClient->shouldReceive('__construct')
                           ->once()
                           ->with(
                               [
                                   'handler' => $this->handlerStack,
                               ]
                           )
                           ->andReturn($this->guzzleClient);

        $response = new Response(200, [], TestFixtures::getFixture('account.getAccountInfo'));

        $this->guzzleClient->shouldReceive('post')
                           ->with(
                               'https://accounts.au1.gigya.com/accounts.getAccountInfo',
                               [
                                   'cert'        => 'some_cert.pem',
                                   'auth'        => 'oauth',
                                   'verify'      => $this->certPath,
                                   'form_params' => [],
                               ]
                           )
                           ->andReturn($response);

        $gigyaResponse = m::mock('Graze\Gigya\Response\ResponseInterface');

        $this->factory->shouldReceive('getResponse')
                      ->with($response)
                      ->andReturn($gigyaResponse);

        $config = [
            'auth'         => 'oauth',
            'uidValidator' => false,
            'factory'      => $this->factory,
            'guzzle'       => [
                'handler' => $this->handlerStack,
            ],
            'options'      => [
                'cert' => 'some_cert.pem',
            ],
        ];
        $client = new Gigya('key', 'secret', Gigya::DC_AU, null, $config);

        static::assertSame($gigyaResponse, $client->accounts()->getAccountInfo());
    }

    public function testOAuth2AuthConstructor()
    {
        $this->guzzleClient->shouldReceive('__construct')
                           ->once()
                           ->with(
                               [
                                   'handler' => $this->handlerStack,
                               ]
                           )
                           ->andReturn($this->guzzleClient);

        $response = new Response(200, [], TestFixtures::getFixture('account.getAccountInfo'));

        $this->guzzleClient->shouldReceive('post')
                           ->with(
                               'https://accounts.au1.gigya.com/accounts.getAccountInfo',
                               [
                                   'cert'        => 'some_cert.pem',
                                   'auth'        => 'gigya-oauth2',
                                   'verify'      => $this->certPath,
                                   'form_params' => [],
                               ]
                           )
                           ->andReturn($response);

        $gigyaResponse = m::mock('Graze\Gigya\Response\ResponseInterface');

        $this->factory->shouldReceive('getResponse')
                      ->with($response)
                      ->andReturn($gigyaResponse);

        $config = [
            'auth'         => 'gigya-oauth2',
            'uidValidator' => false,
            'factory'      => $this->factory,
            'guzzle'       => [
                'handler' => $this->handlerStack,
            ],
            'options'      => [
                'cert' => 'some_cert.pem',
            ],
        ];
        $client = new Gigya('key', 'secret', Gigya::DC_AU, null, $config);

        static::assertSame($gigyaResponse, $client->accounts()->getAccountInfo());
    }

    public function testCredentialsAuthConstructor()
    {
        $this->guzzleClient->shouldReceive('__construct')
                           ->with(
                               [
                                   'handler' => $this->handlerStack,
                               ]
                           )
                           ->once()
                           ->andReturn($this->guzzleClient);

        $response = new Response(200, [], TestFixtures::getFixture('account.getAccountInfo'));

        $this->guzzleClient->shouldReceive('post')
                           ->with(
                               'https://accounts.au1.gigya.com/accounts.getAccountInfo',
                               [
                                   'cert'        => 'some_cert.pem',
                                   'auth'        => 'credentials',
                                   'verify'      => $this->certPath,
                                   'form_params' => [],
                               ]
                           )
                           ->once()
                           ->andReturn($response);

        $gigyaResponse = m::mock('Graze\Gigya\Response\ResponseInterface');

        $this->factory->shouldReceive('getResponse')
                      ->with($response)
                      ->andReturn($gigyaResponse);

        $config = [
            'auth'         => 'credentials',
            'uidValidator' => false,
            'factory'      => $this->factory,
            'guzzle'       => [
                'handler' => $this->handlerStack,
            ],
            'options'      => [
                'cert' => 'some_cert.pem',
            ],
        ];
        $client = new Gigya('key', 'secret', Gigya::DC_AU, null, $config);

        static::assertSame($gigyaResponse, $client->accounts()->getAccountInfo());
    }

    public function testSettingKeyAndSecretWillPassToGuzzleClient()
    {
        $key = 'key' . rand(1, 1000);
        $secret = 'secret' . rand(1001, 2000002);

        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [],
            ]
        );
        $client = $this->createClient($key, $secret, Gigya::DC_EU, null);
        $client->setFactory($this->factory);

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($gigyaResponse, $result);
    }

    public function testSettingDataCenterToAuWillCallAuUri()
    {
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.au1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [],
            ]
        );
        $client = $this->createClient('key', 'secret', Gigya::DC_AU);

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($gigyaResponse, $result);
    }

    public function testSettingDataCenterToUsWillCallUsUri()
    {
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.us1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [],
            ]
        );
        $client = $this->createClient('key', 'secret', Gigya::DC_US);

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($gigyaResponse, $result);
    }

    public function testSettingTheUserKeyWillPassItThroughToGuzzle()
    {
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [],
            ]
        );
        $client = $this->createClient('key', 'userSecret', Gigya::DC_EU, 'userKey');
        $client->setFactory($this->factory);

        $result = $client->accounts()->getAccountInfo([]);

        static::assertSame($gigyaResponse, $result);
    }

    public function testPassingParamsThroughToTheMethodWillPassThroughToGuzzle()
    {
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://socialize.eu1.gigya.com/socialize.notifyLogin',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [
                    'param' => 'passedThrough',
                ],
            ]
        );
        $client = $this->createClient();

        $result = $client->socialize()->notifyLogin(['param' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testCallingChildMethodsCallTheCorrectUri()
    {
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://fidm.eu1.gigya.com/fidm.saml.idp.getConfig',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [
                    'params' => 'passedThrough',
                ],
            ]
        );
        $client = $this->createClient();

        $result = $client->saml()->idp()->getConfig(['params' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testTfaCallingChildMethodsCallTheCorrectUri()
    {
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.tfa.getCertificate',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [
                    'params' => 'passedThrough',
                ],
            ]
        );
        $client = $this->createClient();

        $result = $client->accounts()->tfa()->getCertificate(['params' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    /**
     * @dataProvider clientCallDataProvider
     *
     * @param string $namespace
     * @param string $method
     * @param string $expectedUri
     */
    public function testClientCalls($namespace, $method, $expectedUri)
    {
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            $expectedUri,
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [
                    'params' => 'passedThrough',
                ],
            ]
        );
        $client = $this->createClient();

        $result = $client->{$namespace}()->{$method}(['params' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testCallingMagicMethodWithArgumentsThrowsAnException()
    {
        static::expectException('BadMethodCallException');
        static::expectExceptionMessage('No Arguments should be supplied for Gigya call');

        $client = $this->createClient();
        $client->custom('params');
    }

    public function testAddingOptionsPassesThroughTheOptionsToGuzzle()
    {
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [
                    'params' => 'passedThrough',
                ],
                'option1'     => 'value1',
                'option2'     => false,
            ]
        );
        $client = $this->createClient();

        $client->addOption('option1', 'value1');
        $client->addOption('option2', false);

        $result = $client->accounts()->getAccountInfo(['params' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testAddingOptionsWithASingleCallPassesThroughTheOptionsToGuzzle()
    {
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [
                    'params' => 'passedThrough',
                ],
                'option1'     => 'value1',
                'option2'     => true,
            ]
        );
        $client = $this->createClient();

        $client->addOptions(
            [
                'option1' => 'value1',
                'option2' => true,
            ]
        );

        $result = $client->accounts()->getAccountInfo(['params' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testAddingTheSameOptionAgainWillTakeTheLastValueSet()
    {
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [
                    'params' => 'passedThrough',
                ],
                'option1'     => false,
            ]
        );
        $client = $this->createClient();

        $client->addOption('option1', 'value1');
        $client->addOption('option1', false);

        $result = $client->accounts()->getAccountInfo(['params' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testAddingTheSameOptionAgainWithAddOptionsWillTakeTheLastValueSet()
    {
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [
                    'params' => 'passedThrough',
                ],
                'option1'     => true,
            ]
        );
        $client = $this->createClient();

        $client->addOption('option1', 'value1');
        $client->addOptions(['option1' => true]);

        $result = $client->accounts()->getAccountInfo(['params' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testAddingFormParamsOptionsWillBeIgnored()
    {
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => 'notAFile',
                'form_params' => [
                    'params' => 'passedThrough',
                ],
            ]
        );
        $client = $this->createClient();

        $client->addOption('form_params', 'random');
        $client->addOption('verify', 'notAFile');

        $result = $client->accounts()->getAccountInfo(['params' => 'passedThrough']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testSettingOptionsAsPartOfTheQuery()
    {
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [
                    'params' => 'passedThrough',
                ],
                'custom'      => 'value',
            ]
        );
        $client = $this->createClient();

        $result = $client->accounts()->getAccountInfo(['params' => 'passedThrough'], ['custom' => 'value']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testSettingGlobalAndRequestOptionsTheRequestOptionsOverrideGlobalOptions()
    {
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [
                    'params' => 'passedThrough',
                ],
                'custom'      => 'value',
            ]
        );
        $client = $this->createClient();

        $client->addOption('custom', 'notUsed');

        $result = $client->accounts()->getAccountInfo(['params' => 'passedThrough'], ['custom' => 'value']);

        static::assertSame($gigyaResponse, $result);
    }

    public function testSettingRequestOptionsDoOverrideTheParams()
    {
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => false,
                'form_params' => [
                    'params' => 'passedThrough',
                ],
            ]
        );
        $client = $this->createClient();

        $result = $client->accounts()->getAccountInfo(
            ['params' => 'passedThrough'],
            ['form_params' => 'value', 'verify' => false]
        );

        static::assertSame($gigyaResponse, $result);
    }

    public function testSettingParamsWillNotOverwriteTheDefaultParams()
    {
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [
                    'secret' => 'newSecret',
                ],
            ]
        );
        $client = $this->createClient();

        $result = $client->accounts()->getAccountInfo(
            ['secret' => 'newSecret']
        );

        static::assertSame($gigyaResponse, $result);
    }

    public function testCallingAChainWillCallThatChain()
    {
        $gigyaResponse = $this->setupCall(
            'accounts.getAccountInfo',
            'https://fidm.eu1.gigya.com/fidm.saml.idp.getConfig',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [
                    'secret' => 'newSecret',
                ],
            ]
        );
        $client = $this->createClient();

        $result = $client->fidm()->{'saml.idp.getConfig'}(
            ['secret' => 'newSecret']
        );

        static::assertSame($gigyaResponse, $result);
    }

    public function testWillCallAValidator()
    {
        $response = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [],
            ]
        );
        $client = $this->createClient();

        $validator = m::mock(ResponseValidatorInterface::class);
        $client->addValidator($validator);
        $validator->shouldReceive('canValidate')
                  ->with($response)
                  ->andReturn(true);
        $validator->shouldReceive('assert')
                  ->with($response)
                  ->andReturn(true);

        static::assertSame($response, $client->accounts()->getAccountInfo());
    }

    public function tesWillCallMultipleValidators()
    {
        $response = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [],
            ]
        );
        $client = $this->createClient();

        $validator = m::mock(ResponseValidatorInterface::class);
        $client->addValidator($validator);
        $validator->shouldReceive('canValidate')
                  ->with($response)
                  ->andReturn(true);
        $validator->shouldReceive('assert')
                  ->with($response)
                  ->andReturn(true);
        $validator2 = m::mock(ResponseValidatorInterface::class);
        $client->addValidator($validator2);
        $validator2->shouldReceive('canValidate')
                   ->with($response)
                   ->andReturn(true);
        $validator2->shouldReceive('assert')
                   ->with($response)
                   ->andReturn(true);

        static::assertSame($response, $client->accounts()->getAccountInfo());
    }

    public function testTheValidatorThrowingAnExceptionWillPassthrough()
    {
        $response = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [],
            ]
        );
        $client = $this->createClient();

        $validator = m::mock(ResponseValidatorInterface::class);
        $validator2 = m::mock(ResponseValidatorInterface::class);
        $client->addValidator($validator);
        $client->addValidator($validator2);
        $exception = new Exception();
        $validator->shouldReceive('canValidate')
                  ->with($response)
                  ->andReturn(true);
        $validator->shouldReceive('assert')
                  ->with($response)
                  ->andThrow($exception);

        static::expectException(Exception::class);

        $client->accounts()->getAccountInfo();
    }

    public function testTheValidatorWillOnlyCallAssertWhenItCanValidate()
    {
        $response = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [],
            ]
        );
        $client = $this->createClient();

        $validator = m::mock(ResponseValidatorInterface::class);
        $validator2 = m::mock(ResponseValidatorInterface::class);
        $client->addValidator($validator);
        $client->addValidator($validator2);
        $validator->shouldReceive('canValidate')
                  ->with($response)
                  ->andReturn(true);
        $validator->shouldReceive('assert')
                  ->with($response)
                  ->andReturn(true);
        $validator2->shouldReceive('canValidate')
                   ->with($response)
                   ->andReturn(false);

        static::assertSame($response, $client->accounts()->getAccountInfo());
    }

    public function testRemoveSubscriberWillDetachFromEmitter()
    {
        $response = $this->setupCall(
            'accounts.getAccountInfo',
            'https://accounts.eu1.gigya.com/accounts.getAccountInfo',
            [
                'auth'        => 'gigya',
                'verify'      => $this->certPath,
                'form_params' => [],
            ]
        );
        $client = $this->createClient();

        static::assertSame($response, $client->accounts()->getAccountInfo());

        $fn = function (callable $handler) {
            return $handler;
        };
        $this->handlerStack->shouldReceive('push')
                           ->with($fn)
                           ->once();

        $client->addHandler($fn);

        $this->handlerStack->shouldReceive('remove')
                           ->with($fn)
                           ->once();

        $client->removeHandler($fn);
    }

    /**
     * @return array
     */
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
