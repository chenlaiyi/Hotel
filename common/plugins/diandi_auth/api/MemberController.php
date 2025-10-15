<?php

namespace common\plugins\diandi_auth\api;

use common\plugins\diandi_auth\models\MemberList;
use common\plugins\diandi_auth\models\MemberListRole;
use common\plugins\diandi_auth\models\MemberPermission;
use common\plugins\diandi_auth\models\MemberRole;
use common\plugins\diandi_auth\models\MemberRolePermission;
use common\plugins\diandi_auth\models\searchs\ZyjMemberRole;
use common\plugins\diandi_auth\models\AuthMemberRoleMoney;
use common\plugins\diandi_auth\services\MemberService;
use addons\zyj_wash\models\enums\CheckGoodsEnum;
use addons\zyj_wash\models\enums\OrderLogisticsEnum;
use addons\zyj_wash\models\enums\washOrderStatusEnum;
use addons\zyj_wash\models\money\ZyjWashMoneySharepoolTemplateRole;
use api\controllers\AController;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use Yii;

class MemberController extends AController
{
    /**
     * 账号列表
     */
    public function actionList(): array
    {
        $member_id = Yii::$app->user->identity->member_id??0;
        $member = MemberList::find()->where(['member_id' => $member_id])->select(['member_store','member_product'])->asArray()->one();

        $member_store = $member['member_store'];
        $member_product = $member['member_product'];

        $where = ['or', ['member_store' => $member_store], ['member_product' => $member_product]];
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
     * 根据角色获取分润配置
     */
    public function actionRoleMoney(): array
    {
        $member_id = Yii::$app->user->identity->member_id??0;
        $roleId = Yii::$app->request->input('roleId',0);
        $member = MemberList::find()->where(['member_id' => $member_id])->select(['member_store','member_product'])->asArray()->one();

        $member_store = $member['member_store']; //当前门店
        $member_product = $member['member_product'];

        $templateType = Yii::$app->request->input('templateType');

        /**
         * 获取角色触发分润的规则
         */
        $roleMoneys = AuthMemberRoleMoney::find()->where(['role_id' => $roleId])->with(['template'=>function ($query) use ($templateType,$member_store,$member_product) {
             $query->where([
                'templateType' => $templateType,
                'member_store' => $member_store,
                'member_product' => $member_product
            ]);
        }])->asArray()->all();
        $types = [
            1 => '物流',
            2 => '订单状态',
            3 => '核销单状态'
        ];
        foreach ($roleMoneys as &$roleMoney) {
            switch ($roleMoney['change_end_type']) {
                case 1:
                    $roleMoney['change_end_label'] = OrderLogisticsEnum::getLabelByName($roleMoney['change_end_label']);
                    break;
                case 2:
//                    $list = washOrderStatusEnum::listOptions();
                    $roleMoney['change_end_label'] = washOrderStatusEnum::getLabelByName($roleMoney['change_end_label']);
                    break;
                case 3:
//                    $list = CheckGoodsEnum::listOptions();
                    $roleMoney['change_end_label'] = CheckGoodsEnum::getLabelByName($roleMoney['change_end_label']);
                    break;
            }
            $roleMoney['change_end_type'] = $types[$roleMoney['change_end_type']];
//                change_end_label: "CHECK_GOODS_YES"
//                change_end_type: 3
//                change_start_type
//                change_start_label: "WAIT_USE"
            switch ($roleMoney['change_start_type']){
                case 1:
                    $roleMoney['change_start_label'] = OrderLogisticsEnum::getLabelByName($roleMoney['change_start_label']);
                    break;
                case 2:
//                    $list = washOrderStatusEnum::listOptions();
                    $roleMoney['change_start_label'] = washOrderStatusEnum::getLabelByName($roleMoney['change_start_label']);
                    break;
                case 3:
//                    $list = CheckGoodsEnum::listOptions();
                    $roleMoney['change_start_label'] = CheckGoodsEnum::getLabelByName($roleMoney['change_start_label']);
                    break;
            }

            $roleMoney['change_start_type'] = $types[$roleMoney['change_start_type']];
            $roleMoney['money_type'] = $roleMoney['template']?$roleMoney['template']['money_type']:1;
            $roleMoney['shareMoney'] = $roleMoney['template']?$roleMoney['template']['shareMoney']:null;
            $roleMoney['role_money_id'] = $roleMoney['id'];
            $roleMoney['templateType'] =  $templateType;
        }


        return ResultHelper::json(200, '获取成功', $roleMoneys);
    }

    public function actionSetMoney(): array
    {
        $templateType = Yii::$app->request->post('templateType');
        $roles = Yii::$app->request->post('roles');
        $member_id = Yii::$app->user->identity->member_id??0;
        $member = MemberList::find()->where(['member_id' => $member_id])->select(['member_store','member_product'])->asArray()->one();
        $member_store = $member['member_store'];//门店
        $member_product = $member['member_product'];
        $ZyjWashMoneySharepoolTemplateRole = new ZyjWashMoneySharepoolTemplateRole();

        /**
         * 查询是否存在
         */
        ZyjWashMoneySharepoolTemplateRole::deleteAll(['templateType'=> $templateType,'member_store'=> $member_store,'member_product'=> $member_product]);
        foreach ($roles as $roleId => $role) {
            $_ZyjWashMoneySharepoolTemplateRole = clone $ZyjWashMoneySharepoolTemplateRole;
            $data =[
                'templateType'=> $templateType,
                'shareMoney' => $role['shareMoney'],
                'money_type'=> $role['money_type'],
                'role_id'=> $role['role_id'],
                'role_money_id'=>$role['role_money_id'],
                'member_store'=> $member_store,
                'member_product'=> $member_product
            ];
            $_ZyjWashMoneySharepoolTemplateRole->setAttributes($data);
            $_ZyjWashMoneySharepoolTemplateRole->save();
        }

        return ResultHelper::json(200, '设置成功');
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
        $authList = MemberPermission::find()->select(['page_name','id','id as value','name','name as label','name as text','addons','pid'])->asArray()->all();
        /**
         * 获取角色对应的权限
         */
        $rps = MemberRolePermission::find()->select(['permissionId','roleId'])->joinWith('permission')->asArray()->all();
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
        $roleId = Yii::$app->request->post('roleIds',[]);
        /**
         *获取当前用户spec
         */
        $member_id = Yii::$app->user->identity->member_id??0;
        $spec = MemberList::find()->where(['member_id' => $member_id])->select('spec')->scalar();
        $specs = $spec?json_decode($spec,true):[];
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
            if (!in_array($item['spec'], $specs)){
                unset($roleId[$key]);
            }
        }
        return ResultHelper::json(200, '设置成功');
    }

    function actionAdd(): array
    {
        //        name
        //        username
        //        password
        //        mobile
        //        spec
        $name = Yii::$app->request->post('name');
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        $mobile = Yii::$app->request->post('mobile');
        $role = Yii::$app->request->post('role',[]);
        $member_id = Yii::$app->user->identity->member_id??0;
        $member = MemberList::find()->where(['member_id' => $member_id])->select(['member_store','member_product'])->asArray()->one();
        $member_store = $member['member_store'];//门店
        $model = new MemberList();
        $spec = [3];
        $data = [
            'name' => $name,
            'username' => $username,
            'password' => $password,
            'mobile' => $mobile,
            'status' =>1,
            'member_store'=>$member_store,
            'role'=>$role,
            'spec' =>$spec
        ];
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());

        } else {

            $msg = ErrorsHelper::getModelError($model);

            return ResultHelper::json(400, $msg);

        }
        
    }
}