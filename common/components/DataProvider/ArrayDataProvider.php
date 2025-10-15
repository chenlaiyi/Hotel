<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-11-02 16:21:27
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2020-11-02 17:23:27
 */
 
namespace common\components\DataProvider;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class ArrayDataProvider extends \yii\data\ArrayDataProvider
{
    public int $total = 0;

    public $sql = '';

    /*
     *  @inheritdoc
     */
    protected function prepareModels(): array
    {
        if (($models = $this->allModels) === null) {
            return [];
        }

        if (($sort = $this->getSort()) !== false) {
//            $models = $this->sortModels($models, $sort);
        }

        if (($pagination = $this->getPagination()) !== false) {
            $pagination->totalCount = $this->getTotalCount();
        }
        $key = $this->key;
        array_walk($models, function (&$model) use ($key) {
                $model[$key] = (string) $model[$key];
        });

        return $models;
    }

    /*
     *       @inheritdoc
     */
    protected function prepareTotalCount(): int
    {
        return $this->getTotalCount();
    }

    public function toArray(): array
    {
        $models = $this->prepareModels();
        $arr = [
            'allModels' => $models,
            'total' => $this->total,
        ];
        if (YII_DEBUG){
            $arr['sql'] = $this->sql;
        }
        return $arr;
    }

}
