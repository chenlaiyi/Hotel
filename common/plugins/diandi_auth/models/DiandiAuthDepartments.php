<?php

namespace common\plugins\diandi_auth\models;

use common\traits\ActiveQuery\StoreTrait;
use diandi\addons\components\Store;
use diandi\addons\models\BlocStore;

/**
 * This is the model class for table "{{%diandi_auth_departments}}".
 *
 * @property int $id
 * @property string $department_name 部门名称
 * @property string|null $department_head 部门负责人
 * @property string|null $remarks 备注
 * @property string|null $status 状态
 * @property string|null $create_time
 * @property string|null $update_time
 * @property int|null $bloc_id
 * @property int|null $store_id
 */
class DiandiAuthDepartments extends \yii\db\ActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_auth_departments}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['department_name','department_pid'], 'required'],
            [['remarks', 'status'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['bloc_id', 'store_id'], 'integer'],
            [['department_name', 'department_head'], 'string', 'max' => 100]
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

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'department_name' => '部门名称',
            'department_head' => '部门负责人',
            'remarks' => '备注',
            'status' => '状态',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'bloc_id' => 'Bloc ID',
            'store_id' => 'Store ID',
        ];
    }

    public function getStore()
    {
        return $this->hasOne(BlocStore::class, ['store_id' => 'store_id']);
    }
}