<?php
/**
 * This file is part of aliyun-mq.
 *
 * Created by HyanCat.
 *
 * Copyright (C) HyanCat. All rights reserved.
 */

namespace HyanCat\AliyunMQ;

use Illuminate\Support\ServiceProvider;

class AliyunMQServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->bind(AliyunMQ::class, function ($app) {
            $config = $app['config']->get('aliyun-mq');

            return new AliyunMQ($config);
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/aliyun-mq.php' => config_path('aliyun-mq.php'),
            ], 'config');
        }
        if ($this->isLumen()) {
            $this->app->configure('aliyun-mq');
        }
    }

    protected function isLumen()
    {
        return str_contains($this->app->version(), 'Lumen');
    }
}
