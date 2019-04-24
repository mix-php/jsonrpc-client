## mix/jsonrpc-client

### 安装

使用 Composer 安装：

```
composer require mix/jsonrpc-client
```

传统调用：

在 TP/Yii/Laravel 等传统框架也可使用。

```
$client = new \Mix\JsonRpc\Client\Compatible\JsonRpcTcpClient([
    'host'    => '127.0.0.1',
    'port'    => 9503,
    'timeout' => 5,
]);
$method = 'hello.world';
$params = [];
$id     = 0;
$ret    = $client->call($method, $params, $id);
var_dump($ret);
```

协程使用：

在 Mix PHP 中使用，可并行获取多个请求结果，性能是传统框架的同步方式无法比拟的。

```
$chan1 = new \Mix\Core\Coroutine\Channel();
xgo(function () use ($chan1) {
    $client = new \Mix\JsonRpc\Client\Coroutine\JsonRpcTcpClient([
        'host'    => '127.0.0.1',
        'port'    => 9503,
        'timeout' => 5,
    ]);
    $method = 'hello.world';
    $params = [];
    $id     = 0;
    $ret    = $client->call($method, $params, $id);
    $chan1->push($ret);
});
$chan2 = new \Mix\Core\Coroutine\Channel();
xgo(function () use ($chan2) {
    $client = new \Mix\JsonRpc\Client\Coroutine\JsonRpcTcpClient([
        'host'    => '127.0.0.1',
        'port'    => 9503,
        'timeout' => 5,
    ]);
    $method = 'hello.world';
    $params = [];
    $id     = 0;
    $ret    = $client->call($method, $params, $id);
    $chan2->push($ret);
});
list($ret1, $ret2) = [$chan1->pop(), $chan2->pop()];
// 可对两次请求的结果做计算并发送给客户端
```

连接池：

与 Database/Redis 池使用方法一至，首先在依赖配置中配置依赖：

```
// 连接池
[
    // 类路径
    'class'      => Mix\JsonRpc\Client\Pool\ConnectionPool::class,
    // 属性
    'properties' => [
        // 最多可空闲连接数
        'maxIdle'   => 5,
        // 最大连接数
        'maxActive' => 50,
        // 拨号器
        'dialer'    => [
            // 依赖引用
            'ref' => beanname(Common\Libraries\Dialers\DatabaseDialer::class),
        ],
    ],
],
// 连接池拨号
[
    // 类路径
    'class' => Common\Libraries\Dialers\JsonRpcTcpClientDialer::class,
],
```

注册组件：

```
// 连接池
'rpcPool' => [
    // 依赖引用
    'ref' => beanname(Mix\JsonRpc\Client\Pool\ConnectionPool::class),
],
```

增加 IDE 提示：

修改 `ApplicationInterface.php` 增加注释：

```
@property \Mix\JsonRpc\Client\Pool\ConnectionPool $rpcPool
```

新增一个拨号类：

```
applications\common\src\Libraries\Dialers\JsonRpcTcpClientDialer.php
```

```
<?php

namespace Common\Libraries\Dialers;

use Mix\Pool\DialerInterface;

/**
 * Class JsonRpcTcpClientDialer
 * @package Common\Libraries\Dialers
 * @author liu,jian <coder.keda@gmail.com>
 */
class JsonRpcTcpClientDialer implements DialerInterface
{

    /**
     * 拨号
     * @return \Mix\JsonRpc\Client\Coroutine\JsonRpcTcpClient
     */
    public function dial()
    {
        return \Mix\JsonRpc\Client\Coroutine\JsonRpcTcpClient::newInstance();
    }

}

```

代码中使用 JsonRpcTcpClient 池：

```
$rpc     = app()->rpcPool->getConnection();
$method = 'hello.world';
$params = [];
$id     = 0;
$ret    = $rpc->call($method, $params, $id);
$db->release(); // 不手动释放的连接不会归还连接池，会在析构时丢弃
```
