<?php

namespace common\plugins\diandi_auth\models;

use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "dd_setting".
 *
 * @property int $id
 * @property string|null $cate_name
 * @property string $type
 * @property string $section
 * @property string $key
 * @property int|null $store_id
 * @property int|null $bloc_id
 * @property string $value
 * @property int $status
 * @property string|null $description
 * @property int $created_at
 * @property int $updated_at
 */
class Setting extends \yii\db\ActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'dd_setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['type', 'section', 'key', 'value'], 'required'],
            [['store_id', 'bloc_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['value'], 'string'],
            [['cate_name', 'section', 'key', 'description'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 10],
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
                'updatedAttribute' => 'updated_at',
                'createdAttribute' => 'created_at',
                'time_type' => 'init',
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
            'cate_name' => 'Cate Name',
            'type' => 'Type',
            'section' => 'Section',
            'key' => 'Key',
            'store_id' => 'Store ID',
            'bloc_id' => 'Bloc ID',
            'value' => 'Value',
            'status' => 'Status',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}