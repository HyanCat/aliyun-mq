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
use HyanCat\AliyunMQ\Actions\Delete;
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
        $accessKey  = $this->config['access_key'];
        $secretKey  = $this->config['secret_key'];
        $topic      = $this->config['topic'];
        $producerId = $this->config['producer_id'];

        $push = new Push($accessKey, $secretKey);

        try {
            $responseBody = $push->topic($topic)->asRole($producerId)->push($message);
        } catch (ClientException $e) {
            return false;
        }

        if ($callback instanceof \Closure) {
            $callback(json_decode($responseBody));
        }

        return true;
    }

    private $_autoDeleted = true;

    public function autoDelete($auto)
    {
        $this->_autoDeleted = $auto;

        return $this;
    }

    public function pull(int $count, $callback): bool
    {
        $accessKey  = $this->config['access_key'];
        $secretKey  = $this->config['secret_key'];
        $topic      = $this->config['topic'];
        $consumerId = $this->config['consumer_id'];

        $pull = new Pull($accessKey, $secretKey);

        try {
            $messages = $pull->topic($topic)->asRole($consumerId)->pull($count);
        } catch (ClientException $e) {
            return false;
        }

        foreach ($messages as $message) {
            $callback($message);

            if ($this->_autoDeleted) {
                $delete  = new Delete($accessKey, $secretKey);
                $deleted = $delete->topic($topic)->asRole($consumerId)->delete($message->msgHandle);
            }
        }

        return true;
    }
}
