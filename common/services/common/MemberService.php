<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-07-09 14:52:10
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-07-18 17:15:20
 */

namespace common\services\common;

use admin\models\DdApiAccessToken as ModelsDdApiAccessToken;
use api\models\DdApiAccessToken;
use common\helpers\FileHelper;
use common\helpers\ImageHelper;
use common\models\AccountLog;
use common\models\DdMember;
use common\models\DdMemberAccount;
use common\services\BaseService;
use Yii;
use yii\data\Pagination;

class MemberService extends BaseService
{
    /**
     * 获取用户基础信息，默认为当前商户，is_global为true时获取集团用户
     */
    public  function baseInfo($member_id,$is_global = false)
    {
        $logPath = Yii::getAlias('@api/runtime/MemberService/baseInfo/' . date('Y/md') . '.log');



        $list =  DdMember::find()->with(['account', 'group', 'fans'])->where([
            'member_id' => $member_id
        ])->asArray()->one();

        if (!empty($list)) {
            $list['avatarUrl'] = ImageHelper::tomedia($list['avatarUrl'], 'avatar.jpg');
            $list['avatar'] =     ImageHelper::tomedia($list['avatar'], 'avatar.jpg');
        }


        FileHelper::writeLog($logPath, '获取用户基础信息sql:' . DdMember::find()->with(['account', 'group', 'fans'])->where([
            'member_id' => $member_id
        ])->createCommand()->getRawSql());
        FileHelper::writeLog($logPath, '获取用户基础信息' . json_encode($list));

        if (empty($list['account']) && !empty($list)) {
            $account = [
                "member_id" => $member_id,
                "level" => 0,
                "user_money" => 0,
                "accumulate_money" => 0,
                "give_money" => 0,
                "consume_money" => 0,
                "frozen_money" => 0,
                "user_integral" => 0,
                "accumulate_integral" => 0,
                "give_integral" => 0,
                "consume_integral" => 0,
                "frozen_integral" => 0,
                "credit1" => 0,
                "credit2" => 0,
                "credit3" => 0,
                "credit4" => 0,
                "credit5" => 0,
            ];

            $DdMemberAccount = new DdMemberAccount();
            $DdMemberAccount->load($account, '');
            if ($DdMemberAccount->save()) {
                $list['account'] = $account;
            }
        }
        return $list;
    }

    // 修改用户基础信息
    public static function editInfo($member_id, $fields = [])
    {
        $DdMember = new DdMember();
        $res = $DdMember->updateAll($fields, ['member_id' => $member_id]);
        return $res;
    }

    // 获取所有的会员信息
    public static function memberLists($where, $memberAlias, $joinModel, $joinfiled, $fields = [], $page = 1, $pageSize = 20)
    {
        $selectFs = [];
        foreach ($fields as $key => $value) {
            $selectFs[] = '`u`.' . $value;
        }
        $memberTablename = DdMember::tableName();
        $joinTablename   = $joinModel::tableName();
        $query = DdMember::find()->where($where)
            ->alias($memberAlias)
            ->with(['account', 'group', 'fans'])
            ->leftJoin($joinTablename . ' AS u', 'u.' . $joinfiled . ' = ' . $memberAlias . '.member_id')
            ->select([$memberAlias . '.*', implode(',', $selectFs)]);

        $count = $query->count();

        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => $pageSize,
            'page' => $page - 1,
        ]);

        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        foreach ($list as $key => $value) {
            $list[$key]['status_str'] = $value['status'] == 0 ? '正常' : '拉黑';
            $list[$key]['create_time'] = date('Y-m-d H:i', $value['create_time']);
        }
        return ['count' => $count, 'list' => $list];
    }

    public function updateAccount($member_id, $fields, $num, $is_global = false)
    {

        $old_money = DdMemberAccount::find()->where(['member_id' => $member_id])->select($fields)->scalar();

        $Res = DdMemberAccount::updateAllCounters([
            $fields => $num
        ], ['member_id' => $member_id]);

        if ($Res) {

            $this->addAccountLog($member_id, $fields, $num, $old_money);

            return DdMember::find()->with(['account', 'group', 'fans'])->where([
                'member_id' => $member_id
            ])->asArray()->one();
        } else {
            return false;
        }
    }

    public  function addAccountLog($member_id, $fields, $money, $old_money, $money_id = 0, $remark = '')
    {
        $accountLog = [
            'member_id'     => $member_id,
            'account_type' => $fields,
            'money' => $money,
            'money_id' => $money_id,
            'old_money' => $old_money,
            'is_add' => $money > 0 ? 1 : 0,
            'remark' => '',

        ];

        $AccountLog = new AccountLog();
        $AccountLog->load($accountLog, '');
        $Res = $AccountLog->save();
        return $Res;
    }
}
