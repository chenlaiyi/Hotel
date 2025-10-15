<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2014-10-21 15:23:57
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-03-06 00:50:03
 */

namespace common\modules\openWeixin\services\wechatMsgHelp;


use common\modules\openWeixin\models\enums\errorCode;
use common\services\BaseService;

/**
 * SHA1 class
 *
 * 计算公众平台的消息签名接口.
 */
class sha1 extends BaseService
{
    /**
     * 用SHA1算法生成安全签名
     * @param string $token 票据
     * @param string $timestamp 时间戳
     * @param string $nonce 随机字符串
     * @param $encrypt_msg
     * @return array
     */
	public function getSHA1(string $token, string $timestamp, string $nonce, $encrypt_msg): array
    {
		//排序
		try {
			$array = array($encrypt_msg, $token, $timestamp, $nonce);
			sort($array, SORT_STRING);
			$str = implode($array);
			return array(errorCode::OK, sha1($str));
		} catch (\Exception $e) {
			//print $e . "\n";
			return array(errorCode::ComputeSignatureError, null);
		}
	}
}
