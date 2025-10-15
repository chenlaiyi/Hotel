<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:34:07
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-25 09:21:54
 */
namespace addons\diandi_place\admin\manage\config;
use addons\diandi_place\services\bloc\ConfigService;
use addons\diandi_place\Traits\LandlordTrait;
use admin\controllers\AController;
use common\helpers\ResultHelper;
use Yii;
use yii\web\NotFoundHttpException;
/**
 * 公共配置
 * @date 2023-04-23
 * @example
 * @author Wang Chunsheng
 * @since
 */
class BaseController extends AController
{
    use LandlordTrait;
    public string $modelSearchName = 'PlaceCountry';
    /**
     * 公共配置获取
     * @return array
     * @date 2023-04-24
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionInfo(): array
    {
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        $REs = ConfigService::baseConf($user_id);
        return ResultHelper::json(200, '获取成功', $REs);
    }
    /**
     * 公共配置设置
     * @return array
     * @date 2023-04-23
     * @throws NotFoundHttpException
     * @author Wang Chunsheng
     * @since
     * @example
     */
    public function actionSet(): array
    {
        $bloc_id = \Yii::$app->request->input('bloc_id', 0);
        $store_id = \Yii::$app->request->input('store_id', 0);
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        $lead_time = \Yii::$app->request->input('lead_time');
        $delay_time = \Yii::$app->request->input('delay_time');
        $maintain_time = \Yii::$app->request->input('maintain_time');
        $is_open = \Yii::$app->request->input('is_open');
        $electrovalence = \Yii::$app->request->input('electrovalence');
        $REs = ConfigService::baseSet($bloc_id, $store_id, $user_id, $lead_time, $delay_time, $maintain_time, $is_open, $electrovalence);
        return ResultHelper::json(200, '设置成功', $REs);
    }
    function allowAction(): array
    {
        return ['*'];
    }
}
