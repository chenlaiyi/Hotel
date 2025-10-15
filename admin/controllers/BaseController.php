<?php

namespace admin\controllers;

use common\helpers\ResultHelper;
use diandi\addons\models\form\App;
use Yii;

class BaseController extends AController
{
    protected array $authOptional = ['app-config'];

    public function actionAppConfig()
    {
        $model = new App();
        $bloc_id = Yii::$app->request->input('bloc_id', 0);

        if ($bloc_id) {
            $model->getConf($bloc_id);
            return ResultHelper::json(200, '获取成功', $model->toArray() ?? []);
        } else {
            return ResultHelper::json(400, '缺少 bloc_id 参数');
        }
    }
}