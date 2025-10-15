<?php

namespace common\plugins\diandi_auth\admin\wechat;

use common\plugins\diandi_auth\models\MemberList;
use common\plugins\diandi_auth\models\MemberListRole;
use common\plugins\diandi_auth\models\MemberPermission;
use common\plugins\diandi_auth\models\MemberRole;
use common\plugins\diandi_auth\models\MemberRolePermission;
use common\plugins\diandi_auth\services\MemberService;
use common\models\User;
use api\controllers\AController;
use common\helpers\ErrorsHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use Exception;
use Yii;

class MemberController extends AController
{
    /**
     * 账号列表
     */
    public function actionList(): array
    {
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        $user_ids = MemberService::getMemberIdByShopId($user_id);

        $where = ['user_id' => $user_ids];
        $page = Yii::$app->request->input('page', 1);
        $pageSize = Yii::$app->request->input('pageSize', 20);
        $list = MemberService::getMemberList($where, $page, $pageSize);
        return ResultHelper::json(200, '获取成功', $list);
    }

    /**
     * 获取角色
     */
    public function actionRole(): array
    {
        $list = MemberRole::find()->asArray()->all();
        return ResultHelper::json(200, '获取成功', $list);
    }


    /**
     * 获取权限列表
     */

    function actionPermission(): array
    {
        /**
         * 获取所有的角色
         */
        $list = MemberRole::find()->asArray()->all();
        /**
         * 获取所有权限
         */
        $authList = MemberPermission::find()->select(['page_name', 'id', 'id as value', 'name', 'name as label', 'name as text', 'addons', 'pid'])->asArray()->all();
        /**
         * 获取角色对应的权限
         */
        $rps = MemberRolePermission::find()->select(['permissionId', 'roleId'])->joinWith('permission')->asArray()->all();
        $permission = [];
        foreach ($rps as $rp) {
            $permission[$rp['roleId']][] = $rp['permission'];
        }
        /**
         * 给每个角色下添加所有权限，并判断是否有权限
         */
        array_walk($list, function (&$item) use ($permission, $rps) {
            $item['permission'] = $permission[$item['id']] ?? [];
//            array_walk($item['permission'], function (&$permission) use ($rps, $item) {
//
//                $permission['checked'] = in_array($permission['id'], $rps[$item['id']] ?? []);
//            });
        });
        return ResultHelper::json(200, '获取成功', $list);
    }

    /**
     * 设置用户角色
     */
    function actionSetRole(): array
    {
        $accountId = Yii::$app->request->input('accountId');
        $MemberListRole = new MemberListRole();
        $roleId = Yii::$app->request->post('roleIds', []);
        /**
         *获取当前用户spec
         */
        $user_id = Yii::$app->user->identity->user_id ?? 0;

        $spec = MemberList::find()->where(['user_id' => $user_id])->select('spec')->scalar();
        $specs = $spec ? json_decode($spec, true) : [];
//        {
//                'text': '运营',
//            'value': 1
//          }, {
//            'text': '生产',
//            'value': 2
//          }, {
//            'text': '门店',
//            'value': 3
//          }
        /**
         *店长
         */

        /**
         * 工厂
         */

        /**
         * 删除当前账号所有的角色，重新配置
         */
        $MemberListRole->deleteAll(['accountId' => $accountId]);
        foreach ($roleId as $key => $item) {
            $_MemberListRole = clone $MemberListRole;
            $data = ['accountId' => $accountId, 'roleId' => $item];
            $_MemberListRole->setAttributes($data);
            $_MemberListRole->save();
            if (!in_array($item['spec'], $specs)) {
                unset($roleId[$key]);
            }
        }
        return ResultHelper::json(200, '设置成功');
    }

    /**
     * @throws \Throwable
     */
    function actionAdd(): array
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            //        name
            //        username
            //        password
            //        mobile
            //        spec
            $name = Yii::$app->request->post('name');
            $username = Yii::$app->request->post('username');
            $password = Yii::$app->request->post('password');
            $mobile = Yii::$app->request->post('mobile');
            $role = Yii::$app->request->post('role_id');
            $member_cloud = Yii::$app->request->post('cloud_id');
            $check_type = Yii::$app->request->post('check_type',0);
            $ribbon_type = Yii::$app->request->post('ribbon_type',0);
            $user_id = Yii::$app->user->identity->user_id ?? 0;
            $member = MemberList::find()->where(['user_id' => $user_id])->select(['member_store', 'member_product'])->asArray()->one();
            $member_store = $member['member_store'];//门店
            $model = new MemberList();
            $spec = 3;
            $data = [
                'name' => $name,
                'username' => $username,
                'password' => $password,
                'mobile' => $mobile,
                'check_type'=>$check_type,
                'ribbon_type'=>$ribbon_type,
                'member_cloud'=> $member_cloud,
                'status' => 1,
                'member_store' => $member_store,
                'spec' => $spec
            ];
            $model->load($data, '');
            if (!$model->save()) {
                $msg = ErrorsHelper::getModelError($model);
                throw new \Exception($msg);
            }
            $password = Yii::$app->request->input('password', '');



            $User = new User();
            $isHave = User::find()->where(['mobile' => $model->mobile])->one();
            if (!$isHave) {
                $email = $model->mobile . '@zyj.com';
                $userRes = $User->signup($model->name, $model->mobile, $email, $password, 1);
                loggingHelper::writeLog('diandi_auth', 'afterSave-user list', '会员注册', [
                    'userRes' => $userRes
                ]);

                if (!isset($userRes['access_token'])) {
                    /**
                     * 删除当前数据
                     */
                    $model->delete();
                    throw new Exception($userRes['message']);
                }

                $user_id = $userRes['user']['id'];
                $model->user_id = $user_id;
                $model->save();
            } else {
                throw new Exception('手机号已存在:' . $model->mobile);
            }
            MemberService::saveMemberRole($user_id, $role);

           


            $transaction->commit();
            return ResultHelper::json(200, '创建成功', $model->toArray());

        }catch (Exception $e){
            if ($transaction != null && $transaction->isActive){
                $transaction->rollBack();
            }
            return ResultHelper::json(400, $e->getMessage());
        }


    }

    function actionBindOpenid(): array
    {
        $openid = Yii::$app->request->post('openid');
        if (empty($openid)){
            return ResultHelper::json(400, 'openid不能为空');
        }
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        $Res = MemberService::bindOpenid($user_id, $openid);
        if ($Res){
            return ResultHelper::json(200, '绑定成功');
        }else{
            return ResultHelper::json(400, '绑定失败');
        }
    }
}