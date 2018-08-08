<?php
namespace CroudTech\RecurringTaskScheduler\Tests;

use CroudTech\RecurringTaskScheduler\RecurringTaskSchedulerServiceProvider;
use Illuminate\Database\Capsule\Manager as Capsule;
use Laravel\BrowserKitTesting\TestCase as LaravelTestCase;

abstract class TestCase extends LaravelTestCase
{
    protected $test_root;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Constructs a test case with the given name.
     *
     * @param string $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->test_root = __DIR__;
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Create the test database schema
     *
     * @method migrate
     *
     */
    protected function migrate()
    {
        \Illuminate\Support\Facades\Schema::dropIfExists('test_scheduleables');
        \Illuminate\Support\Facades\Schema::create(
            'test_scheduleables',
            function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->boolean('test_success')->default(false);
                $table->timestamps();
            }
        );
    }
}
