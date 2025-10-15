<?php

namespace common\plugins\diandi_auth\services;

use common\plugins\diandi_auth\models\MemberList;
use common\plugins\diandi_auth\models\MemberListRole;
use common\plugins\diandi_auth\models\MemberPermission;
use common\plugins\diandi_auth\models\MemberRolePermission;
use common\plugins\diandi_auth\models\searchs\MemberRole;
use common\plugins\diandi_auth\models\ZyjMemberLinkStore;
use common\plugins\diandi_auth\models\AuthMemberList;
use common\plugins\diandi_auth\models\AuthMemberListRole;
use addons\zyj_wash\models\store\ZyjWashStore;
use api\models\DdMember;
use common\helpers\ErrorsHelper;
use common\helpers\ImageHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\services\BaseService;
use Yii;
use yii\web\NotFoundHttpException;

class MemberService extends BaseService
{
    public static function getMemberList($where = [], $page = 1, $pageSize = 10): array
    {
        $query = MemberList::find()->where($where);
        $count = $query->count();
        $list = $query->offset(($page - 1) * $pageSize)->limit($pageSize)->all();
        array_walk($list, function (&$item) {
            $item = $item->toArray();
            $roleName = [];
            $roleId = [];
            foreach ($item['role'] as $role) {
                $roleName[] = $role['role']['name'];
                $roleId[] = $role['roleId'];
            }
            $item['roleId'] = implode(',', $roleId);
            $item['roleName'] = implode(',', $roleName);

        });
        return [
            'list' => $list,
            'sql' => $query->createCommand()->getRawSql(),
            'count' => $count,
        ];
    }

    /**
     * 保存会员角色
     * @param $user_id
     * @param int $role_id
     * @return array
     * @throws NotFoundHttpException
     */
    static function saveMemberRole($user_id, int $role_id): array
    {
        $member_role = MemberListRole::find()->where(['user_id' => $user_id])->one();
        if ($member_role) {
            $member_role->roleId = $role_id;
            $member_role->save();
        }else{
            $member_role = new MemberListRole();
            $member_role->user_id = $user_id;
            $member_role->roleId = $role_id;
            if (!$member_role->save()) {
                $msg = ErrorsHelper::getModelError($member_role);
                loggingHelper::writeLog('diandi_auth', 'saveMemberRole', '保存会员角色', [
                    'msg' => $msg
                ]);
                throw new \Exception($msg);
            }
        }


        return ResultHelper::json(200, '保存成功');
    }

    public static function getMemberRole(int $id): array
    {
        $MemberListRole = new MemberListRole();
        $list = $MemberListRole::find()->where(['accountId' => $id])->with(['role' => function ($query) {
            return $query->select(['id', 'name']);
        }])->asArray()->all();
        return $list;
    }

    static function getMemberInfo(int $member_id): array|\yii\db\ActiveRecord
    {

        /**
         * 获取用户角色
         */
        $member = MemberList::find()->where(['member_id' => $member_id])->with(['role'])->asArray()->one();
        $member['avatarUrl'] = ImageHelper::tomedia($member['avatarUrl']);
        if (!$member) {
            return ResultHelper::json(404, '用户不存在');
        }

        $roles = array_column($member['role'], 'roleId');

        $rule_names = MemberRole::find()->where(['id' => $roles])->select('name')->scalar();

        $member['rule_names'] = $rule_names;

        /**
         * 获取角色权限
         */
        $permissionId = MemberRolePermission::find()->where(['roleId' => $roles])->select(['permissionId'])->column();

        /**
         * 获取权限标识
         */
        $permission= MemberPermission::find()->where(['id' => $permissionId])->select('page_name')->column();

        $member['permission'] = array_values($permission);
        return $member;
    }

    public static function editInfo($member_id, array $data)
    {
        $member = MemberList::find()->where(['member_id' => $member_id])->one();
        loggingHelper::writeLog('diandi_auth', 'editInfo', '编辑用户信息', [
            'member' => $member,
            'member_id' => $member_id,
            'data' => $data
        ]);
        if (!$member) {
            return ResultHelper::json(404, '用户不存在');
        }
        $member->setAttributes($data);
        if ($member->save()) {
            /**
             * 修改关联的门店系统里面的用户ID
             */
            $username = $member->username;
            ShopMemberLink::editMemberId($member_id, $username);
            return ResultHelper::json(200, '修改成功');
        } else {
            $msg = ErrorsHelper::getModelError($member);
            return ResultHelper::json(422, $msg);
        }
    }

    public static function editInfoByUid($user_id, array $data)
    {
        $member = MemberList::find()->where(['user_id' => $user_id])->one();
        loggingHelper::writeLog('diandi_auth', 'editInfo', '编辑用户信息', [
            'member' => $member,
            'user_id' => $user_id,
            'data' => $data
        ]);
        if (!$member) {
            return ResultHelper::json(404, '用户不存在');
        }
        $member->setAttributes($data);
        if ($member->save()) {
            /**
             * 修改关联的门店系统里面的用户ID
             */
            $username = $member->username;
            $member_id = DdMember::find()->where(['mobile' => $member['mobile']])->select('member_id')->scalar();
            ShopMemberLink::editMemberId($member_id, $username);
            return ResultHelper::json(200, '修改成功');
        } else {
            $msg = ErrorsHelper::getModelError($member);
            return ResultHelper::json(422, $msg);
        }
    }

    public static function getMemberInfoByUid($uid): array
    {
        /**
         * 获取用户角色
         */
        $member = MemberList::find()->where(['user_id' => $uid])->with(['role'])->asArray()->one();
        if (!$member) {
            return ResultHelper::json(404, '用户不存在');
        }
        $member['avatarUrl'] = ImageHelper::tomedia($member['avatarUrl']);

        $roleId =  $member['role']?$member['role']['roleId']:0;

        $rule_names = MemberRole::find()->where(['id' => $roleId])->select('name')->scalar();

        $member['rule_names'] = $rule_names;


        /**
         * 获取角色权限
         */
        $permissionId = MemberRolePermission::find()->where(['roleId' => $roleId])->select(['permissionId'])->column();

        /**
         * 获取权限标识
         */
        $member['permission'] = MemberPermission::find()->where(['id' => $permissionId])->select('page_name')->column();
        //        shopLogistics
        $shopLogisticsId = MemberPermission::find()->where(['page_name' => 'shopLogistics'])->select('id')->column();
        $shopLogisticsChilds = MemberPermission::find()->where(['pid' => $shopLogisticsId])->select('page_name')->column();
        /**
         * 用户权限中含有物流权限的其中一个，就增长权限shopLogistics
         */
        if (array_intersect($shopLogisticsChilds, $member['permission'])) {
            $member['permission'][] = 'shopLogistics';
        }
        //        factoryLogistics
        $factoryLogisticsId = MemberPermission::find()->where(['page_name' => 'factoryLogistics'])->select('id')->column();
        $factoryLogisticsChilds = MemberPermission::find()->where(['pid' => $factoryLogisticsId])->select('page_name')->column();

        if (array_intersect($factoryLogisticsChilds, $member['permission'])) {
            $member['permission'][] = 'factoryLogistics';
        }

        //        serverList
        $serverListId = MemberPermission::find()->where(['page_name' => 'serverList'])->select('id')->column();
        $serverListChilds = MemberPermission::find()->where(['pid' => $serverListId])->select('page_name')->column();
        if (array_intersect($serverListChilds, $member['permission'])) {
            $member['permission'][] = 'serverList';
        }
        //        productList
        $productListId = MemberPermission::find()->where(['page_name' => 'productList'])->select('id')->column();
        $productListChilds = MemberPermission::find()->where(['pid' => $productListId])->select('page_name')->column();
        if (array_diff($productListChilds, $member['permission'])) {
            $member['permission'][] = 'productList';
        }
        return $member;
    }

    static function bindOpenid($uid, $openid): bool
    {
        $member = MemberList::find()->where(['user_id' => $uid])->one();
        $member->openid = $openid;
        return $member->save();
    }

    /**
     * 根据当前用户找到当前门店对应的会员ID集合
     */
    public static function getMemberIdByShopId($user_id): array
    {
        $member_store_id = ZyjMemberLinkStore::find()->where(['user_id' => $user_id])->select('member_store_id')->column();
        $user_ids = ZyjMemberLinkStore::find()->where(['member_store_id' => $member_store_id])->andWhere(['!=','user_id',$user_id])->select('user_id')->column();
        return $user_ids??[];
    }

    /**
     * 找到会员对应的门店ID集合
     */
    public static function getStoreIdByMemberId($member_id): array
    {
        $store_ids = ZyjMemberLinkStore::find()->where(['member_id' => $member_id])->select('member_store_id')->column();
        return $store_ids??[];
    }


    /**
     * 根据用户ID获取权限内store_id
     */
    public static function getStoreIdByUserId(): array
    {
        $user_id = Yii::$app->user->identity->id ?? 0;
        loggingHelper::writeLog('diandi_auth', 'getStoreIdByUserId', '获取用户门店ID', [
            'user_id' => $user_id
        ]);
        $key = 'zyj_wash_store_ids_' . $user_id;
        if (empty($user_id)) {
            return [];
        }

        /**
         * 有缓存就直接反馈
         */
        $store_ids = Yii::$app->cache->get($key);
        if ($store_ids) {
            return $store_ids;
        }

        $store_ids = ZyjMemberLinkStore::find()->where(['user_id' => $user_id])->select('member_store_id')->column();

        $is_cloud  = AuthMemberListRole::find()->where(['user_id' => $user_id])->joinWith('role as r')->select('r.is_cloud')->scalar();
        loggingHelper::writeLog('diandi_auth', 'getStoreIdByUserId', '获取用户门店ID-是否读取云店数据', [
            'is_cloud' => $is_cloud,
            'store_ids' => $store_ids
        ]);
        if ($is_cloud === 1){
            $pid  = ZyjWashStore::find()->where(['store_id' => $store_ids])->select('id')->column();

            $store_ids_yds = ZyjWashStore::find()->where(['pid' => $pid])->select('store_id')->column();
            $store_ids = array_merge($store_ids, $store_ids_yds);
            loggingHelper::writeLog('diandi_auth', 'getStoreIdByUserId', '获取用户门店ID-读取云店数据', [
                'store_ids_yds' => $store_ids_yds,
                'store_ids' => $store_ids,
                'pid' => $pid
            ]);
        }
        Yii::$app->cache->set($key, $store_ids);
        return $store_ids;
    }
}