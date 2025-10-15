<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-24 13:49:33
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-24 14:37:35
 */

namespace common\rpc\jwt;


use Common\Helpers\StringHelper;
use DomainException;
use UnexpectedValueException;

class JwtObject extends \stdClass
{
    public const STATUS_OK = 1;
    public const STATUS_SIGNATURE_ERROR = -1;
    public const STATUS_EXPIRED = -2;


    protected string $alg = Jwt::ALG_METHOD_HMACSHA256; // 加密方式
    protected string $iss = 'EasySwoole'; // 发行人
    protected mixed $exp; // 到期时间
    protected mixed $sub; // 主题
    protected mixed $nbf; // 在此之前不可用
    protected mixed $aud; // 用户
    protected mixed $iat; // 发布时间
    protected mixed $jti; // JWT ID用于标识该JWT
    protected mixed $signature; // 加密的token
    protected int $status = 0;
    protected array $data = []; // 自定义数据
    protected string $prefix = ''; // token前缀

    protected string $secretKey = '';
    protected string $header = '';
    protected string $payload = '';


    protected array $algMap = [
        Jwt::ALG_METHOD_HMACSHA256 => 'HMAC-SHA256',
        Jwt::ALG_METHOD_AES => 'AES-128-ECB',
        Jwt::ALG_METHOD_RS256 => 'SHA256'
    ];

    function __construct(array $data)
    {
        foreach ($data as $key => $item) {
            $this->{$key} = $item;
        }
        $this->initialize();
    }

    protected function initialize(): void
    {
        if (empty($this->nbf)) {
            $this->nbf = time();
        }
        if (empty($this->iat)) {
            $this->iat = time();
        }
        if (empty($this->exp)) {
            $this->exp = time() + 7200;
        }
        if (empty($this->jti)) {
            $this->jti = StringHelper::random(10);
        }


        // 解包：验证签名
        if (!empty($this->signature)) {
            if (!$this->verify()) {
                $this->status = self::STATUS_SIGNATURE_ERROR;
                return;
            }
            if (time() > $this->exp) {
                $this->status = self::STATUS_EXPIRED;
                return;
            }
        }
        $this->status = self::STATUS_OK;
    }

    /**
     * @return bool
     */
    protected function verify(): bool
    {
        $content = $this->getHeader() . "." . $this->getPayload();

        if (in_array($this->getAlg(), [Jwt::ALG_METHOD_HMACSHA256, Jwt::ALG_METHOD_HS256])) {
            $hash = hash_hmac('SHA256', $content, $this->getSecretKey(), true);
            return hash_equals($this->getSignature(), Encryption::base64UrlEncode($hash));
        }

        if (in_array($this->getAlg(), [Jwt::ALG_METHOD_AES, Jwt::ALG_METHOD_RS256])) {
            $signatureAlg = $this->algMap[$this->getAlg()] ?? null;
            if (!empty($signatureAlg)) {
                $status = openssl_verify($content, Encryption::base64UrlDecode($this->getSignature()), $this->getSecretKey(), $signatureAlg);
                if ($status < 0) {
                    throw new DomainException('OpenSSL error: ' . openssl_error_string());
                }
                return $status === 1;
            }
        }

        throw new UnexpectedValueException('Algorithm not supported, alg: ' . $this->getAlg());
    }

    /**
     * @return string
     */
    public function getAlg(): string
    {
        return $this->alg;
    }

    /**
     * @param mixed $alg
     * @return JwtObject
     */
    public function setAlg(mixed $alg): self
    {
        $this->alg = $alg;

        return $this;
    }

    /**
     * @return string
     */
    public function getIss(): string
    {
        return $this->iss;
    }

    /**
     * @param string $iss
     * @return JwtObject
     */
    public function setIss(string $iss): self
    {
        $this->iss = $iss;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExp(): mixed
    {
        return $this->exp;
    }

    /**
     * @param mixed $exp
     * @return JwtObject
     */
    public function setExp(mixed $exp): self
    {
        $this->exp = $exp;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSub(): mixed
    {
        return $this->sub;
    }

    /**
     * @param mixed $sub
     * @return JwtObject
     */
    public function setSub(mixed $sub): self
    {
        $this->sub = $sub;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNbf(): mixed
    {
        return $this->nbf;
    }

    /**
     * @param mixed $nbf
     * @return JwtObject
     */
    public function setNbf(mixed $nbf): self
    {
        $this->nbf = $nbf;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAud(): mixed
    {
        return $this->aud;
    }

    /**
     * @param mixed $aud
     * @return JwtObject
     */
    public function setAud(mixed $aud): self
    {
        $this->aud = $aud;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIat(): mixed
    {
        return $this->iat;
    }

    /**
     * @param mixed $iat
     * @return JwtObject
     */
    public function setIat(mixed $iat): self
    {
        $this->iat = $iat;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getJti(): mixed
    {
        return $this->jti;
    }

    /**
     * @param mixed $jti
     * @return JwtObject
     */
    public function setJti(mixed $jti): self
    {
        $this->jti = $jti;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSignature(): mixed
    {
        return $this->signature;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param mixed $data
     * @return JwtObject
     */
    public function setData(mixed $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * @param mixed $secretKey
     * @return JwtObject
     */
    public function setSecretKey(string $secretKey): self
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    public function setHeader($header): JwtObject
    {
        $this->header = $header;
        return $this;
    }

    public function getHeader(): string
    {
        return $this->header;
    }

    public function setPayload($payload): JwtObject
    {
        $this->payload = $payload;
        return $this;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function setPrefix(string $prefix): JwtObject
    {
        $this->prefix = $prefix . ' ';
        return $this;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function __toString()
    {
        //TODO:: 为了兼容老版本做了映射
        $algMap = [
            Jwt::ALG_METHOD_HMACSHA256 => Jwt::ALG_METHOD_HS256,
            Jwt::ALG_METHOD_AES => Jwt::ALG_METHOD_AES,
            Jwt::ALG_METHOD_HS256 => Jwt::ALG_METHOD_HS256,
            Jwt::ALG_METHOD_RS256 => Jwt::ALG_METHOD_RS256,
        ];

        $header = json_encode([
            'alg' => $algMap[$this->getAlg()],
            'typ' => 'JWT'
        ]);
        $this->header = Encryption::base64UrlEncode($header);

        $payload = json_encode([
            'exp' => $this->getExp(),
            'sub' => $this->getSub(),
            'nbf' => $this->getNbf(),
            'aud' => $this->getAud(),
            'iat' => $this->getIat(),
            'jti' => $this->getJti(),
            'iss' => $this->getIss(),
            'status' => $this->getStatus(),
            'data' => $this->getData()
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $this->payload = Encryption::base64UrlEncode($payload);

        $this->signature = (new Signature([
            'secretKey' => $this->getSecretKey(),
            'header' => $this->getHeader(),
            'payload' => $this->payload,
            'alg' => $this->getAlg()
        ]))->__toString();
        if (empty($this->prefix)) {
            return $this->header . '.' . $this->payload . '.' . $this->signature;
        } else {
            return $this->prefix . $this->header . '.' . $this->payload . '.' . $this->signature;
        }
    }
}
