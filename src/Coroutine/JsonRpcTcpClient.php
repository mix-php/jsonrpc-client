<?php

namespace Mix\JsonRpc\Client\Coroutine;

use Mix\Pool\ConnectionTrait;

/**
 * Class JsonRpcTcpClient
 * @package Mix\JsonRpc\Client\Coroutine
 * @author liu,jian <coder.keda@gmail.com>
 */
class JsonRpcTcpClient extends \Mix\JsonRpc\Client\JsonRpcTcpClient
{

    use ConnectionTrait;

}
