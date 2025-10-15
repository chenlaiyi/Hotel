<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-24 09:37:35
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-05-18 15:18:30
 */
namespace addons\diandi_place\admin\manage\landlord;
use addons\diandi_place\models\landlord\PlaceLandlord;
use addons\diandi_place\models\place\PlaceCountry;
use addons\diandi_place\services\LandlordService;
use addons\diandi_place\services\MessageService;
use addons\diandi_place\Traits\LandlordTrait;
use admin\controllers\AController;
use common\helpers\ImageHelper;
use common\helpers\ResultHelper;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
/**
 * 房东管理
 */
class InfoController extends AController
{
    use LandlordTrait;
    public array $authOptional = ['country'];
    public array $adminAuth = ['identity', 'landlord','country'];
    public string $modelSearchName = 'PlaceCountry';
    /**
     * 初始房东信息
     * @return array
     */
    public function actionInit(): array
    {
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        $list = LandlordService::initAuth($user_id);
        return ResultHelper::json(200, '获取成功', $list);
    }
    /**
     * 国家
     * @return array
     */
    function actionCountry()
    {
        $list = PlaceCountry::find()->asArray()->all();
        $lists = [];
        foreach ($list as $item) {
            $lists[$item['initial']][] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'initial'=> $item['initial'],
                'prefix_num'=> $item['prefix_num'],
                'icon' => ImageHelper::tomedia($item['icon']),
            ];
        }
        $hot = PlaceCountry::find()->where(['is_hot' => 1])->asArray()->all();
        $hot = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'prefix_num'=> $item['prefix_num'],
                'icon' => ImageHelper::tomedia($item['icon']),
            ];
        }, $hot);
        return ResultHelper::json(200, '获取成功', [
            'list'=>$lists,
            'hot'=>$hot,
        ]);
    }
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
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        $REs = LandlordService::detail($user_id);
        return ResultHelper::json(200, '获取成功', $REs);
    }
    /**
     * 修改资料
     * @return array
     * @throws \Throwable
     * @throws StaleObjectException
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionEdit(): array
    {
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        $gender = (int)Yii::$app->request->input('gender');
        $realname = Yii::$app->request->input('realname');
        $idcard = Yii::$app->request->input('idcard');
        $avatar = Yii::$app->request->input('avatar');
        $res = LandlordService::edit($user_id, $realname, $avatar, $gender, $idcard);
        if ($res) {
            return ResultHelper::json(200, '编辑成功');
        } else {
            return ResultHelper::json(200, '编辑失败');
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
    public function actionPassword(): array
    {
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        $password = Yii::$app->request->input('password');
        $code = (int)Yii::$app->request->input('code');
        if (empty($code)) {
            return ResultHelper::json(401, '请输入验证码', []);
        }
        $REs = LandlordService::reSetPassWord($user_id, $password, $code);
        return ResultHelper::json(200, '设置成功', $REs);
    }
    /**
     * 房东身份认证
     * @return array
     * @date 2023-04-24
     * @throws NotFoundHttpException
     * @author Wang Chunsheng
     * @since
     * @example
     */
    public function actionLandlord(): array
    {
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        $realname = Yii::$app->request->input('realname');
        $icard_code = Yii::$app->request->input('icard_code');
        $icard_front = Yii::$app->request->input('icard_front');
        $icard_back = Yii::$app->request->input('icard_back');
        $REs = LandlordService::landlordIdentity($user_id, $realname, $icard_code, $icard_front, $icard_back);
        return ResultHelper::json(200, '提交成功', $REs);
    }
    /**
     * 身份认证
     * @return array
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionIdentity(): array
    {
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        $realname = Yii::$app->request->input('realname');
        $icard_code = Yii::$app->request->input('icard_code');
        $icard_front = Yii::$app->request->input('icard_front');
        $icard_back = Yii::$app->request->input('icard_back');
        $REs = LandlordService::identity($user_id, $realname, $icard_code, $icard_front, $icard_back);
        return ResultHelper::json(200, '提交成功', $REs);
    }
    public function actionMessage(): array
    {
        $REs = MessageService::messageToBloc();
        return ResultHelper::json(200, '提交成功', $REs);
    }
    /**
     * 业务类型设置
     * @return array
     * @throws NotFoundHttpException|InvalidConfigException
     */
    public function actionSetType(): array
    {
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        $config = Yii::$app->request->input('config',[]);
        $REs = LandlordService::setType($user_id, $config);
        return ResultHelper::json(200, '设置成功', $REs);
    }
    /**
     * 获取所有房东
     * @return string[]
     * @throws \Throwable
     */
    public function actionList(): array
    {
        $page = Yii::$app->request->input('page', 1);
        $pageSize = Yii::$app->request->input('pageSize', 10);
        $keywords = Yii::$app->request->input('keywords', '');
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        $REs = LandlordService::listAll($page,  $pageSize, $keywords, $user_id);
        return ResultHelper::json(200, '获取成功', $REs);
    }
    function allowAction(): array
    {
        return ['*'];
    }
}
