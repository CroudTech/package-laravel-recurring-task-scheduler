<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ModelTests;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ScheduleRouteTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test index resource route
     *
     */
    public function testIndex()
    {
        $this->migrate();
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable(['name' => __CLASS__ . '::' . __METHOD__]);
        $scheduleable->save();
        $schedule = new \CroudTech\RecurringTaskScheduler\Model\Schedule();
        $schedule->range_start = '2017-01-01 00:00:00';
        $schedule->range_end = '2017-08-01 00:00:00';
        $schedule->period = 'days';
        $schedule->scheduleable()->associate($scheduleable);
        $schedule->save();

        $this->json('GET', route('schedule.index'), []);
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $this->response);
        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'days',
                    'months',
                    'period',
                    'interval',
                    'type',
                    'range' => [
                        'start',
                        'end',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Test create method
     *
     * @dataProvider definitionsProvider
     * @group DEV
     */
    public function testStore($definition, $expected)
    {
        $this->migrate();
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable(['name' => __CLASS__ . '::' . __METHOD__]);
        $scheduleable->save();
        $definition_array = json_decode($definition, true);
        $definition_array['scheduleable_id'] = $scheduleable->id;
        $definition_array['scheduleable_type'] = get_class($scheduleable);

        $this->json('POST', route('schedule.store'), $definition_array);
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $this->response);
        $this->assertResponseStatus(200);
        $this->seeJsonStructure([
            'data' => [
                'id',
                'days',
                'months',
                'period',
                'interval',
                'type',
                'range' => [
                    'start',
                    'end',
                ],
            ],
        ]);

        $this->assertFalse(array_key_exists('all_schedule_events', $this->response->getData()->data));
        $this->assertFalse(array_key_exists('future_schedule_events', $this->response->getData()->data));
        $this->assertFalse(array_key_exists('past_schedule_events', $this->response->getData()->data));
    }

    /**
     * Test create method
     *
     * @dataProvider definitionsProvider
     * @group DEV
     */
    public function testStoreWithAllEventsIncluded($definition, $expected)
    {
        $this->migrate();
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable(['name' => __CLASS__ . '::' . __METHOD__]);
        $scheduleable->save();
        $definition_array = json_decode($definition, true);
        $definition_array['scheduleable_id'] = $scheduleable->id;
        $definition_array['scheduleable_type'] = get_class($scheduleable);

        $this->json('POST', route('schedule.store', ['include' => 'all_schedule_events']), $definition_array);
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $this->response);
        $this->assertResponseStatus(200);
        $this->seeJsonStructure([
            'data' => [
                'id',
                'days',
                'months',
                'period',
                'interval',
                'type',
                'range' => [
                    'start',
                    'end',
                ],
                'all_schedule_events' => [
                    'data' => [
                        '*' => [
                            'id',
                            'date',
                            'triggered_at',
                            'trigger_success',
                            'modified',
                        ]
                    ],
                ],
            ],
        ]);

        $this->assertFalse(array_key_exists('future_schedule_events', $this->response->getData()->data));
        $this->assertFalse(array_key_exists('past_schedule_events', $this->response->getData()->data));
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
