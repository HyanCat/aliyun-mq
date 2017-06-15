<?php
/**
 * This file is part of aliyun-mq.
 *
 * Created by HyanCat.
 *
 * Copyright (C) HyanCat. All rights reserved.
 */

namespace HyanCat\AliyunMQ;

use GuzzleHttp\Exception\ClientException;
use HyanCat\AliyunMQ\Actions\Pull;
use HyanCat\AliyunMQ\Actions\Push;
use HyanCat\AliyunMQ\Models\PushMessage;

class AliyunMQ
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function push(PushMessage $message, $callback = null): bool
    {
        $accessKey = $this->config['access_key'];
        $secretKey = $this->config['secret_key'];

        $push = new Push($accessKey, $secretKey);

        try {
            $responseBody = $push->topic($this->config['topic'])->asRole($this->config['producer_id'])->push($message);
        } catch (ClientException $e) {
            return false;
        }

        if ($callback instanceof \Closure) {
            $callback(json_decode($responseBody));
        }

        return true;
    }

    public function pull(int $count, $callback): bool
    {
        $accessKey = $this->config['access_key'];
        $secretKey = $this->config['secret_key'];

        $pull = new Pull($accessKey, $secretKey);

        try {
            $messages = $pull->topic($this->config['topic'])->asRole($this->config['consumer_id'])->pull($count);
        } catch (ClientException $e) {
            return false;
        }

        foreach ($messages as $message) {
            $callback($message);
        }

        return true;
    }
}
