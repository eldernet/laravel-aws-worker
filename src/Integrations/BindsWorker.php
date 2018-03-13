<?php

namespace Nadge\AwsWorker\Integrations;

use Nadge\AwsWorker\Wrappers\WorkerInterface;
use Nadge\AwsWorker\Wrappers\DefaultWorker;
use Nadge\AwsWorker\Wrappers\Laravel53Worker;

/**
 * Class BindsWorker
 * @package Nadge\AwsWorker\Integrations
 */
trait BindsWorker
{
    /**
     * @var array
     */
    protected $workerImplementations = [
        '5\.[3456]\.\d+' => Laravel53Worker::class
    ];

    /**
     * @param $version
     * @return mixed
     */
    protected function findWorkerClass($version)
    {
        foreach ($this->workerImplementations as $regexp => $class) {
            if (preg_match('/' . $regexp . '/', $version)) return $class;
        }

        return DefaultWorker::class;
    }

    /**
     * @return void
     */
    protected function bindWorker()
    {
        $this->app->bind(WorkerInterface::class, $this->findWorkerClass($this->app->version()));
    }
}
