<?php

namespace Wechat\Appletlogin;

/**
 * 登陆类
 */
class WxLogin
{
    protected  $appid;
    protected  $app_secret;
    const URL = 'https://api.weixin.qq.com/sns/jscode2session';

    /**
     * 构造函数
     * @param $appid string 小程序的appid
     * @param $app_secret string 小程序的app_secret
     */
//    public function __construct($appid,$app_secret)
//    {
//
//        $this->appid = $appid;
//        $this->app_secret = $app_secret;
//    }

    /**
     * 执行登陆授权
     * @date   2018-10-25
     */
    public function run($data)
    {
        $this->appid = $data['appId'];
        $this->app_secret = $data['appSecret'];
        // 初始赋值
        if (empty($this->app_secret) || empty($this->appid)) {
            return $this->err('appid or app_secret is null');
        }

        // 检查参数完整性
        if (empty($data['code']) || empty($data['encryptedData']) || empty($data['iv'])) {
            return $this->err('缺少参数字段，请检查后重试');
        }

        return $this->sessionKey($data);
    }

    /**
     * 获取sessionKey
     */
    private function sessionKey($getData)
    {
        // 请求来的数据
        $code = $getData['code'];
        $encryptedData = $getData['encryptedData'];
        $iv = $getData['iv'];
        $appid=$this->appid;
        $app_secret=$this->app_secret;

        $response=$this->send_request(self::URL,[
            'appid' => $appid,
            'secret' => $app_secret,
            'js_code' => $code,
            'grant_type' => 'authorization_code'
        ]);
        $data = json_decode($response, true);
        $sessionKey = $data['session_key'];
        $openid = $data['openid'];
        $unionid = isset($data['unionid'])?$data['unionid']:'';
        // 返回结果
        $result = [];

        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $result);

        if ($errCode != 0) {
            $this->err('解析失败代码：' . $errCode);
        }

        $result = json_decode($result, true);
        $result['session_key'] = $sessionKey;
        $result['unionid'] = $unionid;
        return $result;
    }




    public function send_request($url, $data)
    {
        if (is_string($data)) {
            $real_url = $url . (strpos($url, '?') === false ? '?' : '') . $data;
        } else {
            $real_url = $url . (strpos($url, '?') === false ? '?' : '') . http_build_query($data);
        }
        $ch = curl_init($real_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        try{
            $ret = curl_exec($ch);
        } finally{
            curl_close($ch);
            return $ret;
        }
    }
}