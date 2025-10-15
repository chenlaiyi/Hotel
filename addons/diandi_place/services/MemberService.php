<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-11 15:04:59
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-28 14:04:04
 */
namespace addons\diandi_place\services;
use addons\diandi_place\models\enums\PlaceMemberAuthStatusEnums;
use addons\diandi_place\models\member\PlaceMember;
use addons\diandi_place\models\order\PlaceOrderMember;
use addons\diandi_place\models\place\PlaceLandlord;
use api\models\DdMember;
use common\components\ActiveRecord\YiiActiveRecord;
use common\helpers\ErrorsHelper;
use common\helpers\ImageHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\models\AccountLog;
use common\models\DdMemberAccount;
use common\services\BaseService;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
class MemberService extends BaseService
{
    public static function info($member_id): array|YiiActiveRecord
    {
        $HotelMember = new PlaceMember();
        $member = $HotelMember->find()->where(['member_id' => $member_id])->with(['member', 'wxappFans', 'wechatFans'])->asArray()->one();
        if (empty($member)) {
            $HotelMember->load([
                'member_id' => $member_id,
                'realname' => '星级用户',
            ], '') && $HotelMember->save();
            $msg = ErrorsHelper::getModelError($HotelMember);
            if ($msg) {
                return ResultHelper::json(400, $msg);
            }
            $member = $HotelMember->find()->where(['member_id' => $member_id])->with(['member', 'wxappFans', 'wechatFans'])->asArray()->one();
        }
        $member['avatar'] = ImageHelper::tomedia($member['avatar'], 'avatar15.jpg');
        return $member;
    }
    public static function saveFace($member_id, $face_old, $face_img, $icard_code, $realname, $mobile, $check_in = 1): array
    {
        $resList = [];
        $HotelOrderMember = new PlaceOrderMember();
        $om = PlaceOrderMember::find()->where([
            'member_id' => $member_id,
            'order_id' => 0,
            'icard_code' => $icard_code,
        ])->select('id')->scalar();
        $resList['check_in_id'] = $om;
        $data = [
            'check_in' => $check_in,
            'mobile' => $mobile,
            'order_id' => 0,
            'member_id' => $member_id,
            'face_img' => $face_img,
            'icard_code' => $icard_code,
            'realname' => $realname
        ];
        if (empty($om)) {
            $HotelOrderMember->load($data, '') && $HotelOrderMember->save();
            $msg = ErrorsHelper::getModelError($HotelOrderMember);
            if ($msg) {
                return ResultHelper::json(400, $msg);
            }
            $resList['check_in_id'] = $HotelOrderMember->id;
        } else {
            $HotelOrderMember->updateAll($data, [
                'id' => $om
            ]);
        }
        // 人脸核验成功，开始人脸机授权
        if ((int) $check_in === 1) {
            MessageService::checkInMsg(1);
            // 给人脸机注册
            $tag = '演示用户';
            $cardNo = '12345678';
            try {
                $Res = WoSdk::personCreate($cardNo, $realname, $mobile, $tag, $icard_code);
            } catch (GuzzleException $e) {
                return ResultHelper::json(400, $e->getMessage(), (array)$e);
            }
            loggingHelper::writeLog('diandi_place', 'MemberService/saveFace', '照片注册数据', $Res);
            $resList['img_register_res'] = (int) $Res['result'];
            if ((int) $Res['result'] === 1) {
                $personGuid = $Res['data']['guid'];
                loggingHelper::writeLog('diandi_place', 'MemberService/saveFace', '用户数据', $Res['data']);
                $om = PlaceOrderMember::find()->where([
                    'member_id' => $member_id,
                    'order_id' => 0,
                    'icard_code' => $icard_code,
                ])->select('id')->scalar();
                $HotelOrderMember->updateAll(['personGuid' => $personGuid], [
                    'id' => $om
                ]);
                $Res =  WoSdk::faceAdd($personGuid, $face_old);
                $resList['face_register_res'] = (int) $Res['result'];
                loggingHelper::writeLog('diandi_place', 'MemberService/saveFace', '人脸机注册', [
                    'Res' => $Res,
                    'face_old' => $face_old,
                    'personGuid' => $personGuid,
                ]);
            }
        }
        // else {
        //     MessageService::checkInMsg(0);
        //     // 给人脸机注册
        //     $tag = '演示用户';
        //     $cardNo = '12345678' . rand(1, 88);
        //     $Res = WoSdk::personCreate($cardNo, $realname, $mobile, $tag, $icard_code);
        //     $resList['img_register_res'] = (int) $Res['result'];
        //     loggingHelper::writeLog('diandi_place', 'MemberService/saveFace', '照片注册数据', $Res);
        //     if ((int) $Res['result'] === 1) {
        //         $personGuid = $Res['data']['guid'];
        //         loggingHelper::writeLog('diandi_place', 'MemberService/saveFace', '用户数据', $Res['data']);
        //         $om = PlaceOrderMember::find()->where([
        //             'member_id' => $member_id,
        //             'order_id' => 0,
        //             'icard_code' => $icard_code,
        //         ])->select('id')->scalar();
        //         $HotelOrderMember->updateAll(['personGuid' => $personGuid], [
        //             'id' => $om
        //         ]);
        //         $Res =  WoSdk::faceAdd($personGuid, $face_img);
        //         $resList['face_register_res'] = (int) $Res['result'];
        //         loggingHelper::writeLog('diandi_place', 'MemberService/saveFace', '人脸机注册', $Res);
        //     }
        // }
        return $resList;
    }
    public static function getFace($member_id, $order_id): array
    {
        $HotelOrderMember = new PlaceOrderMember();
        $info =  $HotelOrderMember->find()->where([
            'member_id' => $member_id,
            'order_id' => $order_id
        ])->asArray()->one();
        if(empty($info)){
            return ResultHelper::json(400, '请核验身份后下单');
        }
        $personGuid =  $info['personGuid']; //\Yii::$app->request->input('personGuid');
        if (empty($personGuid)) {
            return ResultHelper::json(400, 'personGuid 不能为空', $info);
        }
        $deviceKey = '84E0F425C137527A'; //\Yii::$app->request->input('deviceKey');
        $res = WoSdk::authDevice($deviceKey, $personGuid);
        MessageService::lockAuth($order_id);
        return $info;
    }
    public static function balance($member_id): array
    {
        $month = [];
        //用户余额充值记录
        $info['recharge_list'] = PlaceRechargeList::find()
            ->with('recharge')
            ->where(['member_id' => $member_id, 'status' => 2])
            ->select(['*', "DATE_FORMAT(create_time,'%Y-%m') AS month"])
            //->groupBy('month')
            ->asArray()
            ->all();
        foreach ($info['recharge_list'] as $k => &$v) {
            $v['pay_time'] = date('Y-m-d H:i', strtotime($v['pay_time']));
            $v['all_money'] = number_format($v['price'] + $v['recharge']['give_money'], 2);
            $v['price'] = floor($v['price']);
            $v['recharge']['give_money'] = floor($v['recharge']['give_money']);
            $v['is_have'] = PlaceInvoice::find()->where(['order_id' => $v['id'], 'type' => 2])->asArray()->one() ? 1 : 2;
            $month[$v['month']][] = $v;
        }
        $data = [];
        $a = 0;
        foreach ($month as $ke => $valu) {
            $data[$a]['time'] = $ke;
            $data[$a]['data'] = $valu;
            $a = $a + 1;
        }
        $info['recharge_list'] = $data;
        //用户余额消费记录
        $order_list = AccountLog::find()
            ->where(['member_id' => $member_id, 'account_type' => ['tea_buy_coupon_balance', 'tea_order_balance', 'tea_member_renew_money_balance', 'tea_member_money_balance']])
            ->select(['*', "FROM_UNIXTIME(create_time,'%Y-%m') as month"])
            ->orderBy(['create_time' => SORT_DESC])
            ->asArray()
            ->all();
        $month = [];
        foreach ($order_list as $key => &$val) {
            $val['s_money'] = number_format($val['money'] + $val['old_money'], 2);
            $val['money'] = number_format($val['money'], 2);
            $val['create_time'] = date('Y-m-d H:i:s', $val['create_time']);
            $month[$val['month']][] = $val;
        }
        $data = [];
        $a = 0;
        foreach ($month as $ke => $valu) {
            $data[$a]['time'] = $ke;
            $data[$a]['data'] = $valu;
            $a = $a + 1;
        }
        $info['order_list'] = $data;
        return $info;
    }
    public static function integral($member_id)
    {
        //积分明细
        $integral_addlist = AccountLog::find()
            ->where([
                'member_id' => $member_id,
                'is_add' => 0,
                'account_type' => [
                    'tea_member_give_integral',
                    'tea_member_give_inte_ren',
                    'tea_member_give_inte_cou',
                    'tea_member_give_inte_rec',
                ],
            ])
            ->select(['*', "FROM_UNIXTIME(create_time,'%Y-%m') as month"])
            ->orderBy(['create_time' => SORT_DESC])
            ->asArray()
            ->all();
        $month = [];
        foreach ($integral_addlist as $key => &$val) {
            $val['old_money'] = round($val['old_money']);
            $val['money'] = round($val['money']);
            $val['surplus_integral'] = $val['old_money'] + $val['money'];
            $val['create_time'] = date('Y-m-d H:i:s', $val['create_time']);
            $month[$val['month']][] = $val;
        }
        $data = [];
        $a = 0;
        foreach ($month as $ke => $valu) {
            $data[$a]['time'] = $ke;
            $data[$a]['data'] = $valu;
            $a = $a + 1;
        }
        $user_integral = DdMemberAccount::find()
            ->select(['user_integral'])
            ->where(['member_id' => $member_id])
            ->asArray()
            ->one()['user_integral'];
        $list['integral'] = $data;
        $list['user_integral'] = $user_integral;
        //兑换明细
        return $list;
    }
    public static function editFriend($member_id, $id, $face_img, $realname, $mobile, $icard_code, $icard_front, $icard_back, $status = 1): array
    {
        $HotelMemberFriend = new PlaceMemberFriend();
        $ishave = $HotelMemberFriend->findOne(['id' => $id, 'member_id' => $member_id]);
        if (empty($ishave)) {
            return ResultHelper::json(400, '该用户信息不存在');
        }
        $ishave->member_id  = $member_id; //  会员ID
        $ishave->face_img  = $face_img; //  脸部照片
        $ishave->realname  = $realname; //  真实姓名
        $ishave->mobile  = $mobile; //  手机号
        $ishave->status  = $status; //  用户状态
        $ishave->icard_code  = $icard_code; //  身份证号码
        $ishave->icard_front  = $icard_front; //  身份证正面
        $ishave->icard_back  = $icard_back; //  身份证反面
        try {
            if ($ishave->update()) {
                return ResultHelper::json(200, '编辑成功');
            }else{
                return ResultHelper::json(400,'编辑失败');
            }
        } catch (StaleObjectException $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        } catch (\Throwable $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        }
    }
    public static function addFriend($member_id, $face_img, $realname, $mobile, $icard_code, $icard_front, $icard_back, $status = 1): array
    {
        $HotelMemberFriend = new PlaceMemberFriend();
        $ishave = $HotelMemberFriend->find(['icard_code' => $icard_code, 'member_id' => $member_id]);
        if (empty($ishave)) {
            return ResultHelper::json(400, '请勿重复添加');
        }
        $HotelMemberFriend->load([
            'member_id' => $member_id, //  会员ID
            'face_img' => $face_img, //  脸部照片
            'realname' => $realname, //  真实姓名
            'mobile' => $mobile, //  手机号
            'status' => $status, //  用户状态
            'icard_code' => $icard_code, //  身份证号码
            'icard_front' => $icard_front, //  身份证正面
            'icard_back' => $icard_back, //  身份证反面
        ], '');
        if (!$HotelMemberFriend->save()) {
            $msg = ErrorsHelper::getModelError($HotelMemberFriend);
            return ResultHelper::json(400, $msg);
        }
        return $HotelMemberFriend->toArray();
    }
    public static function myFriendByMid($member_id)
    {
        $list = PlaceMemberFriend::find()->where(['member_id' => $member_id])->asArray()->all();
        foreach ($list as $key => &$value) {
            $value['icard_code_url'] = ImageHelper::tomedia($value['icard_code']);
            $value['icard_front_url'] = ImageHelper::tomedia($value['icard_front']);
            $value['icard_back_url'] = ImageHelper::tomedia($value['icard_back']);
        }
        return $list;
    }
    public static function delFriendByMid($member_id, $friend_id)
    {
        $friend = PlaceMemberFriend::find()->where(['member_id' => $member_id, 'id' => $friend_id])->one();
        return $friend->delete();
    }
    public static function editMember()
    {
    }
    /**
     * 修改长租协议
     * @param $member_id
     * @param $content
     * @return array
     * @throws StaleObjectException
     * @throws \Throwable
     * @date 2023-06-27
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function editCon($member_id, $content): array
    {
        $Landlord = PlaceLandlord::findOne(['member_id' => $member_id]);
        loggingHelper::writeLog('diandi_hote', 'editCon', '编辑协议', [
            'member_id' => $member_id,
            'content' => $content
        ]);
        if ($Landlord) {
            $Landlord->contract = $content;
            if($Landlord->update()){
                return ResultHelper::json(200, '编辑成功');
            }else{
                return ResultHelper::json(400, '编辑失败');
            }
        } else {
            return ResultHelper::json(400, '您不是房东');
        }
    }
    public static function getCon($member_id): array|int|string
    {
        $contract = PlaceLandlord::find()->where(['member_id' => $member_id])->select('contract')->scalar();
        if ($contract) {
            return $contract;
        } else {
            return ResultHelper::json(400, '您不是房东');
        }
    }
    /**
     * 编辑资料
     * @param int $member_id
     * @param string $realname
     * @param string $avatar
     * @param int $gender
     * @param string $idcard
     * @return bool|false|int
     * @throws StaleObjectException
     * @throws \Throwable
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function edit(int $member_id, string $realname, string $avatar, int $gender, string $idcard = '')
    {
        $member = PlaceMember::findByMemberId($member_id);
        // 未认证 || 认证失败的 可以修改姓名身份证号
        if (in_array($member->icard_auth_status, [
            PlaceMemberAuthStatusEnums::NO_AUTH, PlaceMemberAuthStatusEnums::AUTH_FAIL
        ])) {
            $member->icard_code = $idcard;
            $member->realname   = $realname;
        }
        $member->avatar = $avatar;
        DdMember::updateAll([
            'gender' => $gender
        ], [
            'member_id' => $member_id
        ]);
        return $member->update();
    }
    /**
     * 身份认证
     * @param int $member_id
     * @param string $realname
     * @param string $icard_code
     * @param string $icard_front
     * @param string $icard_back
     * @return PlaceMember|array
     * @date 2023-04-24
     * @throws NotFoundHttpException
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function identity(int $member_id, string $realname, string $icard_code, string $icard_front, string $icard_back): array|HotelMember
    {
        $member = PlaceMember::findByMemberId($member_id);
        // 未认证 || 认证失败的可以进行房东认证
        if (in_array($member->icard_auth_status, [
            PlaceMemberAuthStatusEnums::AUTHING, PlaceMemberAuthStatusEnums::AUTH_SUCCESS
        ])) {
            $enum = PlaceMemberAuthStatusEnums::listData();
            return ResultHelper::json(400, $enum[$member->icard_auth_status] . ',请勿重复认证', []);
        }
        $member->realname          = $realname;
        $member->icard_code        = $icard_code;
        $member->icard_front       = $icard_front;
        $member->icard_back        = $icard_back;
        $member->icard_auth_status = PlaceMemberAuthStatusEnums::AUTHING;
        if (!$member->save()) {
            $msg = ErrorsHelper::getModelError($member);
            return ResultHelper::json(400, $msg, []);
        }
        return $member;
    }
    /**
     * 资料信息
     * @param int $member_id
     * @return array
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function detail(int $member_id): array
    {
        $member = MemberService::info($member_id);
        return [
            'member_id'         => $member_id,
            'mobile'            => $member['member']['mobile'],
            'avatar'            => ImageHelper::tomedia($member['avatar'], 'avatar.jpg'),
            'idcard'            => $member['icard_code'],
            'realname'          => $member['realname'],
            'icard_auth_status' => $member['icard_auth_status'],
            'store_id'          => $member['store_id'],
            'bloc_id'           => $member['bloc_id'],
            'status'            => $member['status'],
            'gender'            => $member['member']['gender'],
            'group_id'          => $member['member']['group_id'],
            'level'             => $member['member']['level'],
            'vip'               => $member['is_vip'] ?: 0,
        ];
    }
    /**
     * 修改密码
     * @param int $member_id
     * @param string $password
     * @param int $code
     * @return DdMember|array|object[]|string[]
     * @throws ErrorException
     * @throws Exception
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function reSetPassWord(int $member_id, string $password, int $code): array|DdMember
    {
        $member = DdMember::findIdentity($member_id);
        $mobile = $member->mobile;
        $sendcode = Yii::$app->cache->get($mobile . '_code');
        if (empty($sendcode)) {
            return ResultHelper::json(401, '验证码已过期');
        }
        if ($code != $sendcode) {
            return ResultHelper::json(401, '验证码错误');
        }
        $member->password_hash = Yii::$app->security->generatePasswordHash($password);
        $member->generatePasswordResetToken();
        if ($member->save()) {
            Yii::$app->user->logout();
            // 清除验证码
            Yii::$app->cache->delete($mobile . '_code');
            return $member;
        }
        $msg = ErrorsHelper::getModelError($member);
        return ResultHelper::json(400, $msg, []);
    }
    /**
     * 创建密码
     * @return void
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function createPassWord()
    {
        # code...
    }
    /**
     * 创建加密字符串
     * @return void
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public static function createAuthKey()
    {
        # code...
    }
}
