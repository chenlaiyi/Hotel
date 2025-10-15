<?php

namespace common\plugins\diandi_auth\admin\cloud;

use admin\models\enums\AddonsType;
use admin\services\UserService;
use common\helpers\ImageHelper;
use common\plugins\diandi_auth\models\cloud\DiandiAuthAddonsCate;
use common\plugins\diandi_auth\models\searchs\cloud\DiandiAuthAddonsCate as DiandiAuthAddonsCateSearch;
use admin\controllers\AController;
use common\helpers\ErrorsHelper;
use common\helpers\ResultHelper;
use diandi\addons\models\AddonsUser;
use diandi\addons\models\BlocStore;
use diandi\addons\models\DdAddons;
use Throwable;
use Yii;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

/**
 * AddonsCateController implements the CRUD actions for DiandiAuthAddonsCate model.
 */
class AddonsCateController extends AController
{
    public string $modelSearchName = "DiandiAuthAddonsCateSearch";

    public $modelClass = '';


    /**
     * Lists all DiandiAuthAddonsCate models.
     * @return array
     */
    public function actionIndex(): array
    {
        $searchModel = new DiandiAuthAddonsCateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return ResultHelper::json(200, '获取成功', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelSearchName' => $this->modelSearchName,
            'field' => $searchModel->attributeLabels()
        ]);
    }

    /**
     * Displays a single DiandiAuthAddonsCate model.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id): array
    {
        $view = $this->findModel($id);

        $view = DiandiAuthAddonsCate::find()->where(['id' => $id])->asArray()->one();
        if ($view['identifies']){
            $view['identifies'] = explode(',', $view['identifies']);
        }
        return ResultHelper::json(200, '获取成功', $view);
    }

    /**
     * Creates a new DiandiAuthAddonsCate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionCreate(): array
    {
        $model = new DiandiAuthAddonsCate();
        $data = Yii::$app->request->post();
        if (is_array($data['identifies'])){
            $data['identifies'] = implode(',', $data['identifies']);
        }
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '创建成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }

    /**
     * Updates an existing DiandiAuthAddonsCate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id): array
    {
        $model = $this->findModel($id);
        $data = Yii::$app->request->post();
        if (is_array($data['identifies'])){
            $data['identifies'] = implode(',', $data['identifies']);
        }
        if ($model->load($data, '') && $model->save()) {
            return ResultHelper::json(200, '编辑成功', $model->toArray());
        } else {
            $msg = ErrorsHelper::getModelError($model);
            return ResultHelper::json(400, $msg);
        }
    }

    /**
     * Deletes an existing WeihExhibitionServiceProvider model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id): array
    {
        $this->findModel($id)->delete();

        return ResultHelper::json(200, '删除成功');
    }

    public function actionList(): array
    {
        $AddonsUser = new AddonsUser();

        $list = $AddonsUser->find()->alias('a')->joinWith('addons as s')->where([
            'a.user_id' => Yii::$app->user->id,
            's.type' =>AddonsType::ADDONS
        ])->orderBy(['s.displayorder'=>SORT_ASC])->asArray()->all();

        $lists = [];
        foreach ($list as $item) {
            if ($item['addons']){
                $identifie = $item['addons']['identifie'];
                $lists[$identifie] = $item['addons'];
            }
        }
        $list = DiandiAuthAddonsCate::find()->select(['id as value','name' ,'name as label','name as text','identifies','thumb'])->asArray()->all();
        array_walk($list,function(&$item) use ($lists){
            $item['thumb'] = ImageHelper::tomedia($item['thumb']);
            $identifies = explode(',',(string)$item['identifies']);
            $item['addons'] = [];
            foreach ($identifies as $identify){
                if (isset($lists[$identify])){
                    $item['addons'][] = $lists[$identify];
                }
            }
        });
        return ResultHelper::json(200, '获取成功', $list);
    }

    function actionAddons()
    {
        $user_id = Yii::$app->user->id;
        $store_id = Yii::$app->request->input('dd_store_id');
        $category_id = BlocStore::find()->where(['store_id' => $store_id])->select('category_id')->scalar();
        $category_id = $category_id?:DiandiAuthAddonsCate::find()->min('id');
        $cate  = DiandiAuthAddonsCate::find()->where(['id' => $category_id])->select(['identifies','name'])->asArray()->one();
        $identifies = $cate['identifies'];
        $change_menu = $cate['name'];
        $identifies = explode(',', $identifies);
        $where = [];
        /**
         * 当前用户如果是总管理员，则显示所有插件
         */
        if (UserService::isSuperAdmin()) {
            $addons = DdAddons::find()->where([
                'type' =>AddonsType::ADDONS,
                'is_nav' =>1,
            ])->andWhere($where)->orderBy(['displayorder'=>SORT_ASC])->asArray()->all();
        }else{
            $where = ['in', 's.identifie', $identifies];
            $list = AddonsUser::find()->alias('a')->joinWith('addons as s')->where([
                'a.store_id' => $store_id,
                'a.user_id' => $user_id,
                's.type' =>AddonsType::ADDONS,
                's.is_nav' =>1,
            ])->andWhere($where)->orderBy(['s.displayorder'=>SORT_ASC])->asArray()->all();
            $addons = array_map(function ($item) {
                return $item['addons'];
            }, $list);
        }
        $bloc_id = BlocStore::find()->where(['store_id' => $store_id])->select('bloc_id')->scalar();

        return ResultHelper::json(200, '获取成功', [
            'store_id'=>(int) $store_id,
            'bloc_id'=>(int) $bloc_id,
            'category_id'=>(int) $category_id,
            'change_menu'=>$change_menu,
            'addons' => $addons
        ]);
    }

    /**
     * Finds the DiandiAuthAddonsCate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return array|ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): array|ActiveRecord
    {
        if (($model = DiandiAuthAddonsCate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}