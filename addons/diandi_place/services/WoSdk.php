<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-06-21 13:50:41
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-23 10:24:30
 */
namespace addons\diandi_place\services;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\helpers\StringHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidCallException;
class WoSdk extends BaseObject
{
    public static $apiUrl = 'http://wo-api.uni-ubi.com/v1/';
    public static $AccessKey = 'EC982D8F58CA413A88426F053861235A';
    public static $AccessSecret = '56250467123045509EC9D404AFBD98D2';
    public static $appid = 'E3CDC1CE0EB14728853D791B69AA9F1A';
    public static $token;
    public static $bloc_id;
    public static $store_id;
    public static $client_secret;
    public static $access_token;
    public static $uid;
    public static $refresh_token;
    public static $expires_in;
    public static $auth_key = 'diandiLockSdk-token';
    public static $header = [
        'ContentType' => 'application/json;charset=UTF-8',
    ];
    public static function __init()
    {
        // 鉴权
        self::authLogin();
    }
    public static function msectime()
    {
        list($msec, $sec) = explode(' ', microtime());
        $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectime;
    }
    public static function authLogin()
    {
        $token = Yii::$app->cache->get('wo_token');
        // if ($token) {
        //     self::$token = $token;
        //     return $token;
        // }
        // 设备序列号：84E0F425C137527A
        // 项目ID:E3CDC1CE0EB14728853D791B69AA9F1A
        // AccessKey: EC982D8F58CA413A88426F053861235A
        // AccessSecret: 56250467123045509EC9D404AFBD98D2
        $data = [];
        $timestamp = self::msectime();
        $headers = [
            'appKey' => self::$AccessKey,
            'timestamp' => $timestamp,
            'sign' => md5(self::$AccessKey . $timestamp . self::$AccessSecret),
        ];
        loggingHelper::writeLog('diandi_place', 'WoSdk/authLogin', '授权头部数据', $headers);
        try {
            $Res = self::getHttp($data, 'auth', [], $headers);
        } catch (GuzzleException $e) {
            return ResultHelper::json(400, $e->getMessage());
        }
        loggingHelper::writeLog('diandi_place', 'WoSdk/authLogin', '授权结果', $Res);
        if ((int) $Res['success'] === 1) {
            Yii::$app->cache->set('wo_token', $Res['data']);
            self::$token = $Res['data'];
            return $Res['data'];
        }else{
            return ResultHelper::json(400, '请求错误');
        }
    }
    public static function createUrl(): string
    {
        return self::$apiUrl . self::$appid . '/';
    }
    /**
     * @param $datas
     * @param $url
     * @param array $params
     * @param array $headers
     * @return array
     * @throws GuzzleException
     */
    public static function postHttp($datas, $url, array $params = [], array $headers = []): array
    {
        $headersToeken = array_merge(self::$header, [
            'token' => self::$token,
        ]);
        $headers = array_merge(self::$header, $headers, $headersToeken);
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => self::createUrl(),
            // You can set any number of default request options.
            'timeout' => 10,
            // 'verify' => false
        ]);
        $res = $client->request('POST', $url, [
            'json' => $datas,
            'headers' => $headers,
        ]);
        $body = $res->getBody();
        $remainingBytes = $body->getContents();
        return self::analysisRes($remainingBytes);
    }
    /**
     * 统一请求
     *
     * @param $datas
     * @param $url
     * @param array $params 地址栏的参数
     * @param array $headers 请求头部
     *
     * @return mixed
     * @throws GuzzleException
     * @date 2022-05-11
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public static function putHttp($datas, $url, array $params = [], array $headers = [])
    {
        $headersToeken = array_merge(self::$header, [
            'token' => self::$token,
        ]);
        $headers = array_merge(self::$header, $headers, $headersToeken);
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => self::createUrl(),
            // You can set any number of default request options.
            'timeout' => 10,
            // 'verify' => false
        ]);
        $res = $client->request('PUT', $url, [
            'json' => $datas,
            'headers' => $headers,
        ]);
        $body = $res->getBody();
        $remainingBytes = $body->getContents();
        return self::analysisRes(json_decode($remainingBytes, true));
    }
    public static function deleteHttp($datas, $url, $params = [], $headers = [])
    {
        $headersToeken = array_merge(self::$header, [
            'token' => self::$token,
        ]);
        $headers = array_merge(self::$header, $headers, $headersToeken);
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => self::createUrl(),
            // You can set any number of default request options.
            'timeout' => 10,
            // 'verify' => false
        ]);
        $res = $client->request("DELETE", $url, [
            'json' => $datas,
            'headers' => $headers,
        ]);
        $body = $res->getBody();
        $remainingBytes = $body->getContents();
        return self::analysisRes(json_decode($remainingBytes, true));
    }
    /**
     * 统一请求
     *
     * @param $datas
     * @param $url
     * @param array $params 地址栏的参数
     * @param array $headers 请求头部
     *
     * @return mixed
     * @throws GuzzleException
     * @date 2022-05-11
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public static function getHttp($datas, $url, array $params = [], array $headers = [])
    {
        $headers = array_merge(self::$header, $headers);
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => self::createUrl(),
            // You can set any number of default request options.
            // 'timeout' => 10,
            // 'verify' => false
        ]);
        $res = $client->request('GET', $url, [
            'query' => $datas,
            'headers' => $headers,
        ]);
        $body = $res->getBody();
        $remainingBytes = $body->getContents();
        return self::analysisRes(json_decode($remainingBytes, true));
    }
    public static function createData($data)
    {
        return $data;
    }
    /**
     * @param $Res
     * @return array
     */
    public static function analysisRes($data): array
    {
        $Res = json_decode($data, true);
        if ((int) $Res['errcode']) {
            throw new InvalidCallException($Res['message']);
        } else {
            return [
                'code' => $Res['resultCode'],
                'content' => $Res['reason'],
            ];
        }
    }
    /**
     * 人员创建
     *
     * @param $cardNo
     * @param $name
     * @param $phone
     * @param $tag
     * @param $idCardNo
     * @return mixed|null
     * @throws GuzzleException
     * @date 2022-06-28
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public static function personCreate($cardNo, $name, $phone, $tag, $idCardNo)
    {
        $data = self::createData([
            'cardNo' => $cardNo,
            'name' => $name,
            'phone' => $phone,
            'tag' => $tag,
            'idCardNo' => $idCardNo
        ]);
        loggingHelper::writeLog('diandi_place', 'diandiLockSdk/switchStatue', '开关操作数据', $data);
        $Res = self::postHttp($data, 'person');
        loggingHelper::writeLog('diandi_place', 'diandiLockSdk/switchStatue', '开关操作数据结果', $Res);
        if ($Res['code'] === 200) {
            return $Res['data'];
        }
        return $Res;
    }
    public function webhook($type)
    {
        // 1识别回调 2授权回调 3销权回调 6设备删除回调
    }
    /**
     * Undocumented function
     * @param $personGuid
     * @param $url
     * @param string $bizData
     * @return array
     * @throws GuzzleException
     * @date 2023-04-23
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function faceAdd($personGuid, $url, string $bizData = '')
    {
        $data = self::createData([
            // 'base64' => $base64, //	String	Y		照片base64（base64,url选填一个即可）
            'personGuid' => $personGuid, //	String	N		照片所有者（人员）guid
            'url' => $url, //	String	Y		照片url（base64,url选填一个即可）
            // 'bizData' => $bizData, //	String	Y		示例 {"bottom":206,"empty":false,"left":100,"right":407,"top":95} 设备提取特征时：若传入此字段且格式正确，则使用此字段；若未传入或格式不正确则无效
        ]);
        loggingHelper::writeLog('diandi_place', 'faceAdd', '开关操作数据', $data);
        $Res = self::postHttp($data, 'face');
        loggingHelper::writeLog('diandi_place', 'faceAdd', '开关操作数据结果', $Res);
        if ($Res['code'] === 200) {
            return $Res['data'];
        }
        return $Res;
    }
    public static function authDevice($deviceKey, $personGuid, $type = 1): array|bool
    {
        // {deviceKey}/person/{personGuid}/type/{type}
        // 84E0F425C137527A/person/CED92E38A7F340BABB9066DC7454961E/type/84E0F425C137527A/person/CED92E38A7F340BABB9066DC7454961E/type/1
        $data = [];
        $url  = 'device/' . $deviceKey . "/person/" . $personGuid . "/type/" . $type;
        loggingHelper::writeLog('diandi_place', 'authDevice', '人脸机授权数据', $data);
        try {
            $Res = self::putHttp($data, $url);
        } catch (GuzzleException $e) {
            return ResultHelper::json(400, $e->getMessage());
        }
        loggingHelper::writeLog('diandi_place', 'authDevice', '人脸机授权结果', $Res);
        if ((int) $Res['result'] === 1) {
            return true;
        }
        return false;
    }
    public static function deleteDevice($deviceKey, $personGuid, $type = 1): bool
    {
        // {deviceKey}/person/{personGuid}/type/{type}
        // 84E0F425C137527A/person/CED92E38A7F340BABB9066DC7454961E/type/84E0F425C137527A/person/CED92E38A7F340BABB9066DC7454961E/type/1
        $data = [];
        $url  = 'device/' . $deviceKey . "/person/" . $personGuid . "/type/" . $type;
        loggingHelper::writeLog('diandi_place', 'authDevice', '人脸机授权数据', $data);
        $Res = self::deleteHttp($data, $url);
        loggingHelper::writeLog('diandi_place', 'authDevice', '人脸机授权结果', $Res);
        if ((int) $Res['result'] === 1) {
            return true;
        }
        return false;
    }
    /**
     * 图片地址转64位
     * @param [type] $image
     * @return string
     * @date 2023-04-06
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function imgToBase64($image): string
    {
        // 获取图像并转换为字符串
        $img = file_get_contents($image);
        // 取得图片的大小，类型等
        $img_info = getimagesize($image);
        // 将图像字符串数据编码为base64
        $file_content = base64_encode($img);
        //判读图片类型
        $img_type = match ($img_info[2]) {
            2 => "jpg",
            3 => "png",
            default => "gif",
        };
        // 显示输出
        //合成图片的base64编码
        return 'data:image/' . $img_type . ';base64,' . $file_content;
    }
}
WoSdk::__init();
