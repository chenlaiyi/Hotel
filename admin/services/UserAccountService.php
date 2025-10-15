<?php

namespace admin\services;

use common\models\User;
use admin\models\UserAccount;
use admin\models\UserAccountLog;
use common\helpers\loggingHelper;
use common\models\DdMemberAccount;
use common\modules\officialaccount\services\OfficialaccountService;
use common\services\BaseService;
use yii\db\StaleObjectException;

/**
 * 管理员财务
 */
class UserAccountService extends BaseService
{
    /**
     * 绑定用户openid
     * @param $user_id
     * @param $openid
     * @return void
     * @throws \Throwable
     * @throws StaleObjectException
     */
    static function bingOpenid($user_id,$openid): void
    {
        loggingHelper::writeLog('UserAccountService','bingOpenid','绑定用户微信数据',[
            'openid'=>$openid,
            'user_id'=>$user_id,
            'data' =>\Yii::$app->request->input()
        ]);
        if ($openid){
            $user = OfficialaccountService::getWechatApp(1)->user->get($openid);
            $userObj = User::findOne($user_id);
            loggingHelper::writeLog('UserAccountService','bingOpenid','绑定用户微信数据',[
                'userObj'=>$userObj,
                'user'=>$user,
            ]);
            $userObj->open_id = $user['openid'];
            $userObj->union_id = $user['unionid'];
            $userObj->update(false);
        }
    }

    /**
     * 初始化用户资产数据
     * @param $user_id
     * @return void
     */
    static function initAccount($user_id): void
    {
        $UserAccount = new UserAccount();
        $have = $UserAccount->find()->where(['user_id'=>$user_id])->exists();
        if(!$have){
            $data = [
                'user_id' =>$user_id,
                'status' =>0,
                'level' =>1,
                'user_money' =>0,
                'accumulate_money' =>0,
                'give_money' =>0,
                'consume_money' =>0,
                'frozen_money' =>0,
                'consume_integral' =>0,
                'credit1' =>0,
                'credit2' =>0,
                'credit3' =>0,
                'credit4' =>0,
                'credit5' =>0,
            ];
            $UserAccount->load($data,'') &&  $UserAccount->save();
        }
    }

    /**
     * 更新用户资产数据
     * @param $user_id
     * @param $fields
     * @param $num
     * @param $remark
     * @return bool|int
     */
    public static function updateAccount($user_id, $fields, $num, $remark = ''): bool|int
    {
        loggingHelper::writeLog('UserAccountService','updateAccount','更新用户财务数据',[
            'user_id' => $user_id,
            'fields' => $fields,
            'num' => $num,
            'remark' => $remark
        ]);


        $old_money = UserAccount::find()->where(['user_id' => $user_id])->select($fields)->scalar();

        $Res = DdMemberAccount::updateAllCounters([
            $fields => $num
        ], ['user_id' => $user_id]);

        if ($Res) {

            self::addAccountLog($user_id, $fields, $num, $old_money, $remark);

            return $Res;
        } else {
            return false;
        }
    }

    /**
     * 添加用户资产日志
     * @param $user_id
     * @param $fields
     * @param $money
     * @param $old_money
     * @param $money_id
     * @param $remark
     * @return bool
     */
    public static  function addAccountLog($user_id, $fields, $money, $old_money, $money_id = 0, $remark = ''): bool
    {
        $accountLog = [
            'user_id'     => $user_id,
            'account_type' => $fields,
            'money' => $money,
            'money_id' => $money_id,
            'old_money' => $old_money,
            'is_add' => $money > 0 ? 1 : 0,
            'remark' => $remark,

        ];

        $AccountLog = new UserAccountLog();
        $AccountLog->load($accountLog, '');
        return $AccountLog->save();
    }
}