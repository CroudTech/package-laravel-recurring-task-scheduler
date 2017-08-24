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
     */
    public function testUpdate($definition, $expected)
    {
        $this->migrate();
        $repository = $this->app->make(\CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract::class);
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable(['name' => __CLASS__ . '::' . __METHOD__]);
        $scheduleable->save();
        $definition_array = json_decode($definition, true);
        $definition_array['scheduleable_id'] = $scheduleable->id;
        $definition_array['scheduleable_type'] = get_class($scheduleable);
        $schedule = $repository->createFromScheduleDefinition($definition_array, $scheduleable);
        $old_range_end = $definition_array['range']['end'];
        $definition_array['range']['end'] = Carbon::parse($old_range_end)->addMonth('1')->setTime(23, 59, 59)->format('c');
        $this->json('PUT', route('schedule.update', ['schedule' => $schedule->id]), $definition_array);

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

        $returned_range_end = Carbon::parse($this->response->getData()->data->range->end->date)->timezone($this->response->getData()->data->range->end->timezone)->format('c');
        $this->assertNotEquals(Carbon::parse($old_range_end)->setTime(23, 59, 59)->format('c'), $returned_range_end);
        $this->assertEquals(Carbon::parse($definition_array['range']['end'])->format('c'), $returned_range_end);
    }

    /**
     * Test destroy route method
     *
     */
    public function testDestroy()
    {
        $this->migrate();
        $repository = $this->app->make(\CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract::class);
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable(['name' => __CLASS__ . '::' . __METHOD__]);
        $scheduleable->save();
        $definition_array['scheduleable_id'] = $scheduleable->id;
        $definition_array['scheduleable_type'] = get_class($scheduleable);
        $definition_array['range'] = [
            'start' => Carbon::now(),
            'end' => Carbon::now()->addMonth(1),
        ];
        $definition_array['type'] = 'periodic';
        $definition_array['interval'] = 1;
        $definition_array['period'] = 'days';
        $schedule = $repository->createFromScheduleDefinition($definition_array, $scheduleable);

        $this->assertNotNull(\CroudTech\RecurringTaskScheduler\Model\Schedule::find($schedule->id));
        $this->json('DELETE', route('schedule.destroy', ['schedule' => $schedule->id]));
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $this->response);
        $this->assertResponseStatus(200);
        $this->assertNull(\CroudTech\RecurringTaskScheduler\Model\Schedule::find($schedule->id));
        $this->assertNotNull(\CroudTech\RecurringTaskScheduler\Model\Schedule::withTrashed()->where('id', $schedule->id)->first());
    }

    /**
     * Test destroy route method
     */
    public function testGet()
    {
        $this->migrate();
        $repository = $this->app->make(\CroudTech\RecurringTaskScheduler\Contracts\ScheduleRepositoryContract::class);
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable(['name' => __CLASS__ . '::' . __METHOD__]);
        $scheduleable->save();
        $definition_array['scheduleable_id'] = $scheduleable->id;
        $definition_array['scheduleable_type'] = get_class($scheduleable);
        $definition_array['range'] = [
            'start' => Carbon::now(),
            'end' => Carbon::now()->addMonth(1),
        ];
        $definition_array['type'] = 'periodic';
        $definition_array['interval'] = 1;
        $definition_array['period'] = 'days';
        $schedule = $repository->createFromScheduleDefinition($definition_array, $scheduleable);

        $this->assertNotNull(\CroudTech\RecurringTaskScheduler\Model\Schedule::find($schedule->id));
        $this->json('GET', route('schedule.show', ['schedule' => $schedule->id]));
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
        $this->assertEquals($schedule->id, $this->response->getData()->data->id);
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
