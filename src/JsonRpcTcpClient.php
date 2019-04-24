<?php

namespace Mix\JsonRpc\Client;

use Mix\Core\Bean\ObjectInterface;
use Mix\Core\Bean\ObjectTrait;
use Mix\JsonRpc\Client\Base\AbstractJsonRpcTcpClient;

/**
 * Class JsonRpcTcpClient
 * @package Mix\JsonRpc\Client
 * @author liu,jian <coder.keda@gmail.com>
 */
class JsonRpcTcpClient extends AbstractJsonRpcTcpClient implements ObjectInterface
{

    use ObjectTrait;

    /**
     * 析构事件
     */
    public function onDestruct()
    {
        $this->disconnect();
    }

}
