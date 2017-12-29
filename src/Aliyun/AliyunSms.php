<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29 0029
 * Time: 17:37
 */

namespace Dm\Aliyun;


use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Core\Config;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Profile\DefaultProfile;
use Dm\IDm;

class AliyunSms implements IDm
{
    private static $acsClient = null;
    private static $appid;
    private static $appkey;

    /**
     * AliyunSms constructor.
     * @param $appid
     * @param $appkey
     */
    public function __construct($appid, $appkey)
    {
        self::$appid =  $appid;
        self::$appkey = $appkey;
        // 加载区域结点配置
        Config::load();
    }

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    private static function getAcsClient() {
        //产品名称:云通信流量服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        $accessKeyId = self::$appid; // AccessKeyId

        $accessKeySecret = self::$appkey; // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";


        if(static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }

    /**
     * 发送单条短信
     * @param string $phone [手机号]
     * @param string $sign [短信签名]
     * @param string $templateCode [短信模板]
     * @param array $params [模板参数]
     * @param string $nationCode [国家码，如 86 为中国]
     * @return mixed
     */
    public function sendSms($phone, $sign, $templateCode, $params, $nationCode = '86')
    {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($phone);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName($sign);

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode($templateCode);

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode($params, JSON_UNESCAPED_UNICODE)); // 短信模板中字段的值

//        // 可选，设置流水号
//        $request->setOutId("yourOutId");
//
//        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
//        $request->setSmsUpExtendCode("1234567");

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);

        return $acsResponse;
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
        // TODO: Implement sendMulSms() method.
    }
}