<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace KY\MontnetsSmsClient;

use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;
use Overtrue\EasySms\Gateways\Gateway;
use Overtrue\EasySms\Support\Config;
use Overtrue\EasySms\Traits\HasHttpRequest;

class MontnetsGateway extends Gateway
{
    use HasHttpRequest;

    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config)
    {
        $host = $config->get('host');
        $username = $config->get('username');
        $password = $config->get('password');

        $uri = 'sms/v2/std/single_send';

        return $this->postJson($host . $uri, [
            'userid' => $username,
            'pwd' => $password,
            'mobile' => $to->getNumber(),
            'content' => $message->getContent(),
        ]);
    }
}
