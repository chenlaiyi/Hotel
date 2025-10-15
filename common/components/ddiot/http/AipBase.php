<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-09-18 11:54:45
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-05-10 05:37:39
 */

namespace ddiot\http;

use ddiot\sign\ProjectSign;
use Exception;

/**
 * Aip Base 基类
 */
class AipBase
{
    /**
     * 测试环境地址
     * @var string
     */
    public static $apiTestUrl = 'https://testiot.ddicms.cn';

    /**
     * 正式环境地址
     * @var string
     */
    public static $apiUrl = 'https://iot.ddicms.com';

    public static $app_id = '';
    public static $app_secret = '';
    public static $project_sn = '';
    public static bool $is_dev = true;

    private $version = '1.0.0';
    private AipHttpClient $client;

    private $scope = '';

    /**
     * @throws Exception
     */
    public function __construct($version = '1.0.0')
    {
        $this->version = $version;
        $this->client = new AipHttpClient();
    }

    /**
     * 设置配置信息
     * @param array $config
     * @throws Exception
     */
    public function setConfig(array $config)
    {
        if (empty($config['app_id'])) {
            throw new Exception('app_id can not be empty');
        }
        if (empty($config['project_sn'])) {
            throw new Exception('project_sn can not be empty');
        }
        if (empty($config['app_secret'])) {
            throw new Exception('app_secret can not be empty');
        }

        self::$app_id = $config['app_id'];
        self::$app_secret = $config['app_secret'];
        self::$project_sn = $config['project_sn'];

        if (isset($config['is_dev'])) {
            self::$is_dev = $config['is_dev'];
        }

        if (isset($config['api_url'])) {
            self::$apiUrl = $config['api_url'];
        }

        if (isset($config['api_test_url'])) {
            self::$apiTestUrl = $config['api_test_url'];
        }
    }

    /**
     * 查看版本
     * @return string
     *
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * 连接超时
     * @param int $ms 毫秒
     */
    public function setConnectionTimeoutInMillis($ms)
    {
        $this->client->setConnectionTimeoutInMillis($ms);
    }

    /**
     * 响应超时
     * @param int $ms 毫秒
     */
    public function setSocketTimeoutInMillis($ms)
    {
        $this->client->setSocketTimeoutInMillis($ms);
    }

    /**
     * 代理
     * @param $proxies
     * @return void
     */
    public function setProxies($proxies): void
    {
        $this->client->setConf($proxies);
    }

    /**
     * 处理请求参数
     * @param array $params
     */
    protected function proccessRequest(array &$params)
    {
        $params['aipSdk'] = 'php';
        $params['aipSdkVersion'] = $this->version;
    }


    /**
     * 构造请求地址
     */
    protected function builldUrl($url)
    {
        $apiUrl = self::$is_dev ? self::$apiTestUrl :  self::$apiUrl;
        return $apiUrl . '/' . self::$project_sn . $url;
    }

    /**
     * Api 请求
     * @param string $url
     * @param string $method
     * @param mixed $data
     * @param array $headers
     * @return mixed
     * @throws Exception
     */
    protected function request(string $url, string $method = 'post', array $data = [], array $headers = array())
    {
        try {
            $url = $this->builldUrl($url);
            $result = $this->validate($url, $data);
            if ($result !== true) {
                return $result;
            }
            //签名
            $data['app_id'] = self::$app_id;
            $data['timestamp'] = time();
            $this->proccessRequest($data);
            $sign = (new ProjectSign())->getSign(self::$app_secret, $data);
            $data['sign'] = $sign;
            // 特殊处理
            $this->setConnectionTimeoutInMillis(2000);

            $headers = $this->getAuthHeaders($url, $data, $headers);

            $response = match ($method) {
                'get' => $this->client->get($url, $data, $headers),
                'put' => $this->client->put($url, $data, $headers),
                'delete' => $this->client->delete($url, $data, $headers),
                default => $this->client->post($url, $data, $headers),
            };
            $obj = $this->proccessResult($response['content']);
        } catch (Exception $e) {
            return json_encode([
                'code'=>400,
                'message'=> $e->getMessage()
            ]);
        }
        return $obj;
    }

    /**
     * Api 多个并发请求
     * @param string $url
     * @param mixed $data
     * @return mixed
     * @throws Exception
     */
    protected function multi_request($url, $data)
    {
        try {
            $params = array();
            $headers = $this->getAuthHeaders($url,  $data);

            $responses = $this->client->multi_post($url, $data, $params, $headers);
            $is_success = false;
            foreach ($responses as $response) {
                $obj = $this->proccessResult($response);
                if (empty($obj) || !isset($obj['code'])) {
                    $is_success = true;
                }
                if (isset($obj['code']) && $obj['code'] !== 200) {
                    $responses = $this->client->post($url, $data, $params, $headers);
                    break;
                }
            }
            $objs = array();
            foreach ($responses as $response) {
                $objs[] = $this->proccessResult($response['content']);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }
        return $objs;
    }

    /**
     * 格式检查
     * @param string $url
     * @param array $data
     * @return boolean
     */
    protected function validate($url, &$data)
    {
        return true;
    }

    /**
     * 格式化结果
     * @param $content string
     * @return mixed
     */
    protected function proccessResult(string $content)
    {
        return json_decode($content, true);
    }

    /**
     * 返回 access token 路径
     * @return string
     */
    private function getLogFilePath()
    {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . md5(self::$app_id);
    }

    /**
     * 写入本地文件
     * @param array $obj
     * @return void
     */
    private function writeLogObj($obj)
    {
        $obj['time'] = time();
        @file_put_contents($this->getLogFilePath(), json_encode($obj));
    }

    /**
     * 判断认证是否有权限
     * @param array $authObj
     * @return boolean
     */
    protected function isPermission($authObj)
    {
        if (empty($authObj) || !isset($authObj['scope'])) {
            return false;
        }
        $scopes = explode(' ', $authObj['scope']);
        return in_array($this->scope, $scopes);
    }

    /**
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return array
     */
    private function getAuthHeaders(string $url, array $data, array $headers = array()): array
    {
        //UTC 时间戳
        $timestamp = gmdate('Y-m-d\TH:i:s\Z');
        $headers['x-bce-date'] = $timestamp;
        //        app-id
        $headers['app-id'] = self::$app_id;
        return $headers;
    }


    /**
     * 通用接口
     * @param string $url
     * @param array $data
     * @param array $headers header
     * @return bool|array
     * @throws Exception
     */
    public function post(string $url, array $data, array $headers = array()): mixed
    {
        return $this->request($url, 'post', $data, $headers)??[];
    }

    /**
     * get
     * @throws Exception
     */
    public function get(string $url, array $data, array $headers = array()): mixed
    {
        return $this->request($url, 'get', $data, $headers)??[];
    }

    /**
     * put
     * @throws Exception
     */
    public function put(string $url, array $data, array $headers = array()): mixed
    {
        return $this->request($url, 'put', $data, $headers)??[];
    }

    /**
     * delete
     * @throws Exception
     */
    public function delete(string $url, array $data, array $headers = array()): mixed
    {
        return $this->request($url, 'delete', $data, $headers)??[];
    }
}