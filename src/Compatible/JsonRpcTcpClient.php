<?php

namespace Mix\JsonRpc\Client\Compatible;

use Mix\JsonRpc\Client\Base\AbstractJsonRpcTcpClient;

/**
 * Class JsonRpcTcpClient
 * @package Mix\JsonRpc\Client
 * @author liu,jian <coder.keda@gmail.com>
 */
class JsonRpcTcpClient extends AbstractJsonRpcTcpClient
{

    /**
     * JsonRpcTcpClient constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        // 导入配置
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * 析构
     */
    public function __destruct()
    {
        $this->disconnect();
    }

}
