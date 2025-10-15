<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-24 09:37:35
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-05-18 15:18:30
 */
namespace addons\diandi_place\api\manage\member;
use addons\diandi_place\services\bloc\PlaceBlocService;
use addons\diandi_place\services\MessageService;
use addons\diandi_place\Traits\LandlordTrait;
use api\controllers\AController;
use common\helpers\ResultHelper;
use Throwable;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\db\StaleObjectException;
/**
 * 房东管理
 */
class InfoController extends AController
{
    use LandlordTrait;
    /**
     * 获取商家资料
     * @return array
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionDetail(): array
    {
        $member_id = Yii::$app->user->identity->member_id ?? 0;
        $REs = PlaceBlocService::detail($member_id);
        return ResultHelper::json(200, '获取成功', $REs);
    }
    /**
     * 修改资料
     * @return array
     * @throws Throwable
     * @throws StaleObjectException
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionEdit()
    {
        $member_id = Yii::$app->user->identity->member_id ?? 0;
        $gender = (int)Yii::$app->request->input('gender');
        $realname = Yii::$app->request->input('realname');
        $idcard = Yii::$app->request->input('idcard');
        $avatar = Yii::$app->request->input('avatar');
        $res = PlaceBlocService::edit($member_id, $realname, $avatar, $gender, $idcard);
        if ($res) {
            return ResultHelper::json(200, '编辑成功', []);
        }else{
            return ResultHelper::json(400, '编辑失败');
        }
    }
    /**
     * 设置密码
     * @return array
     * @throws ErrorException
     * @throws Exception
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionPassword()
    {
        $member_id = Yii::$app->user->identity->member_id ?? 0;
        $password = Yii::$app->request->input('password');
        $code = (int)Yii::$app->request->input('code');
        if (empty($code)) {
            return ResultHelper::json(401, '请输入验证码', []);
        }
        $REs = PlaceBlocService::reSetPassWord($member_id, $password, $code);
        return ResultHelper::json(200, '设置成功', $REs);
    }
    /**
     * 身份认证
     * @return array
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionIdentity()
    {
        $member_id = Yii::$app->user->identity->member_id ?? 0;
        $realname = Yii::$app->request->input('realname');
        $icard_code = Yii::$app->request->input('icard_code');
        $icard_front = Yii::$app->request->input('icard_front');
        $icard_back = Yii::$app->request->input('icard_back');
        $REs = PlaceBlocService::identity($member_id, $realname, $icard_code, $icard_front, $icard_back);
        return ResultHelper::json(200, '提交成功', $REs);
    }
    public function actionMessage()
    {
        $REs = MessageService::messageToBloc();
        return ResultHelper::json(200, '提交成功', $REs);
    }
}
