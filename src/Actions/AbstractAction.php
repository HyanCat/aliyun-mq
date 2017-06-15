<?php
/**
 * This file is part of aliyun-mq.
 *
 * Created by HyanCat.
 *
 * Copyright (C) HyanCat. All rights reserved.
 */

namespace HyanCat\AliyunMQ\Actions;

abstract class AbstractAction
{
    const URL = 'http://publictest-rest.ons.aliyun.com/message';

    protected $accessKey;
    protected $secretKey;

    protected $topic;
    protected $role;

    function __construct(string $accessKey, string $secretKey)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }

    public function topic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    public function asRole($role)
    {
        $this->role = $role;

        return $this;
    }
}
