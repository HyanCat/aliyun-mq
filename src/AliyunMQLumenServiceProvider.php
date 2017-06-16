<?php
/**
 * This file is part of aliyun-mq.
 *
 * Created by HyanCat.
 *
 * Copyright (C) HyanCat. All rights reserved.
 */

namespace HyanCat\AliyunMQ;

class AliyunMQLumenServiceProvider extends AliyunMQServiceProvider
{
    protected function isLumen()
    {
        return true;
    }
}
