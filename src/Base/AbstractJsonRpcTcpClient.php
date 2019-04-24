<?php

namespace Mix\JsonRpc\Client\Base;

use Mix\JsonRpc\Client\Exception\ConnectionException;
use Mix\JsonRpc\Client\Exception\ReadException;
use Mix\JsonRpc\Client\Exception\WriteException;

/**
 * Class AbstractJsonRpcTcpClient
 * @package Mix\JsonRpc\Client\Base
 * @author liu,jian <coder.keda@gmail.com>
 */
abstract class AbstractJsonRpcTcpClient
{

    /**
     * @var resource
     */
    public $connection;

    /**
     * @var string
     */
    public $host;

    /**
     * @var int
     */
    public $port;

    /**
     * @var int
     */
    public $timeout = 5;

    /**
     * @var string
     */
    public $eof = "\r\n";

    /**
     * 连接
     * @return bool
     */
    protected function connect()
    {
        $fp = stream_socket_client("tcp://{$this->host}:{$this->port}", $errno, $errstr, $this->timeout);
        if (!$fp) {
            throw new ConnectionException("JsonRPC tcp client connection failed, [$errno] {$errstr}");
        }
        $this->connection = $fp;
        return true;
    }

    /**
     * 自动连接
     */
    protected function autoConnect()
    {
        if (isset($this->connection)) {
            return;
        }
        $this->connect();
    }

    /**
     * 关闭连接
     * @return bool
     */
    public function disconnect()
    {
        if (!isset($this->connection)) {
            return false;
        }
        $ret              = fclose($this->connection);
        $this->connection = null;
        return $ret;
    }

    /**
     * 执行方法
     * @param $method
     * @param $params
     * @param int $id
     * @return array
     */
    public function call($method, $params, $id = 0)
    {
        $this->autoConnect();
        $ret = fwrite($this->connection, json_encode([
                'method' => $method,
                'params' => $params,
                'id'     => $id,
            ]) . $this->eof);
        if ($ret === false) {
            throw new WriteException('JsonRPC tcp client write failed.');
        }
        stream_set_timeout($this->connection, $this->timeout);
        $line = fgets($this->connection);
        if ($line === false) {
            throw new ReadException('JsonRPC tcp client read failed.');
        }
        return json_decode($line, true);
    }

}
