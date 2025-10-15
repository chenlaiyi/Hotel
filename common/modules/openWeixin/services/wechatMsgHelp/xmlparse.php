<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2014-10-21 15:23:57
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-03-06 00:49:45
 */

namespace common\modules\openWeixin\services\wechatMsgHelp;

use common\modules\openWeixin\models\enums\errorCode;
use common\services\BaseService;
use DOMDocument;
use Exception;

/**
 * XMLParse class
 *
 * 提供提取消息格式中的密文及生成回复消息格式的接口.
 */
class xmlparse extends BaseService
{

	/**
	 * 提取出xml数据包中的加密消息
	 * @param string $xmlText 待提取的xml字符串
	 * @return array
     */
	public function extract(string $xmlText): array
    {
		try {
			$xml = new DOMDocument();
			$xml->loadXML($xmlText);
			$array_e = $xml->getElementsByTagName('Encrypt');
			$array_a = $xml->getElementsByTagName('ToUserName');
			$encrypt = $array_e->item(0)->nodeValue;
			$toUserName = $array_a->item(0)->nodeValue;
			return array(0, $encrypt, $toUserName);
		} catch (Exception $e) {
			//print $e . "\n";
			return array(errorCode::ParseXmlError, null, null);
		}
	}

	/**
	 * 生成xml消息
	 * @param string $encrypt 加密后的消息密文
	 * @param string $signature 安全签名
	 * @param string $timestamp 时间戳
	 * @param string $nonce 随机字符串
	 */
	public function generate(string $encrypt, string $signature, string $timestamp, string $nonce): string
    {
		$format = "<xml>
<Encrypt><![CDATA[%s]]></Encrypt>
<MsgSignature><![CDATA[%s]]></MsgSignature>
<TimeStamp>%s</TimeStamp>
<Nonce><![CDATA[%s]]></Nonce>
</xml>";
		return sprintf($format, $encrypt, $signature, $timestamp, $nonce);
	}
}
