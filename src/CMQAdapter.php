<?php
/**
 * @copyright Copyright (c) 2018 Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Queue\TencentCloudCMQ;

use TencentCloudCMQ\AsyncCallback;
use TencentCloudCMQ\Client;
use TencentCloudCMQ\Http\Promise;
use TencentCloudCMQ\Queue;
use TencentCloudCMQ\Requests\BatchDeleteMessageRequest;
use TencentCloudCMQ\Requests\BatchReceiveMessageRequest;
use TencentCloudCMQ\Requests\BatchSendMessageRequest;
use TencentCloudCMQ\Requests\SendMessageRequest;
use TencentCloudCMQ\Responses\BatchDeleteMessageResponse;
use TencentCloudCMQ\Responses\BatchReceiveMessageResponse;
use TencentCloudCMQ\Responses\BatchSendMessageResponse;
use TencentCloudCMQ\Responses\DeleteMessageResponse;
use TencentCloudCMQ\Responses\ReceiveMessageResponse;
use TencentCloudCMQ\Responses\SendMessageResponse;

/**
 * Class CMQAdapter
 *
 * @method string getUsing()
 * @method SendMessageResponse sendMessage(SendMessageRequest $request)
 * @method Promise sendMessageAsync(SendMessageRequest $request, AsyncCallback $callback = null)
 * @method Promise peekMessageAsync(AsyncCallback $callback = null)
 * @method ReceiveMessageResponse receiveMessage($waitSeconds = null)
 * @method Promise receiveMessageAsync(AsyncCallback $callback = null)
 * @method DeleteMessageResponse deleteMessage(string $receiptHandle)
 * @method Promise deleteMessageAsync(string $receiptHandle, AsyncCallback $callback = null)
 * @method BatchSendMessageResponse batchSendMessage(BatchSendMessageRequest $request)
 * @method Promise batchSendMessageAsync(BatchSendMessageRequest $request, AsyncCallback $callback = null)
 * @method BatchReceiveMessageResponse batchReceiveMessage(BatchReceiveMessageRequest $request)
 * @method Promise batchReceiveMessageAsync(BatchReceiveMessageRequest $request, AsyncCallback $callback = null)
 * @method BatchDeleteMessageResponse batchDeleteMessage(BatchDeleteMessageRequest $request)
 * @method Promise batchDeleteMessageAsync(BatchDeleteMessageRequest $request, AsyncCallback $callback = null)
 */
class CMQAdapter
{
    /**
     * QCloud CMQ Client
     *
     * @var Client
     */
    private $client;

    /**
     * QCloud CMQ SDK Queue.
     *
     * @var Queue
     */
    private $queue;

    /**
     * @var string
     */
    private $using;

    /**
     * CMQAdapter constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->queue, $method], $parameters);
    }

    /**
     * @param string $queue
     *
     * @return self
     */
    public function useQueue($queue)
    {
        if ($this->using != $queue) {
            $this->using = $queue;
            $this->queue = $this->client->getQueueRef($queue);
        }
        return $this;
    }
}
