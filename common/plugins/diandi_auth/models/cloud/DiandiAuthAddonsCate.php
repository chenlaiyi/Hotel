<?php

namespace common\plugins\diandi_auth\models\cloud;

use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%diandi_auth_addons_cate}}".
 *
 * @property int $id ID
 * @property string $name 分类名称
 * @property int $sort 排序值
 * @property string|null $identifies 插件标识集合
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class DiandiAuthAddonsCate extends \yii\db\ActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_auth_addons_cate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'identifies'], 'required'],
            [['bloc_id', 'store_id', 'sort','template_name','is_default','bus_type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name','path_name','search_path_name','remark'], 'string', 'max' => 45],
            [['identifies','thumb'], 'string', 'max' => 255],
        ];
    }

    public function afterFind(): void
    {
        $this->identifies = explode(',', $this->identifies);
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
            'name' => '分类名称',
            'sort' => '排序值',
            'identifies' => '插件标识集合',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}