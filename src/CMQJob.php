<?php
/**
 * @copyright Copyright (c) 2018 Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Queue\TencentCloudCMQ;

use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Job as JobContract;
use Illuminate\Queue\Jobs\Job;
use TencentCloudCMQ\Responses\ReceiveMessageResponse;

/**
 * Class CMQJob
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class CMQJob extends Job implements JobContract
{
    /**
     * The class name of the job.
     *
     * @var string
     */
    protected $job;
    /**
     * The queue message data.
     *
     * @var string
     */
    protected $data;
    /**
     * @var CMQAdapter
     */
    private $adapter;

    /**
     * Create a new job instance.
     *
     * @param Container $container
     * @param CMQAdapter $mns
     * @param string $queue
     * @param ReceiveMessageResponse $job
     */
    public function __construct(Container $container, CMQAdapter $mns, $queue, ReceiveMessageResponse $job)
    {
        $this->container = $container;
        $this->adapter = $mns;
        $this->queue = $queue;
        $this->job = $job;
    }

    /**
     * Fire the job.
     */
    public function fire()
    {
        if (method_exists($this, 'resolveAndFire')) {
            $payload = json_decode($this->getRawBody(), true);
            if (!is_array($payload)) {
                throw new \InvalidArgumentException("Seems it's not a Laravel enqueued job. [$payload]");
            }
            $this->resolveAndFire($payload);
        } else {
            parent::fire();
        }
    }

    /**
     * Get the raw body string for the job.
     *
     * @return string
     */
    public function getRawBody()
    {
        return $this->job->getMessageBody();
    }

    /**
     * Delete the job from the queue.
     */
    public function delete()
    {
        parent::delete();
        $receiptHandle = $this->job->getReceiptHandle();
        $this->adapter->deleteMessage($receiptHandle);
    }

    /**
     * Release the job back into the queue.
     *
     * @param int $delay
     */
    public function release($delay = 1)
    {
        parent::release($delay);
        if ($delay < 1) {
            $delay = 1;
        }
        //$this->adapter->changeMessageVisibility($this->job->getReceiptHandle(), $delay);
    }

    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts()
    {
        return (int)$this->job->getDequeueCount();
    }

    /**
     * Get the IoC container instance.
     *
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get the Job ID.
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->job->getMessageId();
    }
}
