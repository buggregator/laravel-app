<?php

declare(strict_types=1);

namespace Infrastructure\RoadRunner\Cache;

use Illuminate\Cache\Lock;
use Spiral\RoadRunner\KeyValue\StorageInterface;

final class RoadRunnerLock extends Lock
{
    public function __construct(
        private StorageInterface $storage,
        string $name,
        int $seconds,
        string|null $owner = null
    ) {
        parent::__construct($name, $seconds, $owner);
    }

    /**
     * Attempt to acquire the lock.
     *
     * @return bool
     */
    public function acquire()
    {
        return $this->storage->set(
            $this->name,
            $this->owner,
            $this->seconds
        );
    }

    /**
     * Release the lock.
     *
     * @return bool
     */
    public function release()
    {
        if ($this->isOwnedByCurrentProcess()) {
            return $this->storage->delete($this->name);
        }

        return false;
    }

    /**
     * Releases this lock in disregard of ownership.
     *
     * @return void
     */
    public function forceRelease()
    {
        $this->storage->delete($this->name);
    }

    /**
     * Returns the owner value written into the driver for this lock.
     *
     * @return mixed
     */
    protected function getCurrentOwner()
    {
        return $this->storage->get($this->name);
    }
}
