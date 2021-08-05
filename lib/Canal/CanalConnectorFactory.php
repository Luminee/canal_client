<?php
namespace Lib\Canal;

use Lib\Canal\Adapter\CanalConnectorBase;

class CanalConnectorFactory
{
    private function __construct()
    {

    }

    /**
     * @param $clientType
     * @return CanalConnectorBase
     * @throws \Exception
     */
    public static function createClient($clientType)
    {
        switch($clientType){
            case CanalClient::TYPE_SOCKET:
                return new \Lib\Canal\Adapter\Socket\CanalConnector();
            case CanalClient::TYPE_SWOOLE:
                return new \Lib\Canal\Adapter\Swoole\CanalConnector();
            case CanalClient::TYPE_SOCKET_CLUE:
                return new \Lib\Canal\Adapter\Clue\CanalConnector();
            default:
                throw new \Exception("Unknown client type");
        }
    }
}