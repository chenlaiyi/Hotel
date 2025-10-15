<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-07-15 20:04:33
 */


namespace common\components\ActiveRecord;

use common\helpers\ErrorsHelper;
use yii\db\ActiveRecord;

class YiiActiveRecord extends ActiveRecord
{
    /**
     * 查询记录，不存在则创建
     * @param array $conditions 查询条件
     * @param array $attributes 新增时的属性值
     * @return ActiveRecord
     */
    public static function getOrCreateOne($conditions, $attributes = [])
    {
        $model = static::findOne($conditions);

        if (!$model) {
            $model = new static();
            $model->setAttributes(array_merge($conditions, $attributes));

            if (!$model->save()) {
                $msg = ErrorsHelper::getModelError($model);
                throw new \Exception('创建记录失败：' . $msg);
            }
        }

        return $model;
    }
}
