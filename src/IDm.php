<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29 0029
 * Time: 12:08
 */

namespace Dm;


interface IDm
{
    /**
     * 发送单条短信
     * @param string $phone [手机号]
     * @param string $sign [短信签名]
     * @param string $templateCode [短信模板]
     * @param array $params [模板参数]
     * @param string $nationCode [国家码，如 86 为中国]
     * @return mixed
     */
    public function sendSms($phone, $sign, $templateCode, $params, $nationCode = '86');

    /**
     * 群发短信
     * @param array $phones [手机号数组]
     * @param string $sign [短信签名]
     * @param string $templateCode [短信模板]
     * @param array $params [模板参数]
     * @param string $nationCode [国家码，如 86 为中国]
     * @return mixed
     */
    public function sendMulSms($phones, $sign, $templateCode, $params, $nationCode = '86');

}