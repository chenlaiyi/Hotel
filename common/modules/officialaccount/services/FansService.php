<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-04-27 15:31:25
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-04-28 14:15:28
 */

namespace common\modules\officialaccount\services;

use common\helpers\ArrayHelper;
use common\helpers\ErrorsHelper;
use common\helpers\loggingHelper;
use common\modules\officialaccount\models\DdWechatFans;
use common\services\BaseService;
use EasyWeChat\Kernel\Exceptions\HttpException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class FansService.
 *
 * @author wangchunsheng <2192138785@qq.com>
 *
 * @property-read string|int $countFollow
 */
class FansService extends BaseService
{

    static protected string $tagCatch = '';

    /**
     * 获取所有标签
     * @return mixed
     * @throws InvalidConfigException|UnprocessableEntityHttpException
     */
    public static function getTagAll(): mixed
    {
        $wechat = OfficialaccountService::getWechatApp(1);
        //        判断$wechat 是否是EasyWeChat\OpenPlatform\Authorizer\OfficialAccount\Application
        if (!is_object($wechat)){
            throw new UnprocessableEntityHttpException('请检查公众号配置是否正确');
        }
        self::$tagCatch = $wechat->config['app_id'].'fansTag';
        if (Yii::$app->cache->get(self::$tagCatch)){
            return Yii::$app->cache->get(self::$tagCatch);
        }
        $list = $wechat->user_tag->list();
        Yii::$app->cache->set(self::$tagCatch,$list);
        return $list;
    }



    /**
     * 创建标签
     * @param $title
     * @return mixed
     * @throws InvalidConfigException
     */
    static function createTag($title): mixed
    {
        $wechat = OfficialaccountService::getWechatApp(1);
        self::$tagCatch = $wechat->config['app_id'].'fansTag';
        Yii::$app->cache->delete(self::$tagCatch);
        return $wechat->user_tag->create($title);
    }

    /**
     * 修改标签信息
     * @param $tagId
     * @param $name
     * @return mixed
     * @throws InvalidConfigException
     */
    static function UpTag($tagId, $name): mixed
    {
        $wechat = OfficialaccountService::getWechatApp(1);
        self::$tagCatch = $wechat->config['app_id'].'fansTag';
        Yii::$app->cache->delete(self::$tagCatch);
        return $wechat->user_tag->update($tagId, $name);
    }

    /**
     * 删除标签
     * @param $tagId
     * @return mixed
     * @throws InvalidConfigException
     */
    static function delTag($tagId): mixed
    {
        $wechat = OfficialaccountService::getWechatApp(1);
        self::$tagCatch = $wechat->config['app_id'].'fansTag';
        Yii::$app->cache->delete(self::$tagCatch);
        return $wechat->user_tag->delete($tagId);
    }

    /**
     * 获取指定 openid 用户所属的标签
     * @param $openId
     * @return mixed
     * @throws InvalidConfigException
     */
    static function userTags($openId): mixed
    {
        $wechat = OfficialaccountService::getWechatApp(1);

        //
        // {
        //     "tagid_list":["标签1","标签2"]
        // }
        return $wechat->user_tag->userTags($openId);
    }

    /**
     * 批量为用户添加标签
     * @param $tagId
     * @param $openIds
     * @return mixed
     * @throws InvalidConfigException
     */
    static function tagUsers($tagId,$openIds): mixed
    {
        $wechat = OfficialaccountService::getWechatApp(1);
        DdWechatFans::updateAll([
            'tag'=> json_encode([$tagId])
        ],[
            'openid'=>$openIds
        ]);
        return $wechat->user_tag->tagUsers($openIds, $tagId);
    }

    /**
     * 批量为用户移除标签
     * @param $tagId
     * @param $openIds
     * @return mixed
     * @throws InvalidConfigException
     */
    static function untagUsers($tagId,$openIds): mixed
    {
        $wechat = OfficialaccountService::getWechatApp(1);
        DdWechatFans::updateAll([
            'tag'=> json_encode([])
        ],[
            'openid'=>$openIds
        ]);
        return $wechat->user_tag->untagUsers($openIds, $tagId);
    }

    /**
     * @param $openid
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function follow($openid): void
    {
        // 获取用户信息
        $user = Yii::$app->wechat->app->user->get($openid);
        $user = ArrayHelper::toArray($user);
        loggingHelper::writeLog('officialaccount', 'FansService', '粉丝数据', [
            'user' => $user,
        ]);
        $Res = Yii::$app->fans->signup($user);
        $user_id = $Res['fans']['user_id'];
        loggingHelper::writeLog('officialaccount', 'FansService', '粉丝数据注册后', [
            'Res' => $Res,
            'openid' => $openid,
        ]);
        $fans = $this->findModel($openid);

        loggingHelper::writeLog('officialaccount', 'FansService', '更新关注事件', [
            'fans' => $fans,
            'sql' => DdWechatFans::find()->where(['openid' => $openid])->findBloc()->findStore()->createCommand()->getRawSql(),
        ]);

        $fans->groupid = $user['groupid'];
        $fans->avatarUrl = $user['headimgurl'];
        $fans->unionid = $user['unionid'];
        $fans->followtime = date('Y-m-d H:i:s', $user['subscribe_time']);
        $fans->follow = DdWechatFans::FOLLOW_ON;
        $Res = $fans->save();
        if (!$Res) {
            $msg = ErrorsHelper::getModelError($fans);
            loggingHelper::writeLog('officialaccount', 'FansService', '保存粉丝数据', [
                'Res' => $Res,
                'msg' => $msg,
            ]);
        }
    }

    /**
     * 取消关注.
     *
     * @param $openid
     */
    public function unFollow($openid): void
    {
        if ($fans = DdWechatFans::find()->where(['openid' => $openid])->findStore()->findBloc()->one()) {
            $fans->follow = DdWechatFans::FOLLOW_OFF;
            $fans->unfollowtime = date('Y:m:d H:i:s', time());
            $fans->save();
        }
    }

    /**
     * 同步所有粉丝openid.
     *
     * @return array
     *
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws Exception
     * @throws UnprocessableEntityHttpException|InvalidConfigException
     */
    static function syncAllOpenid(): array
    {
        $wechat = OfficialaccountService::getWechatApp(1);
        // 获取全部列表
        $fans_list = $wechat->user->list();
        // Yii::$App->debris->getWechatError($fans_list);
        $pageSize = 100;
        $fans_count = $fans_list['total'];
        $total_page = ceil($fans_count / $pageSize);
        for ($i = 0; $i < $total_page; ++$i) {
            $fans = array_slice($fans_list['data']['openid'], $i * $pageSize, $pageSize);
            // 系统内的粉丝
            $system_fans = self::getListByOpenids($fans);
            $new_system_fans = ArrayHelper::arrayKey($system_fans, 'openid');
            $users = $wechat->user->select($fans);
            $user_info_list = $users['user_info_list'];
            $add_fans = [];
            foreach ($fans as $k => $openid) {
                if (empty($new_system_fans) || empty($new_system_fans[$openid])) {
                    $add_fans[] = [
                        0,
                        $openid,
                        $user_info_list[$k]['unionid'],
                        $user_info_list[$k]['sex'],
                        $user_info_list[$k]['subscribe'],
                        date('Y-m-d H:i:s', $user_info_list[$k]['subscribe_time']),
                        json_encode($user_info_list[$k]['tagid_list']),
                        Yii::$app->request->input('store_id', 0),
                        Yii::$app->request->input('bloc_id', 0)
                    ];
                }
            }

            if (!empty($add_fans)) {
                // 批量插入数据
                $field = [
                    'member_id',
                    'openid',
                    'unionid',
                    'gender',
                    'follow',
                    'followtime',
                    'tag',
                    'store_id',
                    'bloc_id',
                ];
                Yii::$app->db->createCommand()->batchInsert(DdWechatFans::tableName(), $field, $add_fans)->execute();
            }

            // 更新当前粉丝为关注
            DdWechatFans::updateAll(['follow' => 1], ['in', 'openid', $fans]);
        }

        return [$fans_list['total'], !empty($fans_list['data']['openid']) ? $fans_count : 0, $fans_list['next_openid']];
    }

    /**
     * @param $fan_id
     *
     * @return array|\common\components\ActiveRecord\YiiActiveRecord|null
     */
    public function findByIdWithTag($fan_id): array|\common\components\ActiveRecord\YiiActiveRecord|null
    {
        return DdWechatFans::find()
            ->where(['id' => $fan_id])
            ->findBloc()->findStore()
            // ->with('tags')
            ->asArray()
            ->one();
    }

    /**
     * @param $openid
     *
     * @return array|\common\components\ActiveRecord\YiiActiveRecord|null
     */
    public function findByOpenId($openid): array|\common\components\ActiveRecord\YiiActiveRecord|null
    {
        return DdWechatFans::find()
            ->where(['openid' => $openid])
            ->findBloc()->findStore()
            ->one();
    }

    /**
     * @return array|\common\components\ActiveRecord\YiiActiveRecord[]
     */
    static function getListByOpenids(array $openids): array
    {
        return DdWechatFans::find()
            ->where(['in', 'openid', $openids])
            ->findBloc()->findStore()
            ->select('openid')
            ->asArray()
            ->all();
    }

    /**
     * @param int $page
     *
     * @return array|\common\components\ActiveRecord\YiiActiveRecord[]
     */
    public function getFollowListByPage(int $page = 0): array
    {
        return DdWechatFans::find()
            ->where(['follow' => DdWechatFans::FOLLOW_ON])
            ->findBloc()->findStore()
            ->offset(10 * $page)
            ->orderBy('id desc')
            ->limit(10)
            ->asArray()
            ->all();
    }

    /**
     * 获取关注的人数.
     *
     * @return int|string
     */
    public function getCountFollow(): int|string
    {
        return DdWechatFans::find()
            ->where(['follow' => DdWechatFans::FOLLOW_ON])
            ->findBloc()->findStore()
            ->select(['follow'])
            ->count();
    }

    /**
     * 获取用户信息.
     *
     * @param $openid
     *
     * @return array|DdWechatFans|\common\components\ActiveRecord\YiiActiveRecord|null
     */
    protected function findModel($openid): DdWechatFans|array|\common\components\ActiveRecord\YiiActiveRecord|null
    {
        if (empty($openid) || empty(($model = DdWechatFans::find()->where(['openid' => $openid])->findBloc()->findStore()->one()))) {
            return new DdWechatFans();
        }

        return $model;
    }
}
