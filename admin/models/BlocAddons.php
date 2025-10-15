<?php

namespace admin\models;

use common\traits\ActiveQuery\StoreTrait;
use diandi\addons\models\DdAddons;

/**
 * This is the model class for table "{{%bloc_addons}}".
 *
 * @property int $id
 * @property int|null $store_id 默认商户
 * @property int|null $type 用户类型
 * @property string|null $module_name 所属模块
 * @property int|null $bloc_id 公司
 * @property int|null $is_default 是否默认
 * @property int|null $status 审核状态
 * @property string|null $create_time
 * @property string|null $update_time
 */
class BlocAddons extends \yii\db\ActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%bloc_addons}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['store_id', 'type', 'bloc_id', 'is_default', 'status'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['module_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * 行为.
     */
    public function behaviors(): array
    {
        /*自动添加创建和修改时间*/
        return [
            [
                'class' => \common\behaviors\SaveBehavior::className(),
                'updatedAttribute' => 'update_time',
                'createdAttribute' => 'create_time',
                'time_type' => 'datetime',
            ],
        ];
    }

    function getAddons()
    {
        return $this->hasOne(DdAddons::class, ['identifie' => 'module_name']);
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'store_id' => '默认商户',
            'type' => '用户类型',
            'module_name' => '所属模块',
            'bloc_id' => '公司',
            'is_default' => '是否默认',
            'status' => '审核状态',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}