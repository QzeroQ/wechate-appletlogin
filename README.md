#### 基于微信官方SDK封装微信小程序登录，用于获取openid和unionid等敏感信息的php后端实现

##### 安装步骤

1. 进入项目根目录执行
```php
composer require wechate/appletlogin
```
2. 进入laravel项目根目录的config/app.config,添加服务并设置别名

```php
'providers' => [
    //在你原来的服务列表中添加如下服务
    Wechat\Appletlogin\WeChatAppletLoginServiceProvider::class
],
'aliases' => [
    //在你原来的别名列表中添加WxLogin别名
     'WxLogin' => Wechat\Appletlogin\Facades\WxLogin::class
]
```

3. 使用例子：

```php
<?php
/**
 * Created by PhpStorm.
 * User: zzqzz
 * Date: 2018/12/18
 * Time: 9:52
 */

namespace App\Libs;

use WxLogin;
class WSign
{

    private $appid;
    private $appSecret;

    public function __construct($type = 1)
    {
        
        $this->appid = config('app.appId');//你自己的小程序的appId
        $this->appSecret = config('app.appSecret');//你自己的小程序的appSecret      
        
    }

    public function getUserInfo($data)
    {

        $data['appId']=$this->appid;
        $data['appSecret']=$this->appSecret;

        return  WxLogin::run($data);

    }

}
```

```php
<?php
/**
 * Created by PhpStorm.
 * User: zzqzz
 * Date: 2018/12/16
 * Time: 21:06
 */

namespace App\Http\Controllers\Api;

use App\Libs\WSign;
use Illuminate\Http\Request;


class UserLoginController
{
    public function login(Request $request)
    {
        //微信小程序登录后返回的cood
        $s_code = $request->input('s_code', false);
        //微信小程序登录后返回的encryptedData
        $encryptedData = $request->input('encryptedData', false);
        //微信小程序登录后返回的iv
        $iv = $request->input('iv', false);        
        $data['code'] = $s_code;
        $data['encryptedData'] = $encryptedData;
        $data['iv'] = $iv;
        $ws = new WSign();
        $res = $ws->getUserInfo($data);
        return $res;
    }
}
```

