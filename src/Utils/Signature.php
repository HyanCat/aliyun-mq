<?php
/**
 * This file is part of aliyun-mq.
 *
 * Created by HyanCat.
 *
 * Copyright (C) HyanCat. All rights reserved.
 */

namespace HyanCat\AliyunMQ\Utils;

class Signature
{
    public static function sign(string $str, string $key): string
    {
        $sign = base64_encode(hash_hmac("sha1", $str, $key, true));

        return $sign;
    }
}
