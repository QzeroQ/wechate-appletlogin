<?php

namespace Wechat\Appletlogin\Facades;

/**
 * Facades
 */
use Illuminate\Support\Facades\Facade;
class WxLogin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'WxLogin';
    }
}