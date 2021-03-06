<?php
/**
 * @copyright Copyright (c) 2018 Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Queue\TencentCloudCMQ;

use Illuminate\Contracts\Queue\Queue;
use Illuminate\Support\Arr;
use TencentCloudCMQ\Client;
use Illuminate\Queue\Connectors\ConnectorInterface;

/**
 * Class CMQConnector
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class CMQConnector implements ConnectorInterface
{
    /**
     * Establish a queue connection.
     *
     * @param array $config
     *
     * @return Queue
     */
    public function connect(array $config)
    {
        return new CMQQueue($this->getAdaptor($config), $config['queue'], Arr::get($config, 'wait_seconds'));
    }

    /**
     * @param array $config
     *
     * @return Client
     */
    protected function getClient(array $config)
    {
        return new Client($config['endpoint'], $config['secret_Id'], $config['secret_Key']);
    }

    /**
     * @param array $config
     *
     * @return CMQAdapter
     */
    protected function getAdaptor(array $config)
    {
        return new CMQAdapter($this->getClient($config));
    }
}
