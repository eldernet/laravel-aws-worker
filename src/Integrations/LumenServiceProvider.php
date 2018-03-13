<?php

namespace Nadge\AwsWorker\Integrations;

use Nadge\PlainSqs\Sqs\Connector;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\QueueManager;

/**
 * Class CustomQueueServiceProvider
 * @package App\Providers
 */
class LumenServiceProvider extends ServiceProvider
{
    use BindsWorker;

    /**
     * @return void
     */
    public function register()
    {
        if (function_exists('env') && ! env('REGISTER_WORKER_ROUTES', true)) return;

        $this->bindWorker();
        $this->addRoutes(isset( $this->app->router ) ? $this->app->router : $this->app );
    }

    /**
     * @param mixed $router
     * @return void
     */
    protected function addRoutes($router)
    {
        $router->post('/worker/schedule', 'Nadge\AwsWorker\Controllers\WorkerController@schedule');
        $router->post('/worker/queue', 'Nadge\AwsWorker\Controllers\WorkerController@queue');
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