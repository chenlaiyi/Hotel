<?php
namespace common\plugins\diandi_auth\models;

use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "dd_sections".
 *
 * @property int $id id
 * @property string|null $section 配置key
 * @property string|null $description 描述
 * @property int|null $disabled 可用状态 disabled:0 可用 disabled:1 不可用
 * @property string|null $back_up 备注
 * @property int|null $bloc_id 公司id
 * @property int|null $store_id 商户id
 * @property string|null $create_time
 * @property string|null $update_time
 */
class Sections extends \yii\db\ActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%setting_sections}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['disabled', 'bloc_id', 'store_id'], 'integer'],
            [['back_up'], 'string'],
            [['create_time', 'update_time', 'setting_key_list'], 'safe'],
            [['section', 'description'], 'string', 'max' => 255],
            [['section'], 'unique', 'message' => '配置key已存在'],
        ];
    }

    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                if ($this->setting_key_list && is_array($this->setting_key_list)) {
                    $this->setting_key_list = json_encode($this->setting_key_list);
                }
            }
            return true;
        }
        return false;
    }

    /**
     * 行为.
     */
    public function behaviors(): array
    {
        /*自动添加创建和修改时间*/
        return [
            [
                'class'            => \common\behaviors\SaveBehavior::className(),
                'updatedAttribute' => 'update_time',
                'createdAttribute' => 'create_time',
                'time_type'        => 'datetime',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'          => 'id',
            'section'     => '配置key',
            'description' => '描述',
            'disabled'    => '可用状态',
            'back_up'     => '备注',
            'bloc_id'     => '公司id',
            'store_id'    => '商户id',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
