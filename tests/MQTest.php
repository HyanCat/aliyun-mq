<?php
/**
 * This file is part of aliyun-mq.
 *
 * Created by HyanCat.
 *
 * Copyright (C) HyanCat. All rights reserved.
 */
use PHPUnit\Framework\TestCase;
use HyanCat\AliyunMQ\AliyunMQ;
use HyanCat\AliyunMQ\Models\PushMessage;
use HyanCat\AliyunMQ\Models\PullMessage;

class MQTest extends TestCase
{
    protected $mq;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $config   = require __DIR__ . '/../config/aliyun-mq.local.php';
        $this->mq = new AliyunMQ($config);
    }

    public function testPushMessage()
    {
        $this->randomNMessage(function ($message) {
            $succeeded = $this->mq->push($message, function ($response) {
                $this->assertTrue(is_string($response->msgId), 'Got response message id.');
                $this->assertTrue($response->sendStatus === 'SEND_OK', 'Response status is OK.');
            });
            $this->assertTrue($succeeded, 'Push succeeded.');
        });
    }

    public function testPullMessage()
    {
        $succeeded = $this->mq->autoDelete(true)->pull(10, function (PullMessage $message) {
            $this->assertTrue(null !== $message, 'Got a message.');
        });
        $this->assertTrue($succeeded, 'Pull succeeded.');
    }

    private function randomNMessage(Closure $callback)
    {
        $n = random_int(1, 10);
        for ($i = 0; $i < $n; $i++) {
            $message = new PushMessage();
            $message->with([
                'body' => 'test body: ' . rand(1000, 9999),
                'key'  => 'test-key',
                'tag'  => 'test-tag',
            ]);
            $callback($message);
        }
    }
}
