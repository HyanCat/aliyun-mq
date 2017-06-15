<?php
/**
 * This file is part of aliyun-mq.
 *
 * Created by HyanCat.
 *
 * Copyright (C) HyanCat. All rights reserved.
 */

namespace HyanCat\AliyunMQ\Actions;

use GuzzleHttp\Client;
use HyanCat\AliyunMQ\Models\PullMessage;
use HyanCat\AliyunMQ\Utils\Signature;

class Pull extends AbstractAction
{
    const URL = 'http://publictest-rest.ons.aliyun.com/message';

    protected $accessKey;
    protected $secretKey;

    private $_params = [];

    public function __construct($accessKey, $secretKey)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }

    public function pull(int $count = 1): array
    {
        $this->_params['count'] = $count;
        $this->_params['time']  = time() * 1000;

        $client   = new Client();
        $query    = http_build_query($this->parameters(), '', '&');
        $url      = self::URL . '?' . $query;
        $headers  = $this->headers();
        $response = $client->get($url, ['headers' => $headers]);
        if ($response->getStatusCode() !== 200) {
            return [];
        }
        $list = json_decode($response->getBody(), true);

        $messages = [];
        foreach ($list as $item) {
            $message    = new PullMessage($item);
            $messages[] = $message;
        }

        return $messages;
    }

    private function headers(): array
    {
        return [
            'AccessKey'  => $this->accessKey,
            'Signature'  => $this->signature(),
            'ConsumerId' => $this->role,
        ];
    }

    private function parameters(): array
    {
        return [
            'topic' => $this->topic,
//            'timeout' => 35000,
            'time'  => $this->_params['time'],
            'num'   => $this->_params['count'],
        ];
    }

    private function signature(): string
    {
        $newLine      = "\n";
        $joinedString = $this->topic . $newLine . $this->role . $newLine . $this->_params['time'];
        $utf8String   = utf8_encode($joinedString);

        return Signature::sign($utf8String, $this->secretKey);
    }
}
