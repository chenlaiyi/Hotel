<?php

namespace common\rbac;

use admin\models\addons\models\Bloc;
use common\helpers\loggingHelper;
use Yii;
use yii\rbac\Rule;

/**
 * 用户套餐规则
 */
class MealRule extends Rule
{
    public $name = 'MealRule';

    public function execute($user, $item, $params): bool
    {

        /**
         * 校验公司有效期和状态
         */
        $bloc_id = Yii::$app->request->input('bloc_id',0);
        $bloc = Yii::$app->request->input('bloc',[]);
        if (empty($bloc_id) && !empty($bloc)){
            $bloc_id = $bloc[0];
        }
        $bloc = Bloc::find()->where(['bloc_id'=>$bloc_id])->select(['open_time','end_time','status','business_name'])->asArray()->one();
        loggingHelper::writeLog('MealRule','execute','公司有效期核验',[
            'bloc_id'=>$bloc_id,
            'bloc'=>$bloc,
        ]);

        return true;
        return isset($params['post']) ? $params['post']->createdBy == $user : false;
    }
}