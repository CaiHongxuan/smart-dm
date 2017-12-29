<?php

require_once '../vendor/autoload.php';

/**
 * 测试阿里云通信短信服务
 */
$send = new \Dm\Aliyun\AliyunSms('yourAccessKeyId', 'yourAccessKeySecret');
$result = $send->sendSms('手机号', '短信签名', '模板code', ['模板参数1' => '模板参数值1', '模板参数2' => '模板参数值2']);
print_r($result);


/**
 * 测试腾讯云短信服务
 */
$send = new \Dm\Qcloud\QcloudSms('yourAccessKeyId', 'yourAccessKeySecret');
// 单条短信
$result1 = $send->sendSms('手机号', '短信签名', '模板id', ['模板参数1' => '模板参数值1', '模板参数2' => '模板参数值2']);
print_r($result1);
// 群发短信
$result2 = $send->sendMulSms(['手机号1', '手机号2'], '短信签名', '模板id', ['模板参数1' => '模板参数值1', '模板参数2' => '模板参数值2']);
print_r($result2);