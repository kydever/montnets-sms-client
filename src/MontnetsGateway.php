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
        if (! str_ends_with($host, '/')) {
            $host .= '/';
        }

        $timestamp = date('mdHis');
        $content = $message->getContent();

        return $this->postJson($host . $uri, $data = [
            'userid' => $username,
            'mobile' => $to->getNumber(),
            'timestamp' => $timestamp,
            'pwd' => $this->buildPwd($username, $password, $timestamp),
            'content' => urlencode($content),
        ]);
    }

    protected function buildPwd(string $username, string $password, string $timestamp): string
    {
        $str = $username . '00000000' . $password . $timestamp;

        return md5($str);
    }
}
