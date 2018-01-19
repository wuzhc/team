<?php

namespace common\utils;

$path = \Yii::getAlias('@sdk') . '/aliyun-php-sdk-core/Config.php';
include_once $path;

use ClientException;
use DefaultAcsClient;
use DefaultProfile;
use Dm\Request\V20151123\SingleSendMailRequest;
use ServerException;

/**
 * 邮件工具
 * Class EmailUtil
 * @package common\utils
 */
class EmailUtil
{
    /**
     * 阿里云邮件发送
     * @param string $email 邮件接受者
     * @param string $content 邮件内容
     * @param string $subject 邮件主题
     * @param string $from 邮件发送者昵称
     * @return bool
     * @since 2018-01-18
     */
    public static function send($email, $content, $subject, $from = 'team')
    {
        $iClientProfile = DefaultProfile::getProfile("cn-hangzhou", ALI_KEY, ALI_SECRET);
        $client = new DefaultAcsClient($iClientProfile);
        $request = new SingleSendMailRequest();

        // 发信地址
        $request->setAccountName(EMAIL_SENDER);

        // 发信人昵称
        $request->setFromAlias($from);

        // 邮件主题
        $request->setSubject($subject);

        $request->setAddressType(1);
        $request->setReplyToAddress('false');
        $request->setToAddress($email);
        $request->setHtmlBody($content);

        try {
            $response = $client->getAcsResponse($request);
            print_r($response);
            return true;
        } catch (ClientException $e) {
            print_r($e->getErrorCode());
            print_r($e->getErrorMessage());
            return false;
        } catch (ServerException $e) {
            print_r($e->getErrorCode());
            print_r($e->getErrorMessage());
            return false;
        }
    }
}