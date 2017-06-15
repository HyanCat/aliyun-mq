<?php
/**
 * This file is part of aliyun-mq.
 *
 * Created by HyanCat.
 *
 * Copyright (C) HyanCat. All rights reserved.
 */

namespace HyanCat\AliyunMQ\Actions;

use GuzzleHttp\Client;
use HyanCat\AliyunMQ\Utils\Signature;

class Delete extends AbstractAction
{
    private $_params;

    public function delete($msgHandle): bool
    {
        $this->_params['time']      = time() * 1000;
        $this->_params['msgHandle'] = $msgHandle;

        $client   = new Client();
        $query    = http_build_query($this->parameters(), '', '&');
        $url      = self::URL . '?' . $query;
        $headers  = $this->headers();
        $response = $client->delete($url, ['headers' => $headers]);

        return $response->getStatusCode() === 204;
    }

    private function headers(): array
    {
        return [
            'AccessKey'  => $this->accessKey,
            'Signature'  => $this->signature(),
            'ConsumerId' => $this->role,
        ];
    }

    private function parameters(): array
    {
        return [
            'topic'     => $this->topic,
            'msgHandle' => $this->_params['msgHandle'],
            'time'      => $this->_params['time'],
        ];
    }

    private function signature(): string
    {
        $newLine      = "\n";
        $joinedString = $this->topic . $newLine . $this->role . $newLine . $this->_params['msgHandle'] . $newLine . $this->_params['time'];
        $utf8String   = utf8_encode($joinedString);

        return Signature::sign($utf8String, $this->secretKey);
    }
}
