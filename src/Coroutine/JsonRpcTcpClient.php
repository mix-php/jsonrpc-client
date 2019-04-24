<?php

namespace Mix\JsonRpc\Client\Coroutine;

use Mix\Core\Coroutine;

/**
 * Class JsonRpcTcpClient
 * @package Mix\JsonRpc\Client\Coroutine
 * @author liu,jian <coder.keda@gmail.com>
 */
class JsonRpcTcpClient extends \Mix\JsonRpc\Client\JsonRpcTcpClient
{

    /**
     * JsonRpcTcpClient constructor.
     * @param $host
     * @param $port
     * @param int $timeout
     */
    public function __construct($host, $port, $timeout = 5)
    {
        Coroutine::enableHook(); // 启用协程钩子
        parent::__construct($host, $port, $timeout);
    }

}
