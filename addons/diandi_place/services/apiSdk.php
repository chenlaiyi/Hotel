<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-06-21 13:50:41
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-23 09:52:06
 */
namespace addons\diandi_place\services;
use common\helpers\loggingHelper;
use common\helpers\StringHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidCallException;
class apiSdk extends BaseObject
{
    public static string $apiUrl = 'https://www.miniscores.net:8313/CreditFunc/v2.1/IdNamePhotoCheck';
    public static string $username;
    public static string $password;
    public static string $serviceName;
    public static int $mvTrackId;
    public static int $bloc_id;
    public static int $store_id;
    public static string $client_secret;
    public static string $access_token;
    public static int $uid;
    public static string $refresh_token;
    public static int $expires_in;
    public static string $auth_key = 'diandiLockSdk-token';
    public static array $header = [
        'ContentType' => 'application/json;charset=UTF-8',
    ];
    public static function __init(): void
    {
        $confPath = yii::getAlias('@addons/diandi_place/config/diandi.php');
        if (file_exists($confPath)) {
            $config = require $confPath;
            self::$username = $config['username'];
            self::$password = $config['password'];
            self::$bloc_id = (int) $config['bloc_id'];
            self::$store_id = (int) $config['store_id'];
            self::$serviceName = $config['serviceName'];
            // 20170926105632_IdNamePhotoCheck_xxKeji_sa23jhfu
            $str  = StringHelper::uuid('md5');
            $str = substr($str, 0, 8);
            self::$mvTrackId =  date('yyyyMMddHHmmss', time()) . '_' . self::$serviceName . '_' . self::$username . '_' . $str;
            // 鉴权
            // self::apartmentLogin(self::$username, self::$password, self::$serviceName, self::$bloc_id, self::$store_id);
        } else {
            self::putAuthConf();
        }
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
    public static function postHttp($datas, $url, array $params = [], array $headers = []): mixed
    {
        $headersToeken = array_merge(self::$header, [
            'mvTrackId' => self::$mvTrackId,
        ]);
        $headers = array_merge(self::$header, $headers, $headersToeken);
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => self::$apiUrl,
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
        return self::analysisRes(json_decode($remainingBytes, true));
    }
    public static function createData($data)
    {
        return $data;
    }
    // 解析返回的内容
    public static function analysisRes($Res)
    {
        if ((int) $Res['errcode']) {
            throw new InvalidCallException($Res['message']);
        } else {
            $data = [
                'code' => $Res['resultCode'],
                'content' => $Res['reason'],
            ];
            return $Res;
        }
    }
    public static function putAuthConf($username = '', $password = '', $bloc_id = '', $store_id = ''): void
    {
        $confPath = yii::getAlias('@addons/diandi_place/config/diandi.php');
        if (!file_exists($confPath)) {
            $config = self::local_auth_config();
            $config = str_replace([
                '{username}', '{password}', '{bloc_id}', '{store_id}',
            ], [
                $username, $password, $bloc_id, $store_id,
            ], $config);
            file_put_contents($confPath, $config);
        }
    }
    /**
     * Undocumented function
     * @param $name
     * @param $idCard
     * @param $image
     * @return array
     * @throws GuzzleException
     * @date 2023-04-23
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function querySingle($name, $idCard, $image): array
    {
        $data = self::createData([
            'loginName' => self::$username,
            'pwd' => self::$password,
            'serviceName' => self::$serviceName,
            'param' => [
                "name" => $name,
                "idCard" => $idCard,
                "image" => self::imgToBase64($image)
            ]
        ]);
        loggingHelper::writeLog('diandi_place', 'querySingle', '校验数据', $data);
        $Res = self::postHttp($data, '');
        loggingHelper::writeLog('diandi_place', 'querySingle', '校验结果', $Res);
        if ($Res['code'] === 200) {
            return $Res['data'];
        }
        return $Res;
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
        switch ($img_info[2]) {
            case 1:
                $img_type = "gif";
                break;
            case 2:
                $img_type = "jpg";
                break;
            case 3:
                $img_type = "png";
                break;
        }
        // 显示输出
        // $img_base64 = 'data:image/' . $img_type . ';base64,' . $file_content; //合成图片的base64编码
        $img_base64 = $file_content; //合成图片的base64编码
        return  $img_base64;
    }
    public static function local_auth_config(): string
    {
        $cfg = <<<EOF
<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-01-18 16:51:31
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-02-28 10:21:41
 */
return [
    'username' => '{username}',
    'password' => '{password}',
    'bloc_id' => '{bloc_id}',
    'store_id' => '{store_id}',
];
EOF;
        return trim($cfg);
    }
}
apiSdk::__init();
