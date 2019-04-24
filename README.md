## mix/jsonrpc-client

### 安装

使用 Composer 安装：

```
composer require mix/jsonrpc-client
```

普通调用：

在 TP/Yii/Laravel 等传统框架也可使用。

```
$client = \Mix\JsonRpc\Client\JsonRpcTcpClient::new('192.168.1.211', 9504);
$method = 'hello.world';
$params = [];
$id     = 0;
$ret    = $client->call($method, $params, $id);
var_dump($ret);
```

协程使用：

在 Mix PHP 中使用，可并行获取多个请求结果，性能是传统框架的同步方式无法比拟的。

```
$chan = new \Mix\Core\Coroutine\Channel();
xgo(function () use ($chan) {
    $client = \Mix\JsonRpc\Client\Coroutine\JsonRpcTcpClient::new('192.168.1.211', 9504);
    $method = 'hello.world';
    $params = [];
    $id     = 0;
    $ret    = $client->call($method, $params, $id);
    $chan->push($ret);
});
xgo(function () use ($chan) {
    $client = \Mix\JsonRpc\Client\Coroutine\JsonRpcTcpClient::new('192.168.1.211', 9504);
    $method = 'hello.world';
    $params = [];
    $id     = 0;
    $ret    = $client->call($method, $params, $id);
    $chan->push($ret);
});
$ret = [];
for ($i = 0; $i < 2; $i++) {
    $ret[] = $chan->pop();
}
list($ret1, $ret2) = $ret;
// 可对两次请求的结果做计算并发送给客户端
```
