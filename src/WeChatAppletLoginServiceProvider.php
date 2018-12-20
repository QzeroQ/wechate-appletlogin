<?php

namespace Wechat\Appletlogin;

use Illuminate\Support\ServiceProvider;

class WeChatAppletLoginServiceProvider extends ServiceProvider
{


    /**
     * 服务提供者加是否延迟加载.
     *
     * @var bool
     */
    protected $defer = true; // 延迟加载服务


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // 单例绑定服务
        $this->app->singleton('WxLogin', function () {
            return new WxLogin();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        // 因为延迟加载 所以要定义 provides 函数
        return ['WxLogin'];
    }

}
