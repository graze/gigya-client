<?php

namespace Graze\Gigya\Test\Unit\Response;

use DateTime;
use Graze\Gigya\Response\Response;
use Graze\Gigya\Test\TestCase;
use Graze\Gigya\Test\TestFixtures;
use GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;
use Mockery as m;

class ResponseTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        date_default_timezone_set('UTC');
    }

    public function testDateFormat()
    {
        $time = DateTime::createFromFormat(Response::DATE_TIME_FORMAT, '2015-03-22T11:42:25.943Z');

        static::assertInstanceOf('DateTimeInterface', $time);
        static::assertEquals('2015', $time->format('Y'));
        static::assertEquals('03', $time->format('m'));
        static::assertEquals('22', $time->format('d'));
        static::assertEquals('11', $time->format('H'));
        static::assertEquals('42', $time->format('i'));
        static::assertEquals('25', $time->format('s'));
        static::assertEquals('943000', $time->format('u'));
        static::assertEquals('Z', $time->getTimezone()->getName());
    }

    public function testOtherTimeZoneFormat()
    {
        $time = DateTime::createFromFormat(Response::DATE_TIME_FORMAT, '2015-03-22T11:42:25.943+02:00');

        static::assertInstanceOf('DateTimeInterface', $time);
        static::assertEquals('2015', $time->format('Y'));
        static::assertEquals('03', $time->format('m'));
        static::assertEquals('22', $time->format('d'));
        static::assertEquals('11', $time->format('H'));
        static::assertEquals('42', $time->format('i'));
        static::assertEquals('25', $time->format('s'));
        static::assertEquals('943000', $time->format('u'));
        static::assertEquals('+02:00', $time->getTimezone()->getName());
    }

    public function testToString()
    {
        $guzzleResponse = m::mock(GuzzleResponseInterface::class);
        $guzzleResponse->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('accounts.getAccountInfo'));
        $response = new Response($guzzleResponse);

        static::assertRegExp('/Response: \d+: \w+ - \d+: .+\n.+/s', $response->__toString());
    }

    public function testToStringForFailure()
    {
        $guzzleResponse = m::mock(GuzzleResponseInterface::class);
        $guzzleResponse->shouldReceive('getBody')->andReturn(TestFixtures::getFixture('failure_403'));
        $response = new Response($guzzleResponse);

        static::assertRegExp('/Response: \d+: \w+ - \d+: .+\n.+\n.+\n.+/s', $response->__toString());
    }
}
