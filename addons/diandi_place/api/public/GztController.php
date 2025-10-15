<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-03-14 10:56:16
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-05-30 10:04:11
 */
namespace addons\diandi_place\api\public;
use addons\diandi_place\services\apiSdk;
use addons\diandi_place\services\MemberService;
use addons\diandi_place\services\MessageService;
use api\controllers\AController;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use Yii;
/**
 *    国政通接口
 * @date 2023-03-22
 * @example
 * @author Wang Chunsheng
 * @since
 */
class GztController extends AController
{
    public $modelClass = '';
    protected array $authOptional = ['*'];
    public function actionSaveFace()
   {
        $isShow = (int)\Yii::$app->request->input('isShow');
        if ($isShow) {
            $realname = trim(Yii::$app->request->input('realname'));
            $icard_code =\Yii::$app->request->input('icard_code');
            $face_img =\Yii::$app->request->input('face_img');
            $face_old =\Yii::$app->request->input('face_old');
            $mobile = trim(Yii::$app->request->input('mobile'));
            $order_id = 1;
            $member_id = Yii::$app->user->identity->member_id??0;
            if (empty($face_img)) {
                return ResultHelper::json(400, '人脸照片不能为空');
            }
            if (empty($realname)) {
                return ResultHelper::json(400, '姓名不能为空');
            }
            if (empty($icard_code)) {
                return ResultHelper::json(400, '身份证号码不能为空');
            }
            if (empty($mobile)) {
                return ResultHelper::json(400, '手机号不能为空');
            }
            // 人脸核验开始
            $checkRes = apiSdk::querySingle($realname, $icard_code, $face_img);
            loggingHelper::writeLog('diandi_place', 'actionSaveFace', '人脸核验结果', $checkRes);
            if ((int) $checkRes['RESULT'] != 1 || (int) $checkRes['detail']['resultCode'] != 1001) {
                return ResultHelper::json(400, $checkRes['detail']['resultMsg']);
            }
            $face_old =\Yii::$app->request->input('face_old');
            // $checkRes['RESULT'] = 1;
            $Res = MemberService::saveFace($member_id, $face_old, $face_img, $icard_code, $realname, $mobile, (int) $checkRes['RESULT']);
            // $Res = MemberService::saveFace($order_id, $member_id, $face_img, $icard_code, $realname, $mobile, -1);
            return ResultHelper::json(200, '保存成功', [
                'saveRes' => $Res,
                'checkInRes' => (int) $checkRes['RESULT']
            ]);
        } else {
            return ResultHelper::json(200, '验证成功');
        }
    }
    public function actionInfo()
   {
        $member_id = (int)\Yii::$app->request->input('member_id');
        $order_id = (int)\Yii::$app->request->input('order_id');
        $Res = MemberService::getFace($member_id, $order_id);
        return ResultHelper::json(200, '获取成功', $Res);
    }
    /**
     * 真实性核验
     * @return array
     * @date 2023-03-26
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionQuerySingle()
   {
        $name = trim(Yii::$app->request->input('realname'));
        $image =\Yii::$app->request->input('face_img');
        $idCard = (int)\Yii::$app->request->input('icard_code');
        if (empty($name)) {
            return ResultHelper::json(400, 'realname 不能为空');
        }
        if (empty($image)) {
            return ResultHelper::json(400, 'face_img 不能为空');
        }
        if (empty($idCard)) {
            return ResultHelper::json(400, 'icard_code 不能为空');
        }
        $list = apiSdk::querySingle($name, $idCard, $image);
        MessageService::checkInMsg();
        return ResultHelper::json(200, '核验成功', $list);
    }
}
