<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-07-09 14:52:10
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2020-07-09 16:40:41
 */

namespace common\services\common;

use common\models\DdMemberAccount;
use common\services\BaseService;

class AccountService extends BaseService
{
    
    public $member_id;//	用户id
    public $level;//	会员等级
    public $user_money;//	当前余额
    public $accumulate_money;//	累计余额
    public $give_money;//	累计赠送余额
    public $consume_money;//	累计消费金额
    public $frozen_money;//	冻结金额
    public $user_integral;//	当前积分
    public $accumulate_integral;//	累计积分
    public $give_integral;//	累计赠送积分
    public $consume_integral;//	累计消费积分
    public $frozen_integral;//	冻结积分
    public $status;//	状态[-1:删除;0:禁用;1启用]

    /**
     * 增加余额
     * @param $member_id
     * @param $user_money
     * @return int
     */
    public function upmoneyByuid($member_id,$user_money)
    {
        $res = DdMemberAccount::updateAllCounters([
                'user_money' => $user_money,
                'accumulate_money'=>$user_money,
            ], ['member_id' => $member_id]);
        
        return $res;    
    }

    /**
     * 冻结金额减少，可提现金额增加
     * @param $member_id
     * @param $frozen_money
     * @return int
     */
    public function upfrozenByuidReduce($member_id,$frozen_money)
    {
        $res = DdMemberAccount::updateAllCounters([
                'frozen_money' => -$frozen_money,
                'user_money'=>$frozen_money,
            ], ['member_id' => $member_id]);
        
        return $res;    
    }

    /**
     * 冻结金额增加
     */
    public function upfrozenByuidAdd($member_id,$frozen_money)
    {
        $res = DdMemberAccount::updateAllCounters([
                'frozen_money' => $frozen_money,
            ], ['member_id' => $member_id]);

        return $res;
    }

    /**
     * 余额减少
     */
    public function upmoneyByuidReduce($member_id,$user_money)
    {
        $res = DdMemberAccount::updateAllCounters([
                'user_money' => -$user_money,
            ], ['member_id' => $member_id]);

        return $res;
    }

    
}