<?php

namespace Nadge\AwsWorker\Integrations;

use Nadge\AwsWorker\Wrappers\DefaultWorker;
use Nadge\AwsWorker\Wrappers\Laravel53Worker;
use Nadge\AwsWorker\Wrappers\WorkerInterface;
use Nadge\PlainSqs\Sqs\Connector;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\QueueManager;

/**
 * Class CustomQueueServiceProvider
 * @package App\Providers
 */
class LaravelServiceProvider extends ServiceProvider
{
    use BindsWorker;

    /**
     * @return void
     */
    public function register()
    {
        if (function_exists('env') && ! env('REGISTER_WORKER_ROUTES', true)) return;

        $this->bindWorker();
        $this->addRoutes();
    }

    /**
     * @return void
     */
    protected function addRoutes()
    {
        $this->app['router']->post('/worker/schedule', 'Nadge\AwsWorker\Controllers\WorkerController@schedule');
        $this->app['router']->post('/worker/queue', 'Nadge\AwsWorker\Controllers\WorkerController@queue');
    }

    /**
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(QueueManager::class, function() {
            return new QueueManager($this->app);
        });
    }
}
