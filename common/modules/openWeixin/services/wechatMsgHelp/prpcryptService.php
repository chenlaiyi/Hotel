<?php

namespace common\modules\openWeixin\services\wechatMsgHelp;

use common\modules\openWeixin\models\enums\errorCode;
use common\services\BaseService;
use Exception;

class prpcryptService extends BaseService
{
    public $key;

    function __construct($k)
    {
        $this->key = base64_decode($k . "=");
        parent::__construct();
    }

    public function encrypt($text, $appid): array
    {
        try {
            $random = $this->getRandomStr();
            $text = $random . pack("N", strlen($text)) . $text . $appid;
            $iv = substr($this->key, 0, 16);
            $pkc_encoder = new pkcs7Encoder;
            $text = $pkc_encoder->encode($text);
            $encrypted = openssl_encrypt($text, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
            return array(errorCode::OK, base64_encode($encrypted));
        } catch (Exception $e) {
            return array(errorCode::EncryptAESError, null);
        }
    }


    public function decrypt($encrypted, $appid)
    {
        try {
            $ciphertext_dec = base64_decode($encrypted);
            $iv = substr($this->key, 0, 16);
            $decrypted = openssl_decrypt($ciphertext_dec, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
        } catch (Exception $e) {
            return array(errorCode::DecryptAESError, null);
        }

        try {
            $pkc_encoder = new pkcs7Encoder;
            $result = $pkc_encoder->decode($decrypted);
            if (strlen($result) < 16)
                return "";
            $content = substr($result, 16, strlen($result));
            $len_list = unpack("N", substr($content, 0, 4));
            $xml_len = $len_list[1];
            $xml_content = substr($content, 4, $xml_len);
            $from_appid = substr($content, $xml_len + 4);
        } catch (Exception $e) {
            return array(errorCode::IllegalBuffer, null);
        }
        if ($from_appid != $appid)
            return array(errorCode::ValidateAppidError, null);
        return array(0, $xml_content);
    }


    function getRandomStr()
    {
        $str = "";
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($str_pol) - 1;
        for ($i = 0; $i < 16; $i++) {
            $str .= $str_pol[random_int(0, $max)];
        }
        return $str;
    }
}
