# 梦网短信 SDK

## 安装

```
composer require kydev/montnets-sms-client
```

## 使用

```php
<?php
use KY\MontnetsSmsClient\MontnetsGateway;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

$config = [
    // HTTP 请求的超时时间（秒）
    'timeout' => 5.0,

    // 默认发送配置
    'default' => [
        // 网关调用策略，默认：顺序调用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            MontnetsGateway::class,
        ],
    ],
    // 可用的网关配置
    'gateways' => [
        'errorlog' => [
            'file' => '/tmp/easy-sms.log',
        ],
        MontnetsGateway::class => [
            'host' => 'http://ip:port',
            'username' => 'A00001',
            'password' => '123456',
        ],
    ],
];

$easySms = new EasySms($config);

try {
    $content = '您的验证码为: 1111';
    $content = iconv('UTF-8', 'gbk', $content);
    
    $res = $easySms->send(18678010000, [
        'content' => $content,
    ]);

    var_dump($res);
} catch (NoGatewayAvailableException $exception) {
    foreach ($exception->getExceptions() as $exception) {
        echo (string) $exception;
        echo PHP_EOL;
    }
}
```
