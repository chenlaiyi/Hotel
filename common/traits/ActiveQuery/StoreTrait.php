<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-04-22 14:40:19
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-19 13:47:23
 */

namespace common\traits\ActiveQuery;

use admin\models\addons\models\Bloc;
use common\helpers\ArrayHelper;
use diandi\addons\models\BlocStore;
use Yii;

trait StoreTrait
{
    use ExpandListTrait{
        ExpandListTrait::afterFind as afterFindExpandList;
    }

    public array $blocs = [];

    public array $bloc = [];

    public array $store = [];



    /**
     * find查询扩展.
     *
     * @return CommonQuery
     * @date 2023-06-19
     *
     * @example
     *
     * @author Wang Chunsheng
     *
     * @since
     */
    public static function find(): CommonQuery
    {
        return new CommonQuery(get_called_class());
    }


    function getBloc()
    {
        return $this->hasOne(Bloc::class,['bloc_id'=>'bloc_id']);
    }

    function getStore()
    {
        return $this->hasOne(BlocStore::class,['store_id'=>'store_id']);
    }

    public function fields(): array
    {
        $fields = parent::fields();
        $fields['blocs'] = 'blocs';
        $fields['bloc']  = 'bloc';
        $fields['store'] = 'store';
        $fields['expandList'] = 'expandList';
        return $fields;
    }

    public function beforeSave($insert): bool
    {
        $blocs = Yii::$app->request->input('blocs');
        if (is_array($blocs) && count($blocs) === 2){
            $this->bloc_id = $blocs[0];
            $this->store_id = $blocs[1];
        }

        /**
         * 只绑定公司的数据会需要重新赋值
         */
        $bloc_id = Yii::$app->request->input('bloc_id');
        if ($bloc_id && !$this->bloc_id){
            $this->bloc_id = $bloc_id;
        }
        $store_id = Yii::$app->request->input('store_id');
        /**
         * 需要判断当store_id不是主键
         */
        if ($store_id && !$this->store_id && $this->primaryKey()[0] !== 'store_id'){
            $this->store_id = $store_id;
        }
        return parent::beforeSave($insert);
    }

    public function afterFind(): void
    {
        $store_id = $this->getAttribute('store_id');
        $bloc_id = $this->getAttribute('bloc_id');
        $store = BlocStore::find()->where(['store_id'=>$store_id])->asArray()->one();
        $this->store = $store??[];
        $bloc = Bloc::find()->where(['bloc_id'=>$bloc_id])->asArray()->one();
        $this->bloc = $bloc??[];
        /**
         * 雪花ID转字符串
         */
        $primaryKey = $this->primaryKey()[0];
        $this->{$primaryKey} = (string) $this->{$primaryKey};
        self::afterFindExpandList();
        parent::afterFind();
    }
}
