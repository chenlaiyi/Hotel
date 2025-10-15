<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-24 13:49:33
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-24 14:41:02
 */

namespace common\rpc\jwt;



use EasySwoole\Rpc\Protocol\Response;

class Jwt
{
    private static Jwt $instance;

    private string $secretKey = 'DdiotSwoole';
    protected string $prefix = '';

    private string $alg = Jwt::ALG_METHOD_HS256; // 默认加密方式

    public const ALG_METHOD_AES = 'AES';
    public const ALG_METHOD_HMACSHA256 = 'HMACSHA256';
    public const ALG_METHOD_HS256 = 'HS256';
    public const ALG_METHOD_RS256 = 'RS256';
    private Response $response;

    public static function getInstance(): Jwt
    {
        if (!isset(self::$instance)) {
            self::$instance = new Jwt();
        }
        return self::$instance;
    }

    public function setSecretKey(string $key): Jwt
    {
        $this->secretKey = $key;
        return $this;
    }

    public function setAlg(string $alg): Jwt
    {
        $this->alg = $alg;
        return $this;
    }

    public function publish(): JwtObject
    {
        return new JwtObject(['secretKey' => $this->secretKey]);
    }

    /**
     * @throws Exception
     */
    public function decode(string $raw): ?JwtObject
    {
//        if (strpos($raw, ' ')) {
//            $prefix       = explode(' ', $raw);
//            $this->prefix = $prefix[0];
//            $raw          = str_replace($this->prefix . ' ', '', $raw);
//        }

        $items = explode('.', $raw);
        // token格式
        if (count($items) !== 3) {
            throw new Exception('Token format error!');
        }

        // 验证header
        $header = Encryption::base64UrlDecode($items[0]);
        $header = json_decode($header, true);
        if (empty($header)) {
            throw new Exception('Token header is empty!');
        }

        // 验证payload
        $payload = Encryption::base64UrlDecode($items[1]);
        $payload = json_decode($payload, true);
        if (empty($payload)) {
            throw new Exception('Token payload is empty!');
        }

        if (empty($items[2])) {
            throw new Exception('Signature is empty!');
        }

        $jwtObjConfig = array_merge(
            $header,
            $payload,
            [
                'header' => $items[0],
                'payload' => $items[1],
                'signature' => $items[2],
                'secretKey' => $this->secretKey,
                'alg' => $this->alg
            ],
            ['prefix' => $this->prefix]
        );
        return new JwtObject($jwtObjConfig);
    }

    public function setResponse(Response $response): self
    {
        $this->response = $response;
        return $this;
    }
}
