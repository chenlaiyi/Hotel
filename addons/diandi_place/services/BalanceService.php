<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-03-22 11:34:09
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-05-30 09:13:27
 */
namespace addons\diandi_place\services;
use addons\diandi_place\models\marketing\PlaceCoupon;
use addons\diandi_place\models\marketing\PlaceCouponList;
use addons\diandi_place\models\marketing\PlaceCouponOrder;
use addons\diandi_place\models\member\PlaceMemberCoupon;
use addons\diandi_place\models\order\PlaceOrderList;
use addons\diandi_place\models\room\PlaceRoom;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\helpers\StringHelper;
use common\models\AccountLog;
use common\models\DdMemberAccount;
use common\modules\wechat\models\DdWxappFans;
use common\services\BaseService;
use Yii;
class BalanceService extends BaseService
{
    //余额支付订单
    public static function orderBalancePay($member_id, $order_number, $real_pay)
    {
        loggingHelper::writeLog('diandi_tea', 'BalancePay-time', '耗时统计开始', date('m-d H:i:s'));
        $order = PlaceOrderList::find()->where(['order_number' => $order_number, 'real_pay' => $real_pay])->asArray()->one();
        if (empty($order['id'])) {
            return ResultHelper::json(401, '订单不存在');
        }
        loggingHelper::writeLog('diandi_tea', 'BalancePay', '包间下单余额支付订单', $order_number);
        $GorderType = StringHelper::msubstr($order_number, 0, 1);
        loggingHelper::writeLog('diandi_tea', 'BalancePay', '订单类型', $GorderType);
        if ($GorderType == 'H') {
            loggingHelper::writeLog('diandi_tea', 'BalancePay-time', '预订支付类型的订单', date('m-d H:i:s'));
            //loggingHelper::writeLog('diandi_tea', 'Notify', '包间下单回调', $GorderType);
            // 包间支付订单 H2020121499549755
            //$orderInfo = PlaceOrderList::find()->where(['order_number' => $params['out_trade_no']])->asArray()->one();
            loggingHelper::writeLog('diandi_tea', 'BalancePay', '包间订单余额支付详情sql', PlaceOrderList::find()->where(['order_number' => $order_number])->createCommand()->getRawSql());
            loggingHelper::writeLog('diandi_tea', 'BalancePay', '包间订单余额支付详情', $order);
            if ($order['status'] > 1 && $order['status'] != 4) {
                return ResultHelper::json(401, '订单已支付');
            }
            loggingHelper::writeLog('diandi_tea', 'BalancePay-time', '事务开始', date('m-d H:i:s'));
            //$transaction = DistributionAccountStorePay::getDb()->beginTransaction();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $balance = DdMemberAccount::find()->where(['member_id' => $order['member_id']])->select('user_money')->asArray()->one()['user_money'];
                if ($balance < $real_pay) {
                    return ResultHelper::json(401, '余额不足');
                } else {
                    //扣用户余额
                    DdMemberAccount::updateAllCounters(['user_money' => -$real_pay], ['member_id' => $member_id]);
                }
                loggingHelper::writeLog('diandi_tea', 'BalancePay-time', '余额校验完成', date('m-d H:i:s'));
                loggingHelper::writeLog('diandi_tea', 'BalancePay', '包间订单处理', [
                    'order_id' => $order['id'],
                    'status' => 2,
                    'pay_time' => date('Y-m-d H:i:s'),
                    'balance' => $balance - $real_pay,
                ]);
                //房间密码
                $x = 100000;
                $y = 999999;
                $pwd = rand($x, $y);
                //更新订单状态
                $Res = PlaceOrderList::updateAll([
                    'status' => 2,
                    'pwd' => $pwd,
                    'pay_time' => date('Y-m-d H:i:s'),
                    'balance' => $balance - $real_pay,
                    'pay_type' => 2,
                ], ['id' => $order['id']]);
                loggingHelper::writeLog('diandi_tea', 'BalancePay', '包间订单处理', $Res);
                loggingHelper::writeLog('diandi_tea', 'BalancePay-time', '更新订单状态完成', date('m-d H:i:s'));
                //记录套餐消费记录
                $set_meal = [
                    'title' => $order['set_meal_name'],
                    'price' => $order['amount_payable'],
                    'renew_price' => $order['renew_price'],
                    'order_id' => $order['id'],
                    'set_meal_id' => $order['set_meal_id'],
                    'member_id' => $order['member_id'],
                ];
                loggingHelper::writeLog('diandi_tea', 'BalancePay', '记录套餐消费记录', $set_meal);
                //下房间开锁订单
                // 派遣器
                // $dispatcher = new DdDispatcher();
                // // 监听器
                // $listener = new DdListener();
                // $subscriber = new LockopenServer();
                // $dispatcher->addSubscriber($subscriber);
                $member_id = $order['member_id'];
                $password = $pwd;
                $ext_room_id = $order['hourse_id'];
                $start_time = $order['start_time'];
                $end_time = $order['end_time'];
                $ext_order_id = $order['id'];
                loggingHelper::writeLog('diandi_tea', 'BalancePay-time', '创建开锁订单', [
                    'time' => date('m-d H:i:s'),
                    'member_id' => $member_id,
                    'password' => $password,
                    'ext_room_id' => $ext_room_id,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'ext_order_id' => $ext_order_id
                ]);
                // $diandiLockSdk = new diandiLockSdk();
                // $diandiLockSdk->createLockOrder($ext_order_id, $member_id, $password, $ext_room_id, $start_time, $end_time);
                loggingHelper::writeLog('diandi_tea', 'Notify', '房间开锁下单', [$ext_order_id, $member_id, $password, $ext_room_id, $start_time, $end_time]);
                loggingHelper::writeLog('diandi_tea', 'BalancePay-time', '创建开锁订单完成', date('m-d H:i:s'));
                //记录卡券使用记录及增加卡券已使用数量
                if ($order['coupon_id']) {
                    $coupon_model = PlaceCoupon::find()
                        ->select(['name', 'type', 'price'])
                        ->where(['id' => $order['coupon_id']])
                        ->asArray()
                        ->one();
                    $coupon_list = [
                        'member_id' => $order['member_id'],
                        'coupon_id' => $order['coupon_id'],
                        'coupon_name' => $coupon_model['name'],
                        'coupon_type' => $coupon_model['type'],
                        'order_id' => $order['id'],
                        'price' => $coupon_model['price'],
                    ];
                    //记录卡券使用记录
                    $set_coupon_list_model = new PlaceCouponList();
                    $set_coupon_list_model->load($coupon_list, '');
                    $set_coupon_list_model->save();
                    //增加卡券已使用数量
                    PlaceCoupon::updateAllCounters(['use_num' => 1], ['id' => $order['coupon_id']]);
                }
                loggingHelper::writeLog('diandi_tea', 'BalancePay-time', '记录卡券使用记录及增加卡券已使用数量', date('m-d H:i:s'));
                //添加系统用户消费记录
                $accountLoginfo = [
                    'member_id' => $order['member_id'],
                    'account_type' => 'tea_member_money_balance',
                    'old_money' => $balance,
                    'money' => -$order['real_pay'],
                    'is_add' => 1,
                    'money_id' => $order['id'],
                    'remark' => '下单余额消费',
                ];
                loggingHelper::writeLog('diandi_tea', 'BalancePay', '更新系统用户余额变更记录', $accountLoginfo);
                $AccountLog = new AccountLog();
                $AccountLog->load($accountLoginfo, '');
                $AccountLog->save();
                loggingHelper::writeLog('diandi_tea', 'BalancePay-time', '添加系统用户消费记录', date('m-d H:i:s'));
                $openid = DdWxappFans::find()->where(['user_id' => $member_id])->select('openid')->scalar();
                loggingHelper::writeLog('diandi_tea', 'BalancePay-time', '小程序下单通知开始', date('m-d H:i:s'));
                //小程序下单通知
                $hourse_name = PlaceRoom::find()->where(['id' => $order['hourse_id']])->select('name')->scalar();
                //小程序预约到期前10分钟通知
                $dif = (strtotime($order['start_time']) - 600) - time();
                if ($dif > 0) {
                    $notice_time = $dif;
                } else {
                    $notice_time = 10;
                }
                loggingHelper::writeLog('diandi_tea', 'notice_time', '倒计时', $notice_time);
                $url = Yii::$app->request->hostInfo . '/api/diandi_tea/notice/orderobs?bloc_id=30&store_id=79';
                loggingHelper::writeLog('diandi_tea', 'notice_time', 'url地址', $url);
                loggingHelper::writeLog('diandi_tea', 'BalancePay-time', '小程序下单通知完成', date('m-d H:i:s'));
                //小程序订单到期前10分钟续费通知
                $dif_renew = (strtotime($order['end_time']) - 600) - time();
                if ($dif_renew > 0) {
                    $notice_time_renew = $dif_renew;
                } else {
                    $notice_time_renew = 1;
                }
                $url_renew = Yii::$app->request->hostInfo . '/api/diandi_tea/notice/renewobs?bloc_id=30&store_id=79';
                loggingHelper::writeLog('diandi_tea', 'renew_time', 'url地址', $url_renew);
                loggingHelper::writeLog('diandi_tea', 'BalancePay-time', '续费通知处理完成', date('m-d H:i:s'));
                // 增加关灯任务
                $switch_dif_renew = (strtotime($order['end_time'])) - time();
                if ($switch_dif_renew > 0) {
                    $switch_time_renew = $switch_dif_renew;
                } else {
                    $switch_time_renew = 1;
                }
                loggingHelper::writeLog('diandi_tea', 'BalancePay-time', '增加开灯任务开始', date('m-d H:i:s'));
                $ext_event_id = $order['id'];
                loggingHelper::writeLog('diandi_tea', 'BalancePay-time', '增加开灯任务完成', date('m-d H:i:s'));
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                loggingHelper::writeLog('diandi_tea', 'BalancePay', '包间订单处理,错误信息Exception', $e);
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                loggingHelper::writeLog('diandi_tea', 'BalancePay', '包间订单处理,错误信息Throwable', $e);
                throw $e;
            }
            return ResultHelper::json(200, '余额支付成功');
        } elseif ($GorderType == 'X') {
            //包间续费订单回调
            loggingHelper::writeLog('diandi_tea', 'BalancePay', '包间续费余额支付', $GorderType);
            // 包间续费订单 X2020121499549755
            // $orderInfo = PlaceOrderList::find()->where(['order_number' => $order['out_trade_no']])->asArray()->one();
            loggingHelper::writeLog('diandi_tea', 'BalancePay', '包间续费详情sql', PlaceOrderList::find()->where(['order_number' => $order_number])->createCommand()->getRawSql());
            loggingHelper::writeLog('diandi_tea', 'BalancePay', '包间续费详情', $order);
            if ($order['status'] > 1 && $order['status'] != 4) {
                return ResultHelper::json(401, '订单已支付');
            }
            //$transaction = DistributionAccountStorePay::getDb()->beginTransaction();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $balance = DdMemberAccount::find()
                    ->where(['member_id' => $order['member_id']])->select('user_money')->asArray()->one()['user_money'];
                if ($balance < $real_pay) {
                    return ResultHelper::json(401, '余额不足');
                } else {
                    //扣用户余额
                    DdMemberAccount::updateAllCounters(['user_money' => -$real_pay], ['member_id' => $member_id]);
                }
                loggingHelper::writeLog('diandi_tea', 'BalancePay', '包间续费处理', [
                    'order_id' => $order['id'],
                    'status' => 2,
                    'pay_time' => date('Y-m-d H:i:s'),
                    'balance' => $balance - $real_pay,
                ]);
                //更新续费订单状态
                $Res = PlaceOrderList::updateAll([
                    'pay_type' => 2,
                    'status' => 2,
                    'balance' => $balance - $real_pay,
                    'pay_time' => date('Y-m-d H:i:s'),
                ], ['id' => $order['id']]);
                loggingHelper::writeLog('diandi_tea', 'BalancePay', '包间续费处理', $Res);
                //房间开锁加时订单
                // 派遣器
                // $dispatcher = new DdDispatcher();
                // // 监听器
                // $listener = new DdListener();
                // $subscriber = new LockopenServer();
                // $dispatcher->addSubscriber($subscriber);
                $member_id = $order['member_id'];
                $password = '';
                $ext_room_id = $order['hourse_id'];
                $start_time = $order['start_time'];
                $end_time = $order['end_time'];
                $ext_order_id = $order['renew_order_id'];
                // $event = new LockOrderEvent($ext_order_id, $member_id, $password, $ext_room_id, $start_time, $end_time);
                // $dispatcher->dispatch(LockOrderEvent::EVENT_LOCK_ORDER, $event);
                //添加系统用户消费记录
                $accountLoginfo = [
                    'member_id' => $order['member_id'],
                    'account_type' => 'tea_member_renew_money_balance',
                    'old_money' => $balance,
                    'money' => -$order['real_pay'],
                    'is_add' => 1,
                    'money_id' => $order['id'],
                    'remark' => '续费余额消费',
                ];
                $AccountLog = new AccountLog();
                loggingHelper::writeLog('diandi_tea', 'BalancePay', '添加系统用户余额变更记录', $accountLoginfo);
                $AccountLog->load($accountLoginfo, '');
                $AccountLog->save();
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                loggingHelper::writeLog('diandi_tea', 'BalancePay', '包间续费订单处理,错误信息Exception', $e);
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                loggingHelper::writeLog('diandi_tea', 'BalancePay', '包间续费订单处理,错误信息Throwable', $e);
                throw $e;
            }
            return ResultHelper::json(200, '余额支付成功');
        }
    }
    public static function couponBalancePay($member_id, $price, $order_number)
    {
        //卡券购买余额支付
        loggingHelper::writeLog('diandi_tea', 'BalanceBuyCoupon', '卡券余额购买', 'K');
        // 卡券购买订单 K2020121499549755
        $orderInfo = PlaceCouponOrder::find()->where(['order_number' => $order_number, 'price' => $price])->asArray()->one();
        if (empty($orderInfo['id'])) {
            return ResultHelper::json(401, '订单不存在');
        }
        loggingHelper::writeLog('diandi_tea', 'BalanceBuyCoupon', '卡券购买详情sql', PlaceCouponOrder::find()->where(['order_number' => $order_number])->createCommand()->getRawSql());
        loggingHelper::writeLog('diandi_tea', 'BalanceBuyCoupon', '卡券购买详情', $orderInfo);
        if ($orderInfo['status'] != 1) {
            return ResultHelper::json(401, '订单已支付');
        }
        //$transaction = DistributionAccountStorePay::getDb()->beginTransaction();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $balance = DdMemberAccount::find()->where(['member_id' => $member_id])->select('user_money')->asArray()->one()['user_money'];
            if ($balance < $price) {
                return ResultHelper::json(401, '余额不足');
            } else {
                //扣用户余额
                DdMemberAccount::updateAllCounters(['user_money' => -$price], ['member_id' => $member_id]);
            }
            loggingHelper::writeLog('diandi_tea', 'BalanceBuyCoupon', '卡券购买处理', [
                'order_id' => $orderInfo['id'],
                'status' => 2,
                'pay_time' => date('Y-m-d H:i:s'),
                'balance' => $balance - $price,
            ]);
            //更新卡券购买订单状态
            $Res = PlaceCouponOrder::updateAll([
                'status' => 2,
                'pay_type' => 2,
                'balance' => $balance - $price,
                'pay_time' => date('Y-m-d H:i:s'),
            ], ['id' => $orderInfo['id']]);
            loggingHelper::writeLog('diandi_tea', 'BalanceBuyCoupon', '卡券购买处理', $Res);
            //增加已发售卡券数量
            PlaceCoupon::updateAllCounters(['all_num' => 1], ['id' => $orderInfo['coupon_id']]);
            //增加用户卡券数量
            $is_have = PlaceMemberCoupon::find()->select('id')
                ->where(['member_id' => $orderInfo['member_id'], 'coupon_id' => $orderInfo['coupon_id']])
                ->asArray()->one();
            if ($is_have['id']) {
                //增加剩余使用次数
                loggingHelper::writeLog('diandi_tea', 'BalanceBuyCoupon', '增加用户卡券可用次数', $is_have['id']);
                PlaceMemberCoupon::updateAllCounters(['surplus_num' => 1], ['id' => $is_have['id']]);
            } else {
                //创建用户卡券
                $data['member_id'] = $orderInfo['member_id'];
                $data['coupon_name'] = $orderInfo['coupon_name']; //卡券名称
                $data['coupon_id'] = $orderInfo['coupon_id']; //卡券id
                $data['coupon_type'] = $orderInfo['coupon_type']; //卡券类型
                $data['surplus_num'] = 1; //剩余数量
                $data['receive_type'] = 2; //卡券获取方式： 1.领取 2.购买
                $MemberCouponModel = new PlaceMemberCoupon();
                loggingHelper::writeLog('diandi_tea', 'BalanceBuyCoupon', '创建用户卡券', $data);
                $MemberCouponModel->load($data, '');
                $MemberCouponModel->save();
            }
            //添加系统用户卡券购买消费记录
            $accountLoginfo = [
                'member_id' => $orderInfo['member_id'],
                'account_type' => 'tea_buy_coupon_money_balance',
                'money' => -$orderInfo['price'],
                'old_money' => $balance,
                'is_add' => 1,
                'money_id' => $orderInfo['id'],
                'remark' => '会员购买卡券余额消费',
            ];
            $AccountLog = new AccountLog();
            loggingHelper::writeLog('diandi_tea', 'BalanceBuyCoupon', '更新系统用户余额变更记录', $accountLoginfo);
            $AccountLog->load($accountLoginfo, '');
            $AccountLog->save();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            loggingHelper::writeLog('diandi_tea', 'BalanceBuyCoupon', '包间续费订单处理,错误信息Exception', $e);
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            loggingHelper::writeLog('diandi_tea', 'BalanceBuyCoupon', '包间续费订单处理,错误信息Throwable', $e);
            throw $e;
        }
        return ResultHelper::json(200, '余额支付成功');
    }
}
