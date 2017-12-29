<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29 0029
 * Time: 13:57
 */

namespace Dm\Qcloud;


use Dm\IDm;
use Dm\Qcloud\lib\SmsSenderUtil;

class QcloudSms implements IDm
{
    private $url;
    private $appid;
    private $appkey;
    private $util;

    /**
     * QcloudSms constructor.
     * @param $appid
     * @param $appkey
     */
    public function __construct($appid, $appkey)
    {
        $this->url = "https://yun.tim.qq.com/v5/tlssmssvr/sendsms";
        $this->appid =  $appid;
        $this->appkey = $appkey;
        $this->util = new SmsSenderUtil();
    }

    /**
     * 发送短信（指定模板单发）
     * @param string $phone [手机号]
     * @param string $sign [短信签名]
     * @param string $templateCode [短信模板]
     * @param array $params [模板参数]
     * @param string $nationCode [国家码，如 86 为中国]
     * @return string 应答json字符串
     */
    public function sendSms($phone, $sign, $templateCode, $params, $nationCode = '86')
    {
        $random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "?sdkappid=" . $this->appid . "&random=" . $random;

        // 按照协议组织 post 包体
        $data = new \stdClass();
        $tel = new \stdClass();
        $tel->nationcode = "".$nationCode;
        $tel->mobile = "".$phone;

        $data->tel = $tel;
        $data->sig = $this->util->calculateSigForTempl($this->appkey, $random,
            $curTime, $phone);
        $data->tpl_id = $templateCode;
        $data->params = $params;
        $data->sign = $sign;
        $data->time = $curTime;
        $data->extend = ''; // 扩展码，可填空串
        $data->ext = ''; // 服务端原样返回的参数，可填空串

        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    /**
     * 群发短信
     * @param array $phones [手机号数组]
     * @param string $sign [短信签名]
     * @param string $templateCode [短信模板]
     * @param array $params [模板参数]
     * @param string $nationCode [国家码，如 86 为中国]
     * @return mixed
     */
    public function sendMulSms($phones, $sign, $templateCode, $params, $nationCode = '86')
    {
        $random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "?sdkappid=" . $this->appid . "&random=" . $random;

        $data = new \stdClass();
        $data->tel = $this->util->phoneNumbersToArray($nationCode, $phones);
        $data->sign = $sign;
        $data->tpl_id = $templateCode;
        $data->params = $params;
        $data->sig = $this->util->calculateSigForTemplAndPhoneNumbers(
            $this->appkey, $random, $curTime, $phones);
        $data->time = $curTime;
        $data->extend = ''; // 扩展码，可填空串
        $data->ext = ''; // 服务端原样返回的参数，可填空串

        return $this->util->sendCurlPost($wholeUrl, $data);
    }
}