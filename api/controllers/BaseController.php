<?php
namespace api\controllers;

use common\components\sign\Sign;
use common\components\sign\SignException;
use common\helpers\ResultHelper;
use diandi\addons\models\form\App;
use Yii;

class BaseController extends AController
{
    protected array $authOptional = ['sign', 'app-config'];

    /**
     * 签名测试
     * @return array
     * @throws SignException
     */
    public function actionSign():array
    {
        $Sign = new Sign();
        $body = $this->request->bodyParams;
        $signString = $Sign->getSign($body);
        return ResultHelper::json(200, '签名成功', [
            'sign'=>$signString,
            'body'=>$body
        ]);
    }

    public function actionAppConfig(){
        $model = new App();
        $bloc_id = Yii::$app->request->input('bloc_id',0);

        if($bloc_id){
            $model->getConf($bloc_id);

            return ResultHelper::json(200, '获取成功', $model->toArray() ?? []);
        }
        else{
            return ResultHelper::json(400, '缺少 bloc_id 参数');
        }
    }
}