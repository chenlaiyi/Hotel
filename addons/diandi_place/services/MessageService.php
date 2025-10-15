<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-11 16:27:51
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-25 12:53:26
 */
namespace addons\diandi_place\services;
use addons\diandi_place\models\order\PlaceOrderList;
use common\helpers\loggingHelper;
use common\helpers\StringHelper;
use common\modules\wechat\models\DdWxappFans;
use common\services\BaseService;
use Yii;
class MessageService extends BaseService
{
    public static  $member = [
        [
            'name' => '赵经理',
            'touser' => 'oR52CwmuHNJInv4Z9BeUvBgSlCfY',
            'server' => '公共区域'
        ],
        [
            'name' => '范经理',
            'touser' => 'oR52CwteUagH4FBNKso9f6rl6YMw',
            'server' => '公共区域'
        ],
        [
            'name' => '刘经理', //刘经理
            'touser' => 'oR52CwuCtA_kRuuSwoOGRBg71goA',
            'server' => '客房'
        ],
        [
            'name' => '李经理', //郭如峰
            'touser' => 'oR52CwpvvfaulE4giwg1j7DzLxsc',
            'server' => '客房'
        ],
        [
            'name' => '张经理',
            'touser' => 'oR52Cwu3PFfjb4q56RbWHGoGAnkM',
            'server' => '安保'
        ],
        [
            'name' => '李经理',
            'touser' => 'oR52Cwn2VbHR04G3OKI0B0HFIiog',
            'server' => '安保'
        ],
        [
            'name' => '王经理',
            'touser' => 'oR52CwpR_p460DSowfWyZKMinmJ0',
            'server' => '公共区域'
        ],
    ];
    // NhyoTNo5EabCDUfSAM4eamzj9X8Oz1ivQYgfQIehbU4	验证结果通知
    public static function checkInMsg($res = 0)
    {
        // 赵总：
        // oR52CwmuHNJInv4Z9BeUvBgSlCfY
        // 红酒：
        // oR52CwpvvfaulE4giwg1j7DzLxsc
        // 宁静：
        // oR52Cwn2VbHR04G3OKI0B0HFIiog
        // 王春生
        // oR52CwpR_p460DSowfWyZKMinmJ0
        $app = Yii::$app->wechat->app;
        // {{first.DATA}}
        // 姓名：{{keyword1.DATA}}
        // 身份证号：{{keyword2.DATA}}
        // 性别：{{keyword3.DATA}}
        // 生日：{{keyword4.DATA}}
        // 地址：{{keyword5.DATA}}
        // {{remark.DATA}}
        foreach (self::$member as $key => $value) {
            $app->template_message->send([
                'touser' => $value['touser'],
                'template_id' => 'NhyoTNo5EabCDUfSAM4eamzj9X8Oz1ivQYgfQIehbU4',
                // 'url' => 'https://easywechat.org',
                // 'miniprogram' => [
                //     'appid' => 'xxxxxxx',
                //     'pagepath' => 'pages/xxx',
                // ],
                'data' => [
                    'first' => '实名核验' . ($res ? '成功' : '失败'),
                    'keyword1' => '核验人姓名',
                    'keyword2' => '6106********7458',
                    'keyword3' => '男',
                    'keyword4' => '1995-11-05',
                    'keyword5' => '北京市海淀区上地十街与上地西路交叉口东南100米',
                    'remark' => '感谢您的配合'
                ],
            ]);
        }
    }
    // dXX2Y7oJ546mx7GxFU1D3RyIaKxoWCZiOoALvL9-hwo	入住提醒
    public static function joinRoomMsg($order_id)
    {
        $order = PlaceOrderList::find()->where(['id' => $order_id])->with(['checkInMainPerson'])->asArray()->one();
        $username = StringHelper::msubstr($order['checkInMainPerson']['realname'], 0, 1);
        // 赵总：
        // oR52CwmuHNJInv4Z9BeUvBgSlCfY
        // 红酒：
        // oR52CwpvvfaulE4giwg1j7DzLxsc
        // 宁静：
        // oR52Cwn2VbHR04G3OKI0B0HFIiog
        // 王春生
        // oR52CwpR_p460DSowfWyZKMinmJ0
        $app = Yii::$app->wechat->app;
        // {first.DATA}}
        // 订单号：{{OrderID.DATA}}
        // 酒店名称：{{HotelName.DATA}}
        // 入住日期：{{CheckInDate.DATA}}
        // 离店日期：{{CheckOutDate.DATA}}
        // {{remark.DATA}}
        foreach (self::$member as $key => $value) {
            $app->template_message->send([
                'touser' => $value['touser'],
                'template_id' => 'dXX2Y7oJ546mx7GxFU1D3RyIaKxoWCZiOoALvL9-hwo',
                // 'url' => 'https://easywechat.org',
                // 'miniprogram' => [
                //     'appid' => 'xxxxxxx',
                //     'pagepath' => 'pages/xxx',
                // ],
                'data' => [
                    'first' => '请更新客客户居住爱好标记',
                    'OrderID' => $order['order_number'],
                    'HotelName' => '乘方智住酒店',
                    'CheckInDate' => date('Y-m-d', time()),
                    'CheckOutDate' =>  $order['end_time'],
                    'remark' => $username . '**客人已入住406房间，请做好' . $value['server'] . '服务'
                ],
            ]);
        }
    }
    // pnn0IRwNoW8BQgReGgpJ69PInsulDRCOoJ4wCwYLNuo	退房打扫卫生通知
    public static function sanitationMsg($order_id)
    {
        $order = PlaceOrderList::find()->where(['id' => $order_id])->with(['checkInMainPerson'])->asArray()->one();
        $username = StringHelper::msubstr($order['checkInMainPerson']['realname'], 0, 1);
        // 赵总：
        // oR52CwmuHNJInv4Z9BeUvBgSlCfY
        // 红酒：
        // oR52CwpvvfaulE4giwg1j7DzLxsc
        // 宁静：
        // oR52Cwn2VbHR04G3OKI0B0HFIiog
        // 王春生
        // oR52CwpR_p460DSowfWyZKMinmJ0
        $app = Yii::$app->wechat->app;
        // {{first.DATA}}
        // 通知时间：{{keyword1.DATA}}
        // 消息类型：{{keyword2.DATA}}
        // 房间号：{{keyword3.DATA}}
        // 房间地址：{{keyword4.DATA}}
        // {{remark.DATA}}
        foreach (self::$member as $key => $value) {
            if ($key === 1) {
                $first = $username . '**客人已退房,请打扫房间';
            } else {
                $first = $username . '**客人已退房';
            }
            $app->template_message->send([
                'touser' => $value['touser'],
                'template_id' => 'pnn0IRwNoW8BQgReGgpJ69PInsulDRCOoJ4wCwYLNuo',
                // 'url' => 'https://easywechat.org',
                // 'miniprogram' => [
                //     'appid' => 'xxxxxxx',
                //     'pagepath' => 'pages/xxx',
                // ],
                'data' => [
                    'first' => $first,
                    'keyword1' => date('Y-m-d H:i:s', time()),
                    'keyword2' =>  '客人退房关怀',
                    'keyword3' => '3层605房间',
                    'keyword4' => '北京市海淀区上地十街与上地西路交叉口东南100米',
                    'remark' => '请更新客户居住爱好标记',
                ],
            ]);
        }
    }
    // zN3qXrDdqhFQvxRUYWPJ1n3vVkvCYuU5jTfwzbxP-nI	房间续住提醒
    public static function orderTimeMsg($member_id)
    {
        // $member_id = Yii::$App->user->identity->member_id??0;
        $order = PlaceOrderList::find()->where(['member_id' => $member_id])->orderBy(['create_time' => SORT_DESC])->asArray()->one();
        $person_num = $order['person_num'];
        $username = $order['username'];
        $start_time = $order['start_time'];
        $end_time = $order['end_time'];
        $member_id = $order['member_id'];
        $openid = DdWxappFans::find()->where(['user_id' => $member_id])->select('openid')->scalar();
        // {{time1.DATA}}
        // 酒店名称
        // {{thing3.DATA}}
        // 离店时间
        // {{time5.DATA}}
        // 房间号
        // {{thing7.DATA}}
        $info = [
            'template_id' => 'WAVCA4TLVIpOkB56QiSRT5FTOcunzdBNJvT5mY2DdUo', // 所需下发的订阅模板id
            'touser' => $openid,     // 接收者（用户）的 openid
            //'page' => 'pages/reserve/reserve',       // 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
            'page' => 'order/order',
            'data' => [
                'thing3' => [
                    'value' => '乘方智住大酒店',
                ],
                'time1' => [
                    'value' => $end_time,
                ],
                'time5' => [
                    'value' => $end_time,
                ],
                'thing7' => [
                    'value' => '101房',
                ]
            ],
        ];
        loggingHelper::writeLog('diandi_place', 'orderTimeMsg', '续住订阅消息发送内容', [
            'info' => $info,
            'order' => $order
        ]);
        $miniProgram = Yii::$app->wechat->miniProgram;
        $res = $miniProgram->subscribe_message->send($info);
        loggingHelper::writeLog('diandi_place', 'orderTimeMsg', '续住订阅消息发送', [
            'res' => $res
        ]);
        return  $res;
    }
    /**
     * 离店消息
     * @return void
     * @date 2023-04-25
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function outMsg($order_id)
    {
        $order = PlaceOrderList::find()->where(['id' => $order_id])->with(['checkInMainPerson'])->asArray()->one();
        $username = StringHelper::msubstr($order['checkInMainPerson']['realname'], 0, 1);
        // 赵总：
        // oR52CwmuHNJInv4Z9BeUvBgSlCfY
        // 红酒：
        // oR52CwpvvfaulE4giwg1j7DzLxsc
        // 宁静：
        // oR52Cwn2VbHR04G3OKI0B0HFIiog
        // 王春生
        // oR52CwpR_p460DSowfWyZKMinmJ0
        $app = Yii::$app->wechat->app;
        // {{first.DATA}}
        // 入住房源：{{keyword1.DATA}}
        // 房客：{{keyword2.DATA}}
        // 入离时间：{{keyword3.DATA}}
        // 状态：{{keyword4.DATA}}
        // {{remark.DATA}}
        foreach (self::$member as $key => $value) {
            $app->template_message->send([
                'touser' => $value['touser'],
                'template_id' => '5KYPwjkMmocH1ZnYNQtd0VuRX0NyPMndYnuy32Iv0Uc',
                // 'url' => 'https://easywechat.org',
                // 'miniprogram' => [
                //     'appid' => 'xxxxxxx',
                //     'pagepath' => 'pages/xxx',
                // ],
                'data' => [
                    'first' =>   $username . '**客人已退房,请做好' . $value['server'] . '服务',
                    'keyword1' => '乘方智住酒店',
                    'keyword2' => $username . '先生',
                    'keyword3' => date('m-d H:i:s', time()),
                    'keyword4' => '离店关怀',
                    'remark' => '请更新客客户居住爱好标记',
                ],
            ]);
        }
    }
    public static function lockAuth($order_id)
    {
        $app = Yii::$app->wechat->app;
        $order = PlaceOrderList::find()->where(['id' => $order_id])->with(['checkInMainPerson'])->asArray()->one();
        $username = StringHelper::msubstr($order['checkInMainPerson']['realname'], 0, 1);
        // {{first.DATA}}
        // 入住房源：{{keyword1.DATA}}
        // 入住日期：{{keyword2.DATA}}
        // 登记状态：{{keyword3.DATA}}
        // {{remark.DATA}}
        foreach (self::$member as $key => $value) {
            $app->template_message->send([
                'touser' => $value['touser'],
                'template_id' => '57YYv7HW0bmG1UNuflwq6IHj_NS_YeOwtJN1eo0yXIA',
                'data' => [
                    'first' =>  $username . '**客人,已授权无感体验智住酒店',
                    'keyword1' => '乘方智住酒店',
                    'keyword2' => date('Y-m-d', time()),
                    'keyword3' => '人脸进入已授权',
                    'remark' => '请做好客户关怀',
                ],
            ]);
        }
    }
    // 小程序消息下发开始
    //下单通知
    public static function OrderNotice($order_id)
    {
        $order = PlaceOrderList::find()->where(['id' => $order_id])->asArray()->one();
        $person_num = $order['person_num'];
        $username = $order['username'];
        $start_time = $order['start_time'];
        $end_time = $order['end_time'];
        $member_id = $order['member_id'];
        $openid = DdWxappFans::find()->where(['user_id' => $member_id])->select('openid')->scalar();
        // 酒店名称
        // {{thing1.DATA}}
        // 到店日期
        // {{date5.DATA}}
        // 离店日期
        // {{date6.DATA}}
        // 房间数量
        // {{number7.DATA}}
        // 预订结果
        // {{phrase2.DATA}}
        $info = [
            'template_id' => 'UPkgPVJysSDEhfi_yppZXxd3ZuG-4OX_Ldl06rrAI5M', // 所需下发的订阅模板id
            'touser' => $openid,     // 接收者（用户）的 openid
            //'page' => 'pages/reserve/reserve',       // 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
            'page' => 'order/order',
            'data' => [
                'thing1' => [
                    'value' => '乘方智住大酒店',
                ],
                'date5' => [
                    'value' => $start_time,
                ],
                'date6' => [
                    'value' => $end_time,
                ],
                'number7' => [
                    'value' => $person_num ? (int) $person_num : 1,
                ],
                'phrase2' => [
                    'value' => '预订成功',
                ]
            ],
        ];
        $miniProgram = Yii::$app->wechat->miniProgram;
        $res = $miniProgram->subscribe_message->send($info);
        loggingHelper::writeLog('diandi_place', 'MessageService', '订阅消息发送', [
            'info' => $info,
            'res' => $res,
            'order_id' => $order_id,
        ]);
        return  $res;
    }
    public static function invoiceMsg($order_id)
    {
        $order = PlaceOrderList::find()->where(['id' => $order_id])->asArray()->one();
        $person_num = $order['person_num'];
        $username = $order['username'];
        $start_time = $order['start_time'];
        $end_time = $order['end_time'];
        $member_id = $order['member_id'];
        $openid = DdWxappFans::find()->where(['user_id' => $member_id])->select('openid')->scalar();
        // 酒店名称
        // 酒店名称
        // {{thing4.DATA}}
        // 发票金额
        // {{amount5.DATA}}
        // 备注
        // {{thing6.DATA}}
        // 预订单号
        // {{character_string2.DATA}}
        $info = [
            'template_id' => 'UMs_Ev98rpBEJeR1edcM23JJJxXX_efXfK7yRw0ufnU', // 所需下发的订阅模板id
            'touser' => $openid,     // 接收者（用户）的 openid
            //'page' => 'pages/reserve/reserve',       // 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
            'page' => 'order/order',
            'data' => [
                'thing4' => [
                    'value' => '乘方智住大酒店',
                ],
                'amount5' => [
                    'value' => 2090,
                ],
                'character_string2' => [
                    'value' => $order['order_number'],
                ],
                'thing6' => [
                    'value' => '祝您旅途愉快！期待再次为您服务。',
                ]
            ],
        ];
        $miniProgram = Yii::$app->wechat->miniProgram;
        $res = $miniProgram->subscribe_message->send($info);
        loggingHelper::writeLog('diandi_place', 'invoiceMsg', '订阅消息发送', [
            'info' => $info,
            'res' => $res,
            'order_id' => $order_id,
        ]);
        return  $res;
    }
    public static function checkInMember($order_id)
    {
        $order = PlaceOrderList::find()->where(['id' => $order_id])->asArray()->one();
        $person_num = $order['person_num'];
        $username = $order['username'];
        $start_time = $order['start_time'];
        $end_time = $order['end_time'];
        $member_id = $order['member_id'];
        $openid = DdWxappFans::find()->where(['user_id' => $member_id])->select('openid')->scalar();
        // 酒店名称
        // {{thing1.DATA}}
        // 房间号
        // {{thing6.DATA}}
        // 入住时间
        // {{time2.DATA}}
        // 预离时间
        // {{time5.DATA}}
        // 备注
        // {{thing8.DATA}}
        $info = [
            'template_id' => '3GzTqqZHF2myVqzJUuNpV9WFna5zgxWduQSK9JZJTlQ', // 所需下发的订阅模板id
            'touser' => $openid,     // 接收者（用户）的 openid
            //'page' => 'pages/reserve/reserve',       // 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
            'page' => 'order/order',
            'data' => [
                'thing1' => [
                    'value' => '乘方智住大酒店',
                ],
                'thing6' => [
                    'value' => '205房间',
                ],
                'time2' => [
                    'value' => $order['start_time'],
                ],
                'time5' => [
                    'value' =>  $order['end_time'],
                ],
                'thing8' => [
                    'value' => '欢迎李先生入住乘方智住酒店',
                ]
            ],
        ];
        $miniProgram = Yii::$app->wechat->miniProgram;
        loggingHelper::writeLog('diandi_place', 'invoiceMsg', '入住消息发送', [
            'miniProgram' => $miniProgram
        ]);
        $res = $miniProgram->subscribe_message->send($info);
        loggingHelper::writeLog('diandi_place', 'invoiceMsg', '入住消息发送', [
            'info' => $info,
            'res' => $res,
            'order_id' => $order_id,
        ]);
        return  $res;
    }
    public static function messageToBloc():array
    {
        return [];
    }
}
