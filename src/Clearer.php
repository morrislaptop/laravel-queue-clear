<?php namespace Morrislaptop\LaravelQueueClear;

use Illuminate\Queue\QueueManager;
use Illuminate\Contracts\Queue\Factory as FactoryContract;
use Morrislaptop\LaravelQueueClear\Contracts\Clearer as ClearerContract;

class Clearer implements ClearerContract
{
    /**
     * @var QueueManager
     */
    protected $manager;

    /**
     * {@inheritDoc}
     */
    function __construct(FactoryContract $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritDoc}
     */
    public function clear($connection, $queue)
    {
        $count = 0;
        $connection = $this->manager->connection($connection);

        $count += $this->clearJobs($connection, $queue);
        $count += $this->clearJobs($connection, $queue . ':reserved');
        $count += $this->clearDelayedJobs($connection, $queue);

        return $count;
    }

    protected function clearJobs($connection, $queue)
    {
        $count = 0;

        while ($job = $connection->pop($queue)) {
            $job->delete();
            $count++;
        }

        return $count;
    }

    protected function clearDelayedJobs($connection, $queue)
    {
        if (method_exists($connection, 'getRedis')) {
            return $this->clearDelayedJobsOnRedis($connection, $queue);
        }

        throw new \InvalidArgumentException('Queue Connection not supported');
    }

    protected function clearDelayedJobsOnRedis($connection, $queue) {
        $key = "queues:{$queue}:delayed";
        $redis = $connection->getRedis()->connection(config('queue.connections.redis.connection'));
        $count = $redis->zcount($key, '-inf', '+inf');
        $redis->del($key);

        return $count;
    }

}
