<?php

namespace Mix\JsonRpc\Client;

use Mix\JsonRpc\Client\Exception\ConnectionException;
use Mix\JsonRpc\Client\Exception\ReadException;
use Mix\JsonRpc\Client\Exception\WriteException;

/**
 * Class JsonRpcTcpClient
 * @package Mix\JsonRpc\Client
 * @author liu,jian <coder.keda@gmail.com>
 */
class JsonRpcTcpClient
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
    public $timeout;

    /**
     * @var string
     */
    protected static $eof = "\r\n";

    /**
     * 使用静态方法创建实例
     * @param $host
     * @param $port
     * @return $this
     */
    public static function new($host, $port, $timeout = 5)
    {
        return new static($host, $port, $timeout);
    }

    /**
     * JsonRpcTcpClient constructor.
     * @param $host
     * @param $port
     */
    public function __construct($host, $port, $timeout = 5)
    {
        $this->host    = $host;
        $this->port    = $port;
        $this->timeout = $timeout;
        $this->connect();
    }

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
     * 执行方法
     * @param $method
     * @param $params
     * @param int $id
     * @return array
     */
    public function call($method, $params, $id = 0)
    {
        $ret = fwrite($this->connection, json_encode([
                'method' => $method,
                'params' => $params,
                'id'     => $id,
            ]) . static::$eof);
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

    /**
     * 析构
     */
    public function __destruct()
    {
        fclose($this->connection);
    }

}
