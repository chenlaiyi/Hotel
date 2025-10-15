<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-03-14 10:56:16
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-28 13:41:04
 */
namespace addons\diandi_place\api\public;
use addons\diandi_place\services\MemberService;
use api\controllers\AController;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use Yii;
use yii\db\StaleObjectException;
/**
 * 会员业务
 * @date 2023-05-30
 * @example
 * @author Wang Chunsheng
 * @since
 */
class MemberController extends AController
{
    public $modelClass = '';
    // protected $signOptional = ['Integral'];
    public function actionInfo(): array
   {
        $member_id = Yii::$app->user->identity->member_id??0;
        $info = MemberService::info($member_id);
        return ResultHelper::json(200, '获取成功', $info);
    }
    public function actionIntegral(): array
    {
        $member_id = Yii::$app->user->identity->member_id??0;
        $info = MemberService::integral($member_id);
        return ResultHelper::json(200, '请求成功', $info);
    }
    public function actionBalance(): array
    {
        $member_id = Yii::$app->user->identity->member_id??0;
        $info = MemberService::balance($member_id);
        return ResultHelper::json(200, '请求成功', $info);
    }
    public function actionEditMember(): array
   {
        $member_id = Yii::$app->user->identity->member_id??0;
        $data = Yii::$app->request->post();
        // if($data['gender'] == '男'){
        //     $data['gender'] = 0;
        // }elseif($data['gender'] == '女'){
        //     $data['gender'] = 1;
        // }
        MemberService::editMember($member_id, $data);
        return ResultHelper::json(200, '修改成功');
    }
    /**
     * 我的资产
     * @return array
     * @date 2023-05-30
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionAccount(): array
    {
        return ResultHelper::json(200, '获取成功');
    }
    public function actionAddFriend(): array
   {
        $member_id = Yii::$app->user->identity->member_id??0;
        $face_img =\Yii::$app->request->input('face_img');
        $realname =\Yii::$app->request->input('realname');
        $mobile =\Yii::$app->request->input('mobile');
        $icard_code =\Yii::$app->request->input('icard_code');
        $icard_front =\Yii::$app->request->input('icard_front');
        $icard_back =\Yii::$app->request->input('icard_back');
        loggingHelper::writeLog('diandi_place', 'actionAddFriend', '新增好友', [
            'member_id' => $member_id
        ]);
        $Res = MemberService::addFriend($member_id, $face_img, $realname, $mobile, $icard_code, $icard_front, $icard_back);
        return ResultHelper::json(200, '新增成功', $Res);
    }
    public function actionEditFriend(): array
   {
        $member_id = Yii::$app->user->identity->member_id??0;
        $face_img =\Yii::$app->request->input('face_img');
        $realname =\Yii::$app->request->input('realname');
        $id =\Yii::$app->request->input('id');
        $mobile =\Yii::$app->request->input('mobile');
        $icard_code =\Yii::$app->request->input('icard_code');
        $icard_front =\Yii::$app->request->input('icard_front');
        $icard_back =\Yii::$app->request->input('icard_back');
        $Res = MemberService::editFriend($member_id, $id, $face_img, $realname, $mobile, $icard_code, $icard_front, $icard_back);
        return ResultHelper::json(200, '修改成功', $Res);
    }
    public function actionMyFriend(): array
   {
        $member_id = Yii::$app->user->identity->member_id??0;
        $Res = MemberService::myFriendByMid($member_id);
        return ResultHelper::json(200, '获取成功', $Res);
    }
    public function actionDelFriend(): array
   {
        $member_id = Yii::$app->user->identity->member_id??0;
        $friend_id =\Yii::$app->request->input('friend_id');
        $Res = MemberService::delFriendByMid($member_id, $friend_id);
        return ResultHelper::json(200, '获取成功', (array)$Res);
    }
    public function actionAddCon(): array
   {
        $member_id = Yii::$app->user->identity->member_id??0;
        $content =\Yii::$app->request->input('content');
        try {
            $Res = MemberService::editCon($member_id, $content);
        } catch (StaleObjectException $e) {
            return ResultHelper::json(400, $e->getMessage());
        } catch (\Throwable $e) {
            return ResultHelper::json(400, $e->getMessage());
        }
        return ResultHelper::json(200, '修改成功', (array)$Res);
    }
    public function actionGetCon(): array
   {
        $member_id = Yii::$app->user->identity->member_id??0;
        $Res = MemberService::GetCon($member_id);
        return ResultHelper::json(200, '修改成功', [
            'rea' =>  $Res
        ]);
    }
}
