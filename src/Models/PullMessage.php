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
 * Class PullMessage
 * @property string $body
 * @property int    $bornTime
 * @property string $key
 * @property string $tag
 * @property string $msgHandle
 * @property string $msgId
 * @property int    $reconsumeTimes
 * @namespace HyanCat\AliyunMQ\Models
 */
class PullMessage
{
    /**
     * @var string
     */
    private $_body;
    /**
     * @var int
     */
    private $_bornTime;
    /**
     * @var string
     */
    private $_key;
    /**
     * @var string
     */
    private $_tag;
    /**
     * @var string
     */
    private $_msgHandle;
    /**
     * @var string
     */
    private $_msgId;
    /**
     * @var int
     */
    private $_reconsumeTimes;

    function __get($name)
    {
        $property = '_' . $name;
        if (property_exists(static::class, $property)) {
            return $this->$property;
        }

        return null;
    }

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $property = '_' . $key;
            if (property_exists(static::class, $property)) {
                $this->$property = $value;
            }
        }
    }
}
