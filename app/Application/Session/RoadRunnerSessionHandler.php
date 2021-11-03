<?php
declare(strict_types=1);

namespace App\Session;

use Psr\SimpleCache\CacheInterface;
use SessionHandlerInterface;

class RoadRunnerSessionHandler implements SessionHandlerInterface
{
    public function __construct(private CacheInterface $storage, private int $minutes)
    {
    }

    /** {@inheritdoc} */
    public function open($savePath, $sessionName)
    {
        return true;
    }

    /** {@inheritdoc} */
    public function close()
    {
        return true;
    }

    /** {@inheritdoc} */
    public function read($sessionId)
    {
        return $this->storage->get($sessionId, '');
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        return $this->storage->set($sessionId, $data, $this->minutes * 60);
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        return $this->storage->delete($sessionId);
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime)
    {
        return true;
    }
}
