<?php
namespace CroudTech\RecurringTaskScheduler\Http\Controllers;

use CroudTech\RecurringTaskScheduler\Http\Requests\ScheduleParserDefinitionRequest;
use CroudTech\RecurringTaskScheduler\Library\ScheduleParser\Factory as ScheduleParserFactory;
use Illuminate\Http\Request;

class ScheduleParserController extends BaseController
{
    /**
     * Index endpoint
     *
     * @param Request $request
     * @return void
     */
    public function parse(ScheduleParserDefinitionRequest $request, ScheduleParserFactory $parser_factory)
    {
        return $this->sendResponse([
            'data' => $parser_factory->factory($request->all())->getDates(),
        ], 200);
    }
}
