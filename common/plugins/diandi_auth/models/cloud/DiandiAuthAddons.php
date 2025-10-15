<?php

namespace common\plugins\diandi_auth\models\cloud;

use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%diandi_auth_addons}}".
 *
 * @property int $id 模块id
 * @property int|null $mid 系统模块ID
 * @property int|null $is_nav 是否导航
 * @property string $identifie 英文标识
 * @property string|null $type 模块类型
 * @property string $title 名称
 * @property string $version 版本
 * @property string $ability 简介
 * @property string $description 描述
 * @property string $author 作者
 * @property string $url 社区地址
 * @property int $settings 配置
 * @property string $logo logo
 * @property string|null $versions 适应的软件版本
 * @property int|null $is_install
 * @property string|null $parent_mids
 * @property int $cate_id 分类ID
 * @property string $applets 小程序二维码
 */
class DiandiAuthAddons extends \yii\db\ActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_auth_addons}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'mid', 'is_nav', 'settings', 'is_install', 'cate_id'], 'integer'],
            [['identifie', 'title', 'version', 'ability', 'description', 'author', 'url', 'logo', 'cate_id'], 'required'],
            [['identifie', 'title'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 30],
            [['version'], 'string', 'max' => 15],
            [['ability'], 'string', 'max' => 500],
            [['description'], 'string', 'max' => 1000],
            [['author', 'versions'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 255],
            [['logo', 'parent_mids'], 'string', 'max' => 250],
            [['applets'], 'string', 'max' => 180],
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
            'id' => '模块id',
            'mid' => '系统模块ID',
            'is_nav' => '是否导航',
            'identifie' => '英文标识',
            'type' => '模块类型',
            'title' => '名称',
            'version' => '版本',
            'ability' => '简介',
            'description' => '描述',
            'author' => '作者',
            'url' => '社区地址',
            'settings' => '配置',
            'logo' => 'logo',
            'versions' => '适应的软件版本',
            'is_install' => 'Is Install',
            'parent_mids' => 'Parent Mids',
            'cate_id' => '分类ID',
            'applets' => '小程序二维码',
        ];
    }
}