<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-09-27 15:05:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-05-10 12:28:32
 */

namespace ddiot\http;


use Exception;

class AipHttpClient
{
    private array $headers;
    private int $connectTimeout;
    private int $socketTimeout;
    private array $conf;

    /**
     * HttpClient.
     *
     * @param array $headers HTTP header
     */
    public function __construct(array $headers = [])
    {
        $this->headers = $this->buildHeaders($headers);
        $this->connectTimeout = 60000;
        $this->socketTimeout = 60000;
        $this->conf = [];
    }

    /**
     * 连接超时.
     *
     * @param int $ms 毫秒
     */
    public function setConnectionTimeoutInMillis($ms)
    {
        $this->connectTimeout = $ms;
    }

    /**
     * 响应超时.
     *
     * @param int $ms 毫秒
     */
    public function setSocketTimeoutInMillis($ms)
    {
        $this->socketTimeout = $ms;
    }

    /**
     * 配置.
     *
     * @param array $conf
     */
    public function setConf(array $conf): void
    {
        $this->conf = $conf;
    }

    /**
     * 将配置应用到cURL句柄
     *
     * @param resource $ch cURL句柄
     * @return void
     */
    public function prepare($ch): void
    {
        // 遍历配置项并设置到cURL句柄
        foreach ($this->conf as $key => $value) {
            // 检查是否为有效的cURL选项
            if (!is_int($key)) {
                // 可选：记录非法配置键的日志或抛出异常
                continue;
            }

            // 设置cURL选项
            $result = @curl_setopt($ch, $key, $value);

            // 可选：记录失败的设置项以便调试
            //            if (!$result) {
            //                var_dump(curl_error($ch));
            ////                error_log("Failed to set cURL option: {$key}");
            //            }
        }
    }


    /**
     * 发送 HTTP POST 请求
     *
     * @param string $url 请求地址
     * @param array $data 请求体数据
     * @param array $headers 请求头
     * @return array ['code' => int, 'content' => string]
     * @throws Exception
     */
    public function post(string $url, array $data = [], array $headers = []): array
    {
        // 合并默认 header 和传入 header，并格式化为 cURL 可用格式
        $headers = array_merge($this->headers, $this->buildHeaders($headers));
        // 初始化 cURL 句柄
        $ch = curl_init();

        try {
            // 配置 cURL 选项
            curl_setopt_array($ch, [
                CURLOPT_URL            => $url,
                CURLOPT_POST           => true,
                CURLOPT_HEADER         => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER     => $headers,
                CURLOPT_POSTFIELDS     => is_array($data) ? http_build_query($data) : $data,
                CURLOPT_TIMEOUT_MS     => $this->socketTimeout,
                CURLOPT_CONNECTTIMEOUT_MS => $this->connectTimeout,
                CURLOPT_ENCODING       => '', // 接受所有支持的编码
                CURLOPT_FOLLOWLOCATION => true, // 允许重定向
                CURLOPT_MAXREDIRS      => 5, // 最大重定向次数
            ]);

            // 执行请求
            $content = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // 检查执行是否出错
            if ($content === false) {
                $error = curl_error($ch);
                $errno = curl_errno($ch);
                throw new \Exception("cURL Error ($errno): $error", $errno);
            }

            // 检查HTTP状态码
            if ($code >= 400) {
                throw new \Exception("HTTP Error: $code - $content", $code);
            }

            // 尝试检测并处理响应编码
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            if (str_contains($contentType, 'charset=')) {
                $charset = substr($contentType, strpos($contentType, 'charset=') + 8);
                if (strtoupper($charset) !== 'UTF-8') {
                    $content = mb_convert_encoding($content, 'UTF-8', $charset);
                }
            }

            return ['code' => $code, 'content' => $content];
        } catch (\Exception $e) {
            // 记录错误信息
            error_log("HTTP Request Error: " . $e->getMessage());
            throw $e;
        } finally {
            // 确保句柄被关闭
            curl_close($ch);
        }
    }


    /**
     * @param string $url
     * @param array $datas HTTP POST BODY
     * @param array $params
     * @param array $headers HTTP header
     *
     * @return array
     */
    public function multi_post(string $url, array $datas = [], array $params = [], array $headers = []): array
    {
        $url = $this->buildUrl($url, $params);
        $headers = array_merge($this->headers, $this->buildHeaders($headers));
        $chs = [];
        $result = [];
        $mh = curl_multi_init();
        foreach ($datas as $data) {
            $ch = curl_init();
            $chs[] = $ch;
            $this->prepare($ch);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : $data);
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->socketTimeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $this->connectTimeout);
            curl_multi_add_handle($mh, $ch);
        }
        $running = null;
        do {
            curl_multi_exec($mh, $running);
            usleep(100);
        } while ($running);
        foreach ($chs as $ch) {
            $content = curl_multi_getcontent($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $result[] = [
                'code' => $code,
                'content' => $content,
            ];
            curl_multi_remove_handle($mh, $ch);
        }
        curl_multi_close($mh);
        return $result;
    }

    /**
     * @param string $url
     * @param array $datas HTTP POST BODY
     * @param array $params
     * @param array $headers HTTP header
     *
     * @return array
     */
    public function multi_put(string $url, array $datas, array $params = [], array $headers = [])
    {
        $url = $this->buildUrl($url, $params);
        $headers = array_merge($this->headers, $this->buildHeaders($headers));
        $chs = [];
        $result = [];
        $mh = curl_multi_init();
        //备份
        $ch = curl_init();
        $chs[] = $ch;
        $this->prepare($ch);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); //定义请求类型，当然那个提交类型那一句就不需要了
        curl_setopt($ch, CURLOPT_PUT, true);
        // curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($datas) ? http_build_query($datas) : $datas);
        // curl_setopt($ch, CURLOPT_POSTFIELDS,$datas);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->socketTimeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $this->connectTimeout);
        curl_multi_add_handle($mh, $ch);
        $running = null;
        do {
            curl_multi_exec($mh, $running);
            usleep(100);
        } while ($running);
        foreach ($chs as $ch) {
            $content = curl_multi_getcontent($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $result[] = [
                'code' => $code,
                'content' => $content,
            ];
            curl_multi_remove_handle($mh, $ch);
        }
        curl_multi_close($mh);
        return $result;
    }

    /**
     * @param string $url
     * @param array $params
     * @param array $headers HTTP header
     *
     * @return array
     * @throws Exception
     */
    public function get(string $url, array $params = [], array $headers = [])
    {
        $url = $this->buildUrl($url, $params);
        $headers = array_merge($this->headers, $this->buildHeaders($headers));
        $ch = curl_init();
        $this->prepare($ch);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->socketTimeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $this->connectTimeout);
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code === 0) {
            throw new \Exception(curl_error($ch));
        }
        curl_close($ch);
        return [
            'code' => $code,
            'content' => $content,
        ];
    }


    public function put(string $url, mixed $data, array $headers): array
    {
        $headers = array_merge($this->headers, $this->buildHeaders($headers));
        $ch = curl_init();
        $this->prepare($ch);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : $data);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->socketTimeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $this->connectTimeout);
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return [
            'code' => $code,
            'content' => $content,
        ];
    }

    public function delete(string $url, mixed $data, array $headers): array
    {
        $headers = array_merge($this->headers, $this->buildHeaders($headers));
        $ch = curl_init();
        $this->prepare($ch);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : $data);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->socketTimeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $this->connectTimeout);
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return [
            'code' => $code,
            'content' => $content,
        ];
    }

    /**
     * 构造 header.
     *
     * @param array $headers
     *
     * @return array
     */
    private function buildHeaders(array $headers): array
    {
        $result = [];
        foreach ($headers as $k => $v) {
            if (is_int($k)) {
                $k = $v;
                $v = '';
            }

            // 处理数组类型的 header value
            if (is_array($v)) {
                $v = implode(', ', $v); // 或者 json_encode($v)
            }

            $result[] = sprintf('%s:%s', $k, $v);
        }
        return $result;
    }

    /**
     * @param string $url
     * @param array $params 参数
     *
     * @return string
     */
    private function buildUrl(string $url, array $params): string
    {
        if (!empty($params)) {
            $str = http_build_query($params);
            return $url . (!str_contains($url, '?') ? '?' : '&') . $str;
        } else {
            return $url;
        }
    }
}
