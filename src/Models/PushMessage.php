<?php
/**
 * This file is part of aliyun-mq.
 *
 * Created by HyanCat.
 *
 * Copyright (C) HyanCat. All rights reserved.
 */

namespace HyanCat\AliyunMQ\Models;

/**
 * Class PushMessage
 * @property string $tag
 * @property string $key
 * @property string $body
 * @property int    $time
 * @property bool   $isOrder
 * @property string $shardingKey
 * @property int    $startDeliverTime
 * @namespace HyanCat\AliyunMQ\Models
 */
class PushMessage
{
    public function __construct()
    {
        $this->_time = time() * 1000;
    }

    /**
     * @var string
     */
    private $_tag;

    /**
     * @var string
     */
    private $_key;

    /**
     * @var string
     */
    private $_body = '';

    /**
     * @var int
     */
    private $_time;

    /**
     * @var boolean
     */
    private $_isOrder = true;

    /**
     * @var string
     */
    private $_shardingKey;

    /**
     * @var int
     */
    private $_startDeliverTime;

    public function with($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                $this->with($key, $val);
            }

            return $this;
        }
        $property = '_' . $name;
        if (property_exists(static::class, $property)) {
            $this->$property = $value;
        }

        return $this;
    }

    function __get($name)
    {
        $property = '_' . $name;
        if (property_exists(static::class, $property)) {
            return $this->$property;
        }

        return null;
    }
}
