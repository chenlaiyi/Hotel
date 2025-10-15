<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-28 23:43:29
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-07-17 06:57:19
 */

namespace admin\controllers\website;

use admin\controllers\AController;
use common\helpers\ErrorsHelper;
use common\helpers\ImageHelper;
use common\helpers\ResultHelper;
use common\models\WebsiteStationGroup;
use common\services\common\WebsiteGroup;
use diandi\admin\acmodels\AuthItemChild;
use diandi\admin\acmodels\AuthRoute;
use diandi\admin\acmodels\AuthUserGroup;
use diandi\admin\components\Configs;
use diandi\admin\models\AuthItem;
use diandi\admin\models\Menu;
use Yii;
use yii\base\ErrorException;

/**
 * Class SiteController.
 */
class SettingController extends AController
{
    public $modelClass = '';

    protected array $authOptional = ['info', 'ceshi'];

    public int $searchLevel = 0;

    function actionCeshi(): array
    {
//        $config = Configs::instance();
//        /* @var $manager \yii\rbac\BaseManager */
//        $manager = $config::authManager();
//        $roles = AuthUserGroup::find()->select('item_id')->column();
//        $arr = [];
//        $list = $manager->getChildrenRoleArr($roles,$arr);
        /**
         * 查所有路由
         */
        $Routes = Menu::find()->asArray()->all();
        $list = [];
        foreach ($Routes as $route) {
            $item = \diandi\admin\acmodels\AuthItem::find()->where(['id'=> $route['item_id']])->exists();
            $parent_id = Menu::find()->where(['id'=>$route['parent']])->select('item_id')->scalar();

            if (empty($item)){
                $list[] = $route;
                $items = [
                    'name' => $route['name'],
                    'is_sys' => $route['is_sys'],
                    'permission_type' =>1,
                    'description' =>'',
                    'rule_name' =>0,
                    'menu_id' => $route['id'],
                    'parent_id' => (int) $parent_id,
                    'permission_level' =>1,    //权限级别:0: 目录1: 页面 2: 按钮 3: 接口
                    'module_name' =>$route['module_name'],
                ];
                $AcmodelsAuthItem = new \diandi\admin\acmodels\AuthItem();

                // 给item同步添加数据

                if ($AcmodelsAuthItem->load($items, '') && $AcmodelsAuthItem->save()) {
                    $item_id = $AcmodelsAuthItem->id;
                    Menu::updateAll([
                        'item_id' => $item_id
                    ],[
                        'id'=>$route['id']
                    ]);
                }
            }
        }
        print_r($list);
        //        print_r($list);
        //        修复child 的item_id
        //        $listChild =  AuthItemChild::find()->where(['child_type'=>0])->asArray()->all();
        //        foreach ($listChild as $item) {
        //            $item_id = AuthRoute::find()->where(['name'=> $item['child']])->select('item_id')->scalar();
        //            if ($item_id){
        //                AuthItemChild::updateAll([
        //                    'item_id'=> $item_id
        //                ],[
        //                    'id'=>$item['id']
        //                ]);
        //            }else{
        //                AuthItemChild::deleteAll(['id'=>$item['id']]);
        //            }
        //        }

        //                修复路由的item_id
        //        $listRoutes = AuthRoute::find()->asArray()->all();
        //
        //        $AuthItem = new \diandi\admin\acmodels\AuthItem();
        //        foreach ($listRoutes as $listRoute) {
        //            $item_id = $AuthItem::find()->where(['name'=> $listRoute['name']])->select('id')->scalar();
        //            AuthRoute::updateAll([
        //                'item_id'=> $item_id
        //            ],[
        //                'id'=>$listRoute['id']
        //            ]);
        //        }
        return ResultHelper::json(200, 'ok', $arr);
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'website' => [
                'class' => \yii2mod\settings\actions\SettingsAction::class,
                'view' => 'website',
                'successMessage' => '保存成功',
                // also you can use events as follows:
                'on beforeSave' => function ($event) {
                    // your custom code
                },
                'on afterSave' => function ($event) {
                    // your custom code
                },
                'modelClass' => \common\models\forms\Website::class,
            ],
        ];
    }

    public function actionConfig(): array
    {
        $settings = Yii::$app->settings;
        $settings->invalidateCache(); // 清除缓存
        foreach (Yii::$app->request->input('Website') as $key => $value) {
            if ($value) {
                $settings->set('Website', $key, $value);
            } else {
                $settings->remove('Website', $key);
            }
        }

        $info = $settings->getAllBySection('Website');

        return ResultHelper::json(200, '设置成功', $info);
    }

    public function actionInfo(): array
    {
        $list  = WebsiteGroup::getWebsiteInfo();
        return ResultHelper::json(200, '获取成功', $list);
    }
}
