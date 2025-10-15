<?php
namespace common\plugins\diandi_senangpay\api;

use Yii;
use api\controllers\AController;
use common\helpers\ResultHelper;


class ApiController extends AController
{
    public $modelClass = '';

  

    public function actionIndex(): array
   {

        $data = Yii::$app->request->input();
        $access_token = $data['access_token'];
        $data['user_id'] = Yii::$app->user->identity->member_id;
        $res = [];

        return ResultHelper::json(200, '请求成功', $res);
    }

}
