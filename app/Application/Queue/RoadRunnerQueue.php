<?php
declare(strict_types=1);

namespace App\Queue;

use Closure;
use DateTimeInterface;
use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\CallQueuedClosure;
use Illuminate\Queue\Queue;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;
use Spiral\Goridge\RPC\RPCInterface;
use Spiral\RoadRunner\Jobs\DTO\V1\Stat;
use Spiral\RoadRunner\Jobs\DTO\V1\Stats;
use Spiral\RoadRunner\Jobs\Jobs;
use Spiral\RoadRunner\Jobs\QueueInterface;

class RoadRunnerQueue extends Queue implements QueueContract
{
    public function __construct(
        private Jobs         $jobs,
        private RPCInterface $rpc,
        private              $default = 'default',
    )
    {
    }

    /** @inheritDoc */
    public function push($job, $data = '', $queue = null): string
    {
        return $this->enqueueUsing(
            $job,
            $this->createPayload($job, $queue, $data),
            $queue,
            null,
            function ($payload, $queue) {
                return $this->pushRaw($payload, $queue);
            }
        );
    }

    /** @inheritDoc */
    public function pushRaw($payload, $queue = null, array $options = []): string
    {
        assert(is_array($payload), 'Payload should be an array');

        $queue = $this->getQueue($queue);

        $task = $queue->dispatch(
            $queue->create($payload['displayName'] ?? Uuid::uuid4()->toString())
                ->withValue($payload)
        );

        return $task->getId();
    }

    /** @inheritDoc */
    public function later($delay, $job, $data = '', $queue = null): string
    {
        return $this->enqueueUsing(
            $job,
            $this->createPayload($job, $queue, $data),
            $queue,
            $delay,
            function ($payload, $queue) use ($delay) {
                return $this->laterRaw($delay, $payload, $queue);
            }
        );
    }

    /**
     * Push a raw job onto the queue after a delay.
     *
     * @param DateTimeInterface|\DateInterval|int $delay
     * @param string $payload
     * @param string|null $queue
     * @return mixed
     */
    private function laterRaw($delay, $payload, $queue = null): string
    {
        assert(is_array($payload), 'Payload should be an array');

        $queue = $this->getQueue($queue);

        $task = $queue->dispatch(
            $queue->create($payload['displayName'] ?? Uuid::uuid4()->toString())
                ->withValue($payload)
                ->withDelay($this->availableAt($delay))
        );

        return $task->getId();
    }

    public function pop($queue = null)
    {
        // TODO: Implement pop() method.
    }

    protected function createPayload($job, $queue, $data = ''): array
    {
        if ($job instanceof Closure) {
            $job = CallQueuedClosure::create($job);
        }

        return $this->createPayloadArray($job, $queue, $data);
    }

    /**
     * Get the "available at" UNIX timestamp.
     *
     * @param DateTimeInterface|\DateInterval|int $delay
     * @return int
     */
    protected function availableAt($delay = 0): int
    {
        $delay = $this->parseDateInterval($delay);

        return $delay instanceof DateTimeInterface
            ? Carbon::parse($delay)->diffInSeconds()
            : $delay;
    }

    private function getQueue(?string $queue = null): QueueInterface
    {
        $queue = $this->jobs->connect($queue ?? $this->default);

        if (!$this->getStats($queue->getName())->getReady()) {
            $queue->resume();
        }

        return $queue;
    }

    public function size($queue = null): int
    {
        $stats = $this->getStats($queue);

        return $stats->getActive() + $stats->getDelayed();
    }

    private function getStats(?string $queue = null): Stat
    {
        $queue = $queue ?? $this->default;

        $stats = $this->rpc->call('jobs.Stat', new Stats(), Stats::class)->getStats();

        /** @var Stat $stat */
        foreach ($stats as $stat) {
            if ($stat->getQueue() === $queue) {
                return $stat;
            }
        }

        return new Stat();
    }
}
