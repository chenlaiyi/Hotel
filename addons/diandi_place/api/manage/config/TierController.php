<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:34:15
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-05-17 18:00:00
 */
namespace addons\diandi_place\api\manage\config;
use addons\diandi_place\services\bloc\ConfigService;
use addons\diandi_place\Traits\LandlordTrait;
use api\controllers\AController;
use common\helpers\ResultHelper;
use Yii;
use yii\db\StaleObjectException;
/**
 * 楼层管理
 * @date 2023-04-23
 * @example
 * @author Wang Chunsheng
 * @since
 */
class TierController extends AController
{
    use LandlordTrait;
    public function actionInfo(): array
    {
        $id = Yii::$app->request->input('id');
        $REs = ConfigService::tierInfo($id);
        return ResultHelper::json(200, '获取成功', $REs);
    }
    /**
     * 单个添加
     * @return array
     * @date 2023-04-23
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionAdd(): array
    {
        $hotel_id = Yii::$app->request->input('hotel_id');
        $title = Yii::$app->request->input('title');
        $type_id = Yii::$app->request->input('type_id');
        $prefix = Yii::$app->request->input('prefix', '');
        $REs = ConfigService::tieradd($type_id, $hotel_id, $title, $prefix);
        return ResultHelper::json(200, '添加成功', $REs);
    }
    /**
     * 楼层列表
     * @return array
     * @date 2023-05-08
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionList(): array
    {
        $type_id = Yii::$app->request->input('type_id');
        if (empty($type_id)) {
            return ResultHelper::json(400, 'type_id 不能为空');
        }
        $hotel_id = Yii::$app->request->input('hotel_id');
        if (empty($hotel_id)) {
            return ResultHelper::json(400, 'hotel_id 不能为空');
        }
        $keywords = Yii::$app->request->input('keywords') ?? '';
        $REs = ConfigService::tierlist($type_id, $hotel_id, $keywords);
        return ResultHelper::json(200, '获取成功', $REs);
    }
    /**
     * 编辑楼层
     * @return array
     * @throws \Throwable
     * @throws StaleObjectException
     * @date 2023-05-08
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionEdit(): array
    {
        $type_id = Yii::$app->request->input('type_id');
        $id = Yii::$app->request->input('id');
        $hotel_id = Yii::$app->request->input('hotel_id');
        $title = Yii::$app->request->input('title');
        $prefix = Yii::$app->request->input('prefix');
        $REs = ConfigService::tieredit($id, $type_id, $hotel_id, $title, $prefix);
        return ResultHelper::json(200, '添加成功', $REs);
    }
    /**
     * 批量添加
     * @return array
     * @date 2023-04-23
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionAdds(): array
    {
        $nums = Yii::$app->request->input('nums');
        $hotel_id = Yii::$app->request->input('hotel_id');
        $type_id = Yii::$app->request->input('type_id');
        if (!$nums){
            return ResultHelper::json(400, 'nums 不能为空');
        }
        if (!$hotel_id){
            return ResultHelper::json(400, 'hotel_id 不能为空');
        }
        if (!$type_id){
            return ResultHelper::json(400, 'type_id 不能为空');
        }
        $prefix = Yii::$app->request->input('prefix');
        $REs = ConfigService::tieradds($nums, $type_id, $hotel_id, $prefix);
        return ResultHelper::json(200, '添加成功', $REs);
    }
    public function actionDel(): array
    {
        try {
            $REs = ConfigService::del(Yii::$app->request->input('id'));
        } catch (StaleObjectException|\Throwable $e) {
            return ResultHelper::json(400, $e->getMessage());
        }
        return ResultHelper::json(200, '删除成功', $REs);
    }
}
