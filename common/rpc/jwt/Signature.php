<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-24 13:49:33
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-09-20 15:50:57
 */

namespace common\rpc\jwt;


class Signature extends \stdClass
{

    protected string $secretKey;
    protected string $header;
    protected string $payload;
    protected $alg;

    public function __toString()
    {
        $content = $this->header . '.' . $this->payload;

        $signature = "";
        switch ($this->alg) {
            case Jwt::ALG_METHOD_HMACSHA256:
            case Jwt::ALG_METHOD_HS256:
                $signature = Encryption::base64UrlEncode(
                    hash_hmac('sha256', $content, $this->secretKey, true)
                );
                break;
            case Jwt::ALG_METHOD_AES:
                $signature = Encryption::base64UrlEncode(
                    openssl_encrypt($content, 'AES-128-ECB', $this->secretKey)
                );
                break;
            case Jwt::ALG_METHOD_RS256:
                $success = openssl_sign($content, $signature, $this->secretKey, 'SHA256');
                if (!$success) {
                    $signature = "";
                } else {
                    $signature = Encryption::base64UrlEncode($signature);
                }
                break;
            default:
                $signature = "";
        }
        return $signature;
    }

    function __construct(array $data)
    {
        foreach ($data as $key => $item) {
            $this->{$key} = $item;
        }
    }
}
