<?php
/**
 * @copyright Copyright (c) 2018 Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Queue\TencentCloudCMQ;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use TencentCloudCMQ\Client;

/**
 * Class CMQFlushCommand
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class CMQFlushCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'queue:cmq:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush CMQ Queue';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $queue = $this->argument('queue');
        $connection = $this->option('connection');
        $config = config("queue.connections.{$connection}");
        if (!$queue) {
            $queue = $config['queue'];
        }
        $client = new Client($config['endpoint'], $config['secret_Id'], $config['secret_Key']);
        $queue = $client->getQueueRef($queue);
        $hasMessage = true;
        while ($hasMessage) {
            $response = $queue->batchReceiveMessage(16);
            $handles = [];

            foreach ($response->getMessages() as $message) {
                $handles[] = $message->getReceiptHandle();
            }
            $response = $queue->batchDeleteMessage($handles);
            if ($response->isSucceed()) {
                foreach ($handles as $handle) {
                    $this->info(sprintf("The message: %s deleted success", $handle));
                }
            }
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['queue', InputArgument::OPTIONAL, 'The queue name'],
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['connection', 'c', InputOption::VALUE_OPTIONAL, 'The Queue connection name', 'cmq']
        ];
    }
}
