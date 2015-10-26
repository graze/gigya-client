<?php

namespace Graze\Gigya\Test\Unit\Response;

use DateTimeImmutable;
use Graze\Gigya\Response\Response;
use Graze\Gigya\Response\ResponseCollectionInterface;
use Graze\Gigya\Response\ResponseFactory;
use Graze\Gigya\Test\TestCase;
use Graze\Gigya\Test\TestFixtures;
use Mockery as m;

// use Psr\Http\Message\ResponseInterface; Guzzle v6

class ResponseFactoryTest extends TestCase
{
    /**
     * @var ResponseFactory
     */
    private $factory;

    public static function setUpBeforeClass()
    {
        date_default_timezone_set('UTC');
    }

    public function setUp()
    {
        $this->factory = new ResponseFactory();
    }

    public function tearDown()
    {
        $this->factory = null;
    }

    public function testAccountModel()
    {
        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('accounts.getAccountInfo'));

        $gigyaResponse = $this->factory->getResponse($response);

        static::assertInstanceOf('Graze\Gigya\Response\Response', $gigyaResponse);
        static::assertEquals(200, $gigyaResponse->getStatusCode());
        static::assertEquals(0, $gigyaResponse->getErrorCode());
        static::assertEquals('OK', $gigyaResponse->getStatusReason());
        static::assertEquals('e6f891ac17f24810bee6eb533524a152', $gigyaResponse->getCallId());
        static::assertInstanceOf('DateTimeInterface', $gigyaResponse->getTime());
        static::assertEquals(
            DateTimeImmutable::createFromFormat(Response::DATE_TIME_FORMAT, '2015-03-22T11:42:25.943Z'),
            $gigyaResponse->getTime()
        );
        $data = $gigyaResponse->getData();
        static::assertEquals('_gid_30A3XVJciH95WEEnoRmfZS7ee3MY+lUAtpVxvUWNseU=', $data->get('UID'));
        static::assertSame($response, $gigyaResponse->getOriginalResponse());
    }

    public function testCollectionModel()
    {
        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('accounts.search_simple'));

        /** @var ResponseCollectionInterface $gigyaResponse */
        $gigyaResponse = $this->factory->getResponse($response);

        static::assertInstanceOf('Graze\Gigya\Response\ResponseCollection', $gigyaResponse);
        static::assertEquals(200, $gigyaResponse->getStatusCode());
        static::assertEquals(1840, $gigyaResponse->getTotal());
        static::assertEquals(5, $gigyaResponse->getCount());
        static::assertNull($gigyaResponse->getNextCursor());

        $results = $gigyaResponse->getData();

        static::assertEquals(5, $results->count());
        static::assertEquals('g1@gmail.com', $results[0]->profile->email);
    }

    public function testError403()
    {
        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('failure_403'));

        $gigyaResponse = $this->factory->getResponse($response);

        static::assertInstanceOf('Graze\Gigya\Response\Response', $gigyaResponse);
        static::assertEquals(403, $gigyaResponse->getStatusCode());
        static::assertEquals(403005, $gigyaResponse->getErrorCode());
        static::assertEquals('Forbidden', $gigyaResponse->getStatusReason());
        static::assertEquals('Unauthorized user', $gigyaResponse->getErrorMessage());
        static::assertEquals('The user billyBob cannot login', $gigyaResponse->getErrorDetails());
    }
}
