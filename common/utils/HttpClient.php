<?php

namespace common\utils;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Http客户端，用于发送http请求
 * Class HttpClient
 * @package common\utils
 * @author wuzhc <wuzhc2016@163.com>
 * @since 2018-01-28
 */
class HttpClient
{

    /**
     * http请求
     * @param string $url 请求地址
     * @param string $method 请求方式
     * @param array $data 请求参数
     * @param array $options 请求选项
     * @return string
     */
    public static function request($url, $method = 'get', $data = [], $options = [])
    {
        $client = new Client();

        // 请求选项，如果自己设置，则默认值会被覆盖
        $options = array_merge([
            'debug'       => false, // 调试模式
            'timeout'     => 5, // 请求超时时间，0表示无限制
            'http_errors' => YII_DEBUG, // 是否抛出异常
        ], $options);

        if (!empty($data)) {
            $query = parse_url($url, PHP_URL_QUERY);
            if ($query) {
                $url .= '&';
            } else {
                $url .= '?';
            }
            $url .= http_build_query($data);
        }

        try {
            $response = $client->request($method, $url, $options);
            if ($response->getStatusCode() == 200) {
                return $response->getBody()->getContents();
            } else {
                return '';
            }
        } catch (RequestException $e) {
            var_dump($e->getCode(), $e->getMessage());
            exit;
        }
    }


}