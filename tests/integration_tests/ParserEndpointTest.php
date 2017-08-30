<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ModelTests;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParserEndpointTest extends TestCase
{
    /**
     * Check that the parser endpoint returns the correct error code when invalid parameters are passed
     */
    public function testParserGetWithInvalidParameters()
    {
        $route = route('croudtech.schedule.parse');
        $this->json('GET', $route);
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $this->response);
        $this->assertResponseStatus(422);
    }

    /**
     * Check that the parser endpoint returns the expected dates when a definition is passed to it
     *
     * @dataProvider definitionsProvider
     */
    public function testParser($definition, $expected)
    {
        $route = route('croudtech.schedule.parse');
        $this->json('GET', $route, json_decode($definition, true));
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $this->response);
        $this->assertResponseStatus(200);
        $dates = collect((array)$this->response->getData()->data)->map(function ($date) {
            $date_object = new Carbon($date->date, $date->timezone);
            return $date_object->format('c');
        })->toArray();
        $this->assertEquals($dates, $expected);
    }

    /**
     * Load json definitions from data file
     *
     * @return array
     */
    public function definitionsProvider() : array
    {
        return include $this->test_root . '/test_data/json_definitions.php';
    }
}
