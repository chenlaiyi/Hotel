<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-16 11:38:40
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-17 10:08:53
 */


namespace common\plugins\diandi_auth\api;


use api\controllers\AController;
use common\helpers\ImageHelper;
use common\helpers\ResultHelper;
use common\plugins\diandi_auth\models\BlocConfAppNav;
use Yii;

class AppController extends AController
{

    protected array $authOptional = ['nav'];

    /**
     * app导航
     */
    public function actionNav()
    {
        $isGuest = Yii::$app->user->isGuest;
        $where = [];
        if ($isGuest) {
            $where['is_guest'] = $isGuest;
        }
        $list = BlocConfAppNav::find()->where($where)->findBloc()->with(['path'])->asArray()->all();

        $lists = [];
        foreach ($list as $item) {
            $lists[] = [
                'text' => $item['text'],
                'tabName' => $item['tab_name'],
                'pagePath' => $item['path']['page_name'],
                'iconPath' => ImageHelper::tomedia($item['icon_path']),
                'selectedIconPath' => ImageHelper::tomedia($item['selected_icon_path']),
            ];
        }
        return ResultHelper::json(200, '请求成功', $lists);
    }
}
