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
use HyanCat\AliyunMQ\Models\PushMessage;
use HyanCat\AliyunMQ\Utils\Signature;

class Push extends AbstractAction
{
    const URL = 'http://publictest-rest.ons.aliyun.com/message';

    protected $accessKey;
    protected $secretKey;

    /**
     * @var PushMessage
     */
    protected $message;

    public function __construct($accessKey, $secretKey)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }

    public function push(PushMessage $message): string
    {
        $this->message = $message;

        $client   = new Client();
        $query    = http_build_query($this->parameters(), '', '&');
        $url      = self::URL . '?' . $query;
        $headers  = $this->headers();
        $response = $client->post($url, ['body' => $message->body, 'headers' => $headers]);

        // If succeeded, status code is 201.
        return (string)$response->getBody();
    }

    private function headers(): array
    {
        return [
            'AccessKey'   => $this->accessKey,
            'Signature'   => $this->signature(),
            'ProducerId'  => $this->role,
            'isOrder'     => $this->message->isOrder,
            'shardingKey' => $this->message->shardingKey,
        ];
    }

    private function parameters(): array
    {
        return [
            'topic'   => $this->topic,
            'tag'     => $this->message->tag,
            'key'     => $this->message->key,
            'time'    => $this->message->time,
            'timeout' => 3000,
        ];
    }

    private function signature(): string
    {
        $newLine      = "\n";
        $joinedString = $this->topic . $newLine . $this->role . $newLine . md5($this->message->body) . $newLine . $this->message->time;
        $utf8String   = utf8_encode($joinedString);

        return Signature::sign($utf8String, $this->secretKey);
    }
}
