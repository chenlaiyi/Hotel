<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2014-10-21 15:23:57
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-03-06 00:51:09
 */


namespace common\modules\openWeixin\models\enums;

use common\components\BaseEnum;

class errorCode extends BaseEnum
{
	const OK = 0;
	const ValidateSignatureError = -40001;
	const ParseXmlError = -40002;
	const ComputeSignatureError = -40003;
	const IllegalAesKey = -40004;
	const ValidateAppidError = -40005;
	const EncryptAESError = -40006;
	const DecryptAESError = -40007;
	const IllegalBuffer = -40008;
	const EncodeBase64Error = -40009;
	const DecodeBase64Error = -40010;
	const GenReturnXmlError = -40011;

    public static $messageCategory = 'App';


    protected static $list = [
        self::OK =>'success',
        self::ValidateSignatureError =>'签名验证错误',
        self::ParseXmlError =>'xml解析失败',
        self::ComputeSignatureError =>'sha加密生成签名失败',
        self::IllegalAesKey =>'encodingAesKey',
        self::ValidateAppidError =>'appid',
        self::EncryptAESError =>'aes',
        self::DecryptAESError =>'aes',
        self::IllegalBuffer =>'解密后得到的buffer非法',
        self::EncodeBase64Error =>'base64加密失败',
        self::DecodeBase64Error =>'base64解密失败',
        self::GenReturnXmlError =>'生成xml失败',
    ];
}
