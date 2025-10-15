<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2014-10-21 15:23:57
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-03-06 00:49:27
 */

namespace common\modules\openWeixin\services\wechatMsgHelp;

use common\modules\openWeixin\services\decrypted;
use common\services\BaseService;

/**
 * PKCS7Encoder class
 *
 * 提供基于PKCS7算法的加解密接口.
 */
class pkcs7Encoder extends BaseService
{
    public static int $block_size = 32;

	/**
	 * 对需要加密的明文进行填充补位
	 * @param $text //需要进行填充补位操作的明文
	 * @return string
	 */
	function encode($text): string
    {
		$block_size = self::$block_size;
		$text_length = strlen($text);
		//计算需要填充的位数
		$amount_to_pad = $block_size - ($text_length % $block_size);
		if ($amount_to_pad == 0) {
			$amount_to_pad = $block_size;
		}
		//获得补位所用的字符
		$pad_chr = chr($amount_to_pad);
        $tmp = str_repeat($pad_chr, $amount_to_pad);
		return $text . $tmp;
	}

	/**
	 * 对解密后的明文进行补位删除
	 * @param  $text //解密后的明文
	 * @return string//删除填充补位后的明文
	 */
	function decode($text): string
    {

		$pad = ord(substr($text, -1));
		if ($pad < 1 || $pad > 32) {
			$pad = 0;
		}
		return substr($text, 0, (strlen($text) - $pad));
	}
}
