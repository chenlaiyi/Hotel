<?php

namespace common\modules\openWeixin\models\enums;

use common\components\BaseEnum;

/**
 * 注册企业小程序实时状态反馈
 */
class RegisterMiniprogramStatus extends BaseEnum
{
    const status1 = 100001;//	已下发的模板消息法人并未确认且已超时（24h），未进行身份证校验
    const status2 = 100002;//	已下发的模板消息法人并未确认且已超时（24h），未进行人脸识别校验
    const status3 = 100003;//	已下发的模板消息法人并未确认且已超时（24h）
    const status4 = 101;//	工商数据返回：“企业已注销”
    const status5 = 102;//	工商数据返回：“企业不存在或企业信息未更新”
    const status6 = 103;//	工商数据返回：“企业法定代表人姓名不一致”
    const status7 = 104;//	工商数据返回：“企业法定代表人身份证号码不一致”
    const status8 = 105;//	法定代表人身份证号码，工商数据未更新，请 5-15 个工作日之后尝试
    const status9 = 1000;//	工商数据返回：“企业信息或法定代表人信息不一致”
    const status10 = 1001;//	主体创建小程序数量达到上限
    const status11 = 1002;//	主体违规命中黑名单
    const status12 = 1003;//	管理员绑定账号数量达到上限
    const status13 = 1004;//	管理员违规命中黑名单
    const status14 = 1005;//	管理员手机绑定账号数量达到上限
    const status15 = 1006;//	管理员手机号违规命中黑名单
    const status16 = 1007;//	管理员身份证创建账号数量达到上限
    const status17 = 1008;//	管理员身份证违规命中黑名单
    const status18 = -1;//	企业与法人姓名不一致

    public static $messageCategory = 'App';

    protected static $list = [
        self::status1 => '已下发的模板消息法人并未确认且已超时（24h），未进行身份证校验',
        self::status2 => '已下发的模板消息法人并未确认且已超时（24h），未进行人脸识别校验',
        self::status3 => '已下发的模板消息法人并未确认且已超时（24h）',
        self::status4 => '工商数据返回：“企业已注销”',
        self::status5 => '工商数据返回：“企业不存在或企业信息未更新”',
        self::status6 => '工商数据返回：“企业法定代表人姓名不一致”',
        self::status7 => '工商数据返回：“企业法定代表人身份证号码不一致”',
        self::status8 => '法定代表人身份证号码，工商数据未更新，请 5-15 个工作日之后尝试',
        self::status9 => '工商数据返回：“企业信息或法定代表人信息不一致”',
        self::status10 => '主体创建小程序数量达到上限',
        self::status11 => '主体违规命中黑名单',
        self::status12 => '管理员绑定账号数量达到上限',
        self::status13 => '管理员违规命中黑名单',
        self::status14 => '管理员手机绑定账号数量达到上限',
        self::status15 => '管理员手机号违规命中黑名单',
        self::status16 => '管理员身份证创建账号数量达到上限',
        self::status17 => '管理员身份证违规命中黑名单',
        self::status18 => '企业与法人姓名不一致'
    ];
}