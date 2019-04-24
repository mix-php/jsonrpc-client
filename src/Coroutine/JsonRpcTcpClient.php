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
     * 初始化事件
     */
    public function onInitialize()
    {
        // 启用协程钩子
        Coroutine::enableHook();
    }

}
