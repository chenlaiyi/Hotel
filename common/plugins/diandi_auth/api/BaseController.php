<?php

namespace common\plugins\diandi_auth\api;


use admin\services\AuthService;
use common\helpers\ImageHelper;
use common\plugins\diandi_auth\models\cloud\DiandiAuthAddonsCate;
use Yii;
use api\controllers\AController;
use common\helpers\ResultHelper;
use yii\db\StaleObjectException;


class BaseController extends AController
{

    public $modelClass = '';

    protected array $authOptional = ['xfmid','xf-child','xf-menu-item','xf-menu','xf-bt'];

    public function actionYwCate(): array
    {
        $data = Yii::$app->request->input();

        $list = DiandiAuthAddonsCate::find()->asArray()->all();

        array_walk($list, function (&$value) {
           $value['thumb'] = ImageHelper::tomedia($value['thumb']);
        });

        return ResultHelper::json(200, '请求成功', $list);
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     */
    function actionXfmid()
    {
       $lists =  \diandi\admin\acmodels\AuthItem::find()->where(['>','parent_id' , 0])->asArray()->all();
        foreach ($lists as $list) {
            AuthService::upParentPermissionMenuId($list['id']);
        }
        return ResultHelper::json(200, '请求成功', []);
    }

    /**
     * 修复权限关系中在item中不存在的权限
     * @return array
     */
    function actionXfChild()
    {
        $all_item_ids = \diandi\admin\acmodels\AuthItem::find()->select('id')->column();
        /**
         * 查询AuthItemChild 中item_id不在all_item_ids中的数据
         */
        $child_item_ids = \diandi\admin\acmodels\AuthItemChild::find()->select('item_id')->where(['not in', 'item_id', $all_item_ids])->column();
        /**
         * 查询AuthItemChild 中parent_item_id不在all_item_ids中的数据
         */
        $parent_item_ids = \diandi\admin\acmodels\AuthItemChild::find()->select('parent_item_id')->where(['not in', 'parent_item_id', $all_item_ids])->column();
        /**
         * 给出以上2个的删除sql
         */
        $sql = "delete from diandi_admin_auth_item_child where item_id in (" . implode(',', array_unique($child_item_ids)) . ") or parent_item_id in (" . implode(',', array_unique($parent_item_ids)) . ")";
        return ResultHelper::json(200, '请求成功', [
            'sql' => $sql,
        ]);
    }


    /**
     * 修复菜单数据
     * @return array|object[]|string[]
     * @throws \yii\base\ErrorException
     */
    function actionXfMenu()
    {
        AuthService::menuRepair();

        return ResultHelper::json(200, '请求成功');

    }

    function actionXfMenuItem()
    {
        AuthService::xfMenuIitem();
        return ResultHelper::json(200, '请求成功');
    }

//xfItenmToMenu

    function actionXfBt()
    {
        AuthService::xfItenmToMenu();
        return ResultHelper::json(200, '请求成功');
    }


}

