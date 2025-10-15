<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:39
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-20 15:04:38
 */


namespace common\plugins\diandi_auth\models;

use common\traits\ActiveQuery\StoreTrait;
use Yii;

/**
 * This is the model class for table "{{%diandi_auth_permission}}".
 *
 * @property int $id
 * @property int|null $bloc_id 业务中心
 * @property int|null $store_id 门店
 * @property string|null $name 权限名称
 * @property string|null $tag 权限标识
 * @property string|null $desc 权限描述
 * @property string|null $addons 插件名称
 * @property int|null $pid 父级权限
 * @property string|null $page_name 页面名称
 * @property string|null $create_time 创建时间
 * @property string|null $update_time 更新时间
 */
class MemberPermission extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_auth_permission}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'pid','nav_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['name', 'tag', 'desc', 'addons', 'page_name'], 'string', 'max' => 50],
        ];
    }

    function getNav()
    {
        return $this->hasOne(BlocConfAppNav::class, ['id' => 'nav_id']);
    }

    function getChildren()
    {
        return $this->hasMany(MemberPermission::class, ['pid' => 'id']);
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
            'bloc_id' => '业务中心',
            'store_id' => '门店',
            'name' => '权限名称',
            'tag' => '权限标识',
            'desc' => '权限描述',
            'addons' => '插件名称',
            'pid' => '父级权限',
            'page_name' => '页面名称',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }
}
