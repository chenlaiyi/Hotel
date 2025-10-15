<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-03-14 10:56:16
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-30 16:16:19
 */
namespace addons\diandi_place\admin\manage\agreement;
use addons\diandi_hotel\services\OrderService;
use addons\diandi_place\Traits\LandlordTrait;
use admin\controllers\AController;
use common\helpers\ResultHelper;
use Yii;
/**
 * 民宿业务
 * @date 2023-05-30
 * @example
 * @author Wang Chunsheng
 * @since
 */
class IndexController extends AController
{
    use LandlordTrait;
    public $modelClass = '';
    protected array $authOptional = [];
    public string $modelSearchName = 'PlaceCountry';
    /**
     * 长租协议
     * @return array
     */
    public function actionContract(): array
    {
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        $status = \Yii::$app->request->input('status');
        $page = \Yii::$app->request->input('page') ?? 1;
        $pageSize = \Yii::$app->request->input('pageSize') ?? 10;
        $Res = OrderService::contractByMid($user_id, $status, $page, $pageSize);
        return ResultHelper::json(200, '获取成功', $Res);
    }
    /**
     * 编辑协议
     * @return array
     */
    public function actionUpcontract(): array
    {
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        $user_sign_img = \Yii::$app->request->input('user_sign_img');
        $landlord_sign_img = \Yii::$app->request->input('landlord_sign_img');
        $id = \Yii::$app->request->input('id');
        $Res = OrderService::upContractByid($id, $user_id, $user_sign_img, $landlord_sign_img);
        return ResultHelper::json(200, '获取成功', $Res);
    }
    function allowAction(): array
    {
        return ['*'];
    }
}
