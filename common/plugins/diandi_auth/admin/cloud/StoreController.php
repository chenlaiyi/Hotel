<?php

namespace common\plugins\diandi_auth\admin\cloud;

use admin\controllers\AController;
use common\helpers\ImageHelper;
use common\models\UserStore;
use diandi\addons\models\BlocStore;
use diandi\admin\models\AuthAssignmentGroup;
use Yii;

class StoreController extends AController
{
    public string $modelSearchName = "DiandiAuthStore";

    public $modelClass = '';

    public function actionList()
    {
        $user_id = Yii::$app->user->id;
        $group = AuthAssignmentGroup::find()->where(['user_id' => $user_id])->select('item_name')->column();
        $where = [];
        // 确定我的权限角色与系统默认有交集，name就显示所有集团
        if (!in_array(['总管理员'], $group)) {
            // 查找自己的数据
            $store_ids = UserStore::find()->where(['user_id' => $user_id])->select('store_id')->column();
            $where['store_id'] = $store_ids;
        }

        $stores = BlocStore::find()->where($where)->with(['bloc', 'addons'])->asArray()->all();
        foreach ($stores as &$value) {
            $value['create_time'] = date('Y-m-d', $value['create_time'] ?? time());
            $value['identifie'] = $value['addons'] ? $value['addons']['identifie'] : '';
            $value['logo'] = ImageHelper::tomedia($value['logo']);
            // if (empty($value['addons'])) {
            //     unset($stores[$key]);
            // }
        }
    }

}