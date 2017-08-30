<?php
namespace CroudTech\RecurringTaskScheduler\Tests\ModelTests;

use Carbon\Carbon;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Periodic as PeriodicParser;
use CroudTech\RecurringTaskScheduler\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NestedScheduleRouteTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test index resource route
     *
     */
    public function testIndex()
    {
        $this->migrate();
        $schedules = [];
        $schedules[] = $this->getSchedule();
        $schedules[] = $this->getSchedule();

        foreach ($schedules as $schedule) {
            $this->json('GET', route('schedule.schedule-event.index', ['schedule' => $schedule->id]), ['per_page' => 500]);
            $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $this->response);

            $this->seeJsonStructure([
                'data' => [
                    '*' => [
                        "id",
                        "schedule_id",
                        "original_date",
                        "date",
                        "triggered_at",
                        "trigger_success",
                        "modified",
                        "created_at",
                        "updated_at",
                        "deleted_at",
                    ],
                ],
            ]);
            $this->assertNotEmpty($this->response->getData()->data);
            foreach ($this->response->getData()->data as $returned_schedule_event) {
                $this->assertEquals($schedule['id'], intval($returned_schedule_event->schedule_id));
            }
        }
    }

    /**
     * Test show route
     *
     * @return void
     */
    public function testShow()
    {
        $this->migrate();
        $schedules = collect();
        $schedules[] = $this->getSchedule();
        $schedules[] = $this->getSchedule();


        $this->json('GET', route('schedule.schedule-event.show', ['schedule' => $schedules->first()->id, 'schedule-event' => $schedules->first()->scheduleEvents->last()->id]));
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $this->response);
        $this->seeJsonStructure([
            'data' => [
                "id",
                "schedule_id",
                "original_date",
                "date",
                "triggered_at",
                "trigger_success",
                "modified",
                "created_at",
                "updated_at",
                "deleted_at",
            ],
        ]);
        $this->assertNotEmpty($this->response->getData()->data);
        $this->assertEquals($schedules->first()['id'], intval($this->response->getData()->data->schedule_id));

        $this->json('GET', $route = route('schedule.schedule-event.show', ['schedule' => $schedules->last()->id, 'schedule-event' => $schedules->first()->scheduleEvents->last()->id]));
        $this->assertResponseStatus(404);
    }

    /**
     * Test destroy route
     *
     * @return void
     */
    public function testDestroy()
    {
        $this->migrate();
        $schedules = collect();
        $schedules[] = $this->getSchedule();
        $schedules[] = $this->getSchedule();


        $this->json('DELETE', route('schedule.schedule-event.destroy', ['schedule' => $schedules->first()->id, 'schedule-event' => $schedules->first()->scheduleEvents->last()->id]));
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $this->response);
        $this->assertRegExp('/([^\:]+)::([0-9]+) deleted/', $this->response->getData()->message);
        $this->assertEquals(true, $this->response->getData()->success);

        $this->json('DELETE', route('schedule.schedule-event.destroy', ['schedule' => $schedules->first()->id, 'schedule-event' => $schedules->first()->scheduleEvents->last()->id]));
        $this->assertResponseStatus(404);
    }

    /**
     * Test update route
     *
     * @return void
     */
    public function testStore()
    {
        $this->migrate();
        $schedules = collect();
        $schedules[] = $this->getSchedule();
        $schedules[] = $this->getSchedule();

        $request_array = [
            'date' => '2017-01-01 00:12:30',
        ];

        $this->json('POST', route('schedule.schedule-event.store', ['schedule' => $schedules->first()->id]), $request_array);
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $this->response);
        $this->assertEquals($schedules->first()->id, $this->response->getData()->data->schedule_id);
        $this->seeJsonStructure([
            'data' => [
                "id",
                "schedule_id",
                "original_date",
                "date",
                "triggered_at",
                "trigger_success",
                "modified",
                "created_at",
                "updated_at",
                "deleted_at",
            ],
        ]);
    }

    /**
     * Test update route
     *
     * @return void
     */
    public function testUpdate()
    {
        $this->migrate();
        $schedules = collect();
        $schedules[] = $this->getSchedule();
        $schedules[] = $this->getSchedule();

        $request_array = [
            'date' => '2017-01-01 00:12:30',
        ];

        $schedule_event_1 = $schedules->first()->scheduleEvents()->first();
        $schedule_event_2 = $schedules->last()->scheduleEvents()->first();

        $this->json('PUT', route('schedule.schedule-event.update', ['schedule' => $schedules->first()->id, $schedule_event_1->id]), $request_array);
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $this->response);
        $this->assertEquals($schedules->first()->id, $this->response->getData()->data->schedule_id);
        $this->assertEquals($schedule_event_1->id, $this->response->getData()->data->id);
        $this->assertEquals('2017-01-01 00:12:30', $this->response->getData()->data->date);
        $this->seeJsonStructure([
            'data' => [
                "id",
                "schedule_id",
                "original_date",
                "date",
                "triggered_at",
                "trigger_success",
                "modified",
                "created_at",
                "updated_at",
                "deleted_at",
            ],
        ]);

        $this->json('PUT', route('schedule.schedule-event.update', ['schedule' => $schedules->last()->id, $schedule_event_1->id]), $request_array);
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $this->response);
        $this->assertResponseStatus(404);
    }


    /**
     * Undocumented function
     *
     * @return \CroudTech\RecurringTaskScheduler\Model\Schedule
     */
    protected function getSchedule() : \CroudTech\RecurringTaskScheduler\Model\Schedule
    {
        $scheduleable = new \CroudTech\RecurringTaskScheduler\Tests\App\Model\TestScheduleable(['name' => __CLASS__ . '::' . __METHOD__]);
        $scheduleable->save();
        $schedule = new \CroudTech\RecurringTaskScheduler\Model\Schedule();
        $schedule->range_start = '2017-01-01 00:00:00';
        $schedule->range_end = '2017-01-07 00:00:00';
        $schedule->period = 'days';
        $schedule->interval = '1';
        $schedule->scheduleable()->associate($scheduleable);
        $schedule->save();

        return $schedule;
    }
}
