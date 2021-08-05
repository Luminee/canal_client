<?php

namespace Lib\Core;

use Exception;
use Lib\Canal\CanalClient;
use Lib\Canal\CanalConnectorFactory;
use Lib\Canal\Adapter\CanalConnectorBase;

class Client
{
    protected $parser;

    protected $conf;

    public function __construct()
    {
        $this->parser = new Parser();
        $this->conf = config('canal.server');
    }

    /**
     * @return CanalConnectorBase
     * @throws Exception
     */
    protected function connect()
    {
        $client = CanalConnectorFactory::createClient(CanalClient::TYPE_SOCKET_CLUE);
        $client->connect($this->conf['host'], $this->conf['port']);
        $client->checkValid();
        $client->subscribe($this->conf['client_id'], $this->conf['destination'], $this->conf['filter']);
        return $client;
    }

    /**
     * @param CanalConnectorBase $client
     * @param $timer
     * @return CanalConnectorBase
     * @throws Exception
     */
    protected function reconnect(CanalConnectorBase $client, &$timer)
    {
        $timer = 0;
        app()->setVar('reconnect', true);
        $client->disConnect();
        return $this->connect();
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        $client = $this->connect();
        for ($timer = 0; true; $timer++) {
            $message = $client->get(100);
            if ($entries = $message->getEntries()) {
                foreach ($entries as $entry) {
                    $this->process($entry);
                }
            }
            if ($timer > 1800) $client = $this->reconnect($client, $timer);
            sleep(1);
        }
    }

    /**
     * @param $entry
     * @throws Exception
     */
    protected function process($entry)
    {
        $res = $this->parser->parseEntry($entry);
        if (is_null($res)) return;
        list($index, $rowChange) = $res;

        /** @var Subscriber $subscriber */
        foreach (app()->getSubscribers() as $subscriber) {
            $subscriber->handle($index, $rowChange);
        }
    }

}