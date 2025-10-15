<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:37:34
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-08-21 15:20:06
 */
namespace addons\diandi_place\admin\manage\place;
use addons\diandi_place\models\enums\HotelRoomTypeEnums;
use addons\diandi_place\services\PlaceService;
use addons\diandi_place\Traits\LandlordTrait;
use admin\controllers\AController;
use common\helpers\ResultHelper;
use Yii;
use yii\db\StaleObjectException;
/**
 * 酒店管理
 * @date 2023-04-23
 * @example
 * @author Wang Chunsheng
 * @since
 */
class TypeController extends AController
{
    use LandlordTrait;
    public string $modelSearchName = 'PlaceCountry';
    public function actionAdd(): ?array
    {
        $title = Yii::$app->request->input('title');         //酒店名称
        $template_type = Yii::$app->request->input('template_type'); //酒店名称
        $is_default = Yii::$app->request->input('is_default');
        $REs = PlaceService::addType($title, $template_type, $is_default);
        return ResultHelper::json(200, '添加成功', $REs);
    }
    public function actionEdit(): ?array
    {
        $id = Yii::$app->request->input('id');            //酒店名称
        $title = Yii::$app->request->input('title');         //酒店名称
        $template_type = Yii::$app->request->input('template_type'); //酒店名称
        $is_default = Yii::$app->request->input('is_default');
        $REs = PlaceService::editType($id, $title, $template_type, $is_default);
        return ResultHelper::json(200, '编辑成功', $REs);
    }
    public function actionDel(): ?array
    {
        $REs = PlaceService::delType(Yii::$app->request->input('id'));
        return ResultHelper::json(200, '删除成功', $REs);
    }
    /**
     * 房源类型+列表
     * @return array|null
     */
    public function actionList(): ?array
    {
        $page = Yii::$app->request->input('page') ?? 1;
        $pageSize = Yii::$app->request->input('pageSize') ?? 10;
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        // 查询不同类型的楼栋
        $list = PlaceService::hotelByType($user_id,$page, $pageSize);
        // 全局的房型
//        $RoomType = HotelRoomTypeEnums::listData();
        return ResultHelper::json(200, '获取成功', $list);
    }

    /**
     * 设置默认房源类型
     * @throws \Throwable
     */
    public function actionSetDefault(): ?array
    {
        $id = Yii::$app->request->input('default_id');
        $REs = PlaceService::hotelByTypeDefault($id);
        return ResultHelper::json(200, '设置成功', $REs);
    }
    function allowAction(): array
    {
        return ['*'];
    }
}
