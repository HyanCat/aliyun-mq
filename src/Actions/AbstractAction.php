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
    protected $topic;
    protected $role;

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
