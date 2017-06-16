<?php
/**
 * This file is part of aliyun-mq.
 *
 * Created by HyanCat.
 *
 * Copyright (C) HyanCat. All rights reserved.
 */

namespace HyanCat\AliyunMQ;

use Illuminate\Support\Facades\Facade;

class AliyunMQFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return AliyunMQ::class;
    }
}
