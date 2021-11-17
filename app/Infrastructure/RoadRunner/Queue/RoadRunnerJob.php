<?php

declare(strict_types=1);

namespace Infrastructure\RoadRunner\Queue;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Queue\Job as JobContract;
use Illuminate\Queue\Jobs\Job;
use Spiral\RoadRunner\Jobs\Task\ReceivedTaskInterface;

class RoadRunnerJob extends Job implements JobContract
{
    public function __construct(Application $container, private ReceivedTaskInterface $task)
    {
        $this->container = $container;
    }

    /** @inheritDoc */
    public function getJobId(): string
    {
        return $this->task->getId();
    }

    /** @inheritDoc */
    public function getRawBody(): string
    {
        return json_encode($this->payload());
    }

    /** @inheritDoc */
    public function payload(): array
    {
        return $this->task->getPayload()[0] ?? [];
    }

    /** @inheritDoc */
    public function attempts(): int
    {
        return (int) $this->task->getHeaderLine('attempts');
    }

    /** @inheritDoc */
    public function fire(): void
    {
        parent::fire();

        $this->task->complete();
    }

    /** @inheritDoc */
    protected function failed($e): void
    {
        $attempts = $this->attempts();

        $this->task
            ->withHeader('attempts', $attempts + 1)
            ->fail($e->getMessage());

        parent::failed($e);
    }
}
