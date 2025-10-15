<?php

namespace common\plugins\diandi_auth\models;

use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%bloc_conf_app_nav}}".
 *
 * @property int $id
 * @property int|null $bloc_id 公司
 * @property string $text 描述
 * @property string $tab_name 导航名称
 * @property string $page_path 导航路径
 * @property string $icon_path 未选中图片
 * @property string $selected_icon_path 选中图片
 * @property int|null $sort_order 排序
 * @property int|null $status 状态
 * @property string|null $create_time
 * @property string|null $update_time
 */
class BlocConfAppNav extends \yii\db\ActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%bloc_conf_app_nav}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'sort_order','store_id', 'status','is_guest','nav_remark'], 'integer'],
            [['text', 'tab_name', 'page_path', 'icon_path', 'selected_icon_path'], 'required'],
            [['create_time', 'update_time'], 'safe'],
            [['text', 'tab_name', 'page_path', 'icon_path', 'selected_icon_path'], 'string', 'max' => 255],
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

    function getPath()
    {
        return $this->hasOne(AuthMemberPermission::class, ['id' => 'page_path']);
    }

    public function getAuth()
    {
        return $this->hasMany(MemberPermission::class, ['nav_id' => 'nav_remark']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'bloc_id' => '公司',
            'text' => '描述',
            'tab_name' => '导航名称',
            'page_path' => '导航路径',
            'icon_path' => '未选中图片',
            'selected_icon_path' => '选中图片',
            'sort_order' => '排序',
            'status' => '状态',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }
}