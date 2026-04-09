<?php

namespace Hongyi\ApiEncrypt;

use Illuminate\Support\ServiceProvider;

class ApiEncryptServiceProvider extends ServiceProvider
{
    /**
     * 注册任何应用程序服务
     */
    public function register(): void
    {
        $this->app->singleton('apiEncrypt', function ($app) {
            $encrypt = new Encrypt();
        });
    }
}