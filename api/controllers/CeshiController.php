<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-19 13:29:33
 */


namespace api\controllers;

use addons\diandi_business_opportunity\models\searchs\DiandiBusinessContact;
use admin\models\auth\AuthRoute;
use common\helpers\ResultHelper;
use common\models\UserStore;
use diandi\admin\acmodels\AuthItem;
use diandi\admin\acmodels\AuthItemChild;
use diandi\admin\acmodels\AuthUserGroup;

class CeshiController extends AController
{
    protected array $authOptional = ['index'];

    public function actionIndex(): array
    {
        $department_ids = UserStore::find()->where(['user_id' => \Yii::$app->user->identity->user_id ?? 0])->select('department_id')->groupBy('department_id')->column();

        $list = DiandiBusinessContact::find()->findDepartments()->all();
        return ResultHelper::json(200, 'success',$list);
    }
}
