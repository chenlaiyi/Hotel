<?php

namespace admin\models\enums;

use common\components\BaseEnum;

/**
 * 用户资产类型
 */
class UserAccountEnum extends BaseEnum
{
    const level = 1;//	 	会员等级
    const user_money = 2;//	 		当前余额
    const accumulate_money = 3;// 	累计余额
    const give_money = 4;//	 	累计赠送余额
    const consume_money = 5;//	 	累计消费金额
    const frozen_money = 6;// 	冻结金额
    const user_integral = 7;// 当前积分
    const accumulate_integral = 8;// 	累计积分
    const give_integral = 9;// 	累计赠送积分
    const consume_integral = 10;// 	累计消费积分
    const frozen_integral = 11;// 冻结积分
    const credit1 = 12;//
    const credit2 = 13;//
    const credit3 = 14;//
    const credit4 = 15;//
    const credit5 = 16;//


    /**
     * @var string message category
     * You can set your own message category for translate the values in the $list property
     * Values in the $list property will be automatically translated in the function `listData()`
     */
    public static $messageCategory = 'App';

    /**
     * @var array
     */
    public static $list = [
        self::level => 	'会员等级',
        self::user_money => '当前余额',
        self::accumulate_money => '累计余额',
        self::give_money => '累计赠送余额',
        self::consume_money => 	'累计消费金额',
        self::frozen_money => '冻结金额',
        self::user_integral => '当前前积分',
        self::accumulate_integral => '累计积分',
        self::give_integral => 	'累计赠送积分',
        self::consume_integral => '累计消费积分',
        self::frozen_integral => '冻结积分',
        self::credit1 => '备用',
        self::credit2 => '备用',
        self::credit3 => '备用',
        self::credit4 => '备用',
        self::credit5 => '备用'
    ];
}