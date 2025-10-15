<?php

namespace common\services\common;

use admin\services\AuthService;
use admin\services\UserService;
use common\helpers\ImageHelper;
use common\helpers\loggingHelper;
use common\models\WebsiteStationGroup;
use common\services\BaseService;
use Yii;
use yii\db\Query;
use yii\web\HttpException;

class WebsiteGroup extends BaseService
{
    /**
     * 校验当前用户是否和站点信息公司一致
     *
     * @throws \Exception
     */
    static function checkUserCompany($user_id): bool
    {
        if (UserService::isSuperAdmin()) {
            return true;
        }
        $web_bloc_id = self::getCompanyId();
        /**
         * 优先使用user
         */
        $bloc_id = (new Query())->from("{{%user}}")->where(['id' => $user_id])->select('bloc_id')->scalar();
        if (empty($bloc_id)) {
            $bloc_id = (new Query())->from("{{%customer_user}}")->where(['id' => $user_id])->select('bloc_id')->scalar();
        }
        loggingHelper::writeLog('WebsiteGroup', 'checkUserCompany', '用户所属公司信息', [
            'user_id'     => $user_id,
            'bloc_id'     => $bloc_id,
            'userType'    => Yii::$app->params['userType'],
            'token'       => Yii::$app->request->input('access_token', ''),
            'web_bloc_id' => $web_bloc_id,
        ]);
        return $bloc_id == $web_bloc_id;
    }


    /**
     * 获取域名出去协议部分的内容
     */
    static function getDomainUrl(): string
    {
        $url = \Yii::$app->request->hostInfo;
        /**
         * 去掉前面的http:// 和https://
         */
        return str_replace(['http://', 'https://'], '', $url);
    }

    /**
     * 获取当前域名所属公司
     */
    static function getCompanyId(): int
    {
        $Origin  = self::getDomainUrl();
        $bloc_id = WebsiteStationGroup::find()->where(['domain_url' => $Origin])->select('bloc_id')->scalar();
        if (empty($bloc_id)) {
            throw new HttpException(400,'当前域名未授权');
        }
        return $bloc_id;
    }

    /**
     * 根据域名获取站点信息
     */
    static function getWebsiteInfo(): array
    {
        $settings = Yii::$app->settings;
        $settings->invalidateCache();
        $bloc_id = Yii::$app->request->input('bloc_id');
        $info    = $settings->getAllBySection('Website');
        $isImg   = Yii::$app->request->input('isImg', 0);

        $list = $info;
        $Origin  = self::getDomainUrl();
        if ($Origin && !strpos($Origin, 'localhost') && !strpos($Origin, '127.0.0.1')) {
            $url   = parse_url($Origin);
            $query = WebsiteStationGroup::find()->where(['domain_url' => $url]);
//            if (!empty($bloc_id)) {
//                $query->andWhere(['bloc_id' => $bloc_id]);
//            }
            $infoUrl                 = $query->asArray()->one();
            $infoUrl                 = $infoUrl ?? [];
            $infoUrl['is_send_code'] = $info['is_send_code'] ?? 0;
            $list = (array) $infoUrl;
        }


        if ((int) $isImg === 1) {
            unset(
                $list['qcloud_sdk_app_id'],
                $list['qcloud_secret_id'],
                $list['qcloud_secret_key'],
                $list['qcloud_sign_name'],
                $list['sign_name'],
                $list['site_status'],
                $list['template_code'],
                $list['access_key_id'],
                $list['access_key_secret'],
                $list['aliyun_access_key_id'],
                $list['aliyun_access_key_secret'],
                $list['aliyun_sign_name'],
                $list['aliyun_template_code']
            );

        }


        //没有登录的登录页使用
        if (isset($list['blogo'])) {
            $list['blogo'] = ImageHelper::tomedia($list['blogo']);
        }

        if (isset($list['loginbg'])) {
            $list['loginbg'] = ImageHelper::tomedia($list['loginbg'], 'loginbg.jpg');
        }

        if (isset($list['flogo'])) {
            $list['flogo'] = ImageHelper::tomedia($list['flogo']);
        }

//        ico
        if (isset($list['ico'])) {
            $list['ico'] = ImageHelper::tomedia($list['ico']);
        }

        $list['origin'] = $Origin;
        return $list;
    }

    /**
     * 允许域名
     *
     * @return array
     */
    static function getAllowedDomain(): array
    {
        $urls = \Yii::$app->cache->get('allowed_domain');
        if ($urls) {
            return $urls;
        }
        $urls = WebsiteStationGroup::find()->select('domain_url')->cache()->column();
        foreach ($urls as &$url) {
            if (!(str_starts_with($url, 'Http://')) && !(str_starts_with($url, 'https://'))) {
                $url = 'https://' . $url;
            }
        }
        \Yii::$app->cache->set('allowed_domain', $urls);
        return $urls ?? [];
    }

    /**
     * 允许域名
     *
     * @return string
     */
    static function getAllowedDomainStr(): string
    {

        $urls = WebsiteStationGroup::find()->select('domain_url')->column();
        foreach ($urls as &$url) {
            if (!(str_starts_with($url, 'Http://')) && !(str_starts_with($url, 'https://'))) {
                $url = 'https://' . $url;
            }
        }

        return implode(',', $urls ?? []);
    }

    /**
     * @param $origin
     * @return bool
     */
    static function checkHeaderOrigin($origin): bool
    {

        if (in_array($origin, self::getAllowedDomain())) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkIp(mixed $ip)
    {
        loggingHelper::writeLog('WebsiteGroup', 'checkIp', '请求的ip地址记录', [
            'ip'     => $ip,
            'params' => \Yii::$app->request->input(),
            'url'    => \Yii::$app->request->url,
        ]);
        $notAllowdIps   = env('not_allowd_ips');
        $notAllowdIpArr = explode(',', $notAllowdIps);
        if ($notAllowdIps && in_array($ip, $notAllowdIpArr ?? [])) {
            return false;
        } else {
            return true;
        }
    }

}