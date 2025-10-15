<?php


namespace common\rpc\jwt;


use EasySwoole\Component\Singleton;

class Encryption
{
    use Singleton;
    public static function base64UrlEncode($content): array|string
    {
        return str_replace('=', '', strtr(base64_encode($content), '+/', '-_'));
    }

    public static function base64UrlDecode($content): bool|string
    {
        $remainder = strlen($content) % 4;
        if ($remainder) {
            $addlen = 4 - $remainder;
            $content .= str_repeat('=', $addlen);
        }
        return base64_decode(strtr($content, '-_', '+/'));
    }

}
