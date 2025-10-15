<?php

namespace common\modules\officialaccount\models;

use common\behaviors\SaveBehavior;
use common\traits\ActiveQuery\StoreTrait;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;

/**
 * This is the model class for table "{{%officialaccount_wechat_menu}}".
 *
 * @property int $id 粉丝id
 * @property int|null $bloc_id
 * @property int|null $store_id
 * @property string|null $update_time 更新时间
 * @property string|null $create_time
 * @property string|null $menuName 菜单名称
 * @property int|null $parentId 父级id
 * @property int|null $menuLevel 菜单等级
 * @property int|null $msgType 消息类型
 * @property int|null $menuType 菜单类型
 * @property string|null $menuUrl 菜单URL
 * @property int|null $menuSort 菜单排序
 * @property string|null $appid 小程序appid
 * @property string|null $pagepath 小程序页面路径
 * @property int|null $media_id 素材ID
 */
class OfficialaccountWechatMenu extends ActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%officialaccount_wechat_menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'parentId', 'menuLevel', 'msgType', 'menuType', 'menuSort', 'media_id'], 'integer'],
            [['update_time', 'create_time'], 'safe'],
            [['menuName'], 'string', 'max' => 30],
            [['menuUrl'], 'string', 'max' => 255],
            [['appid'], 'string', 'max' => 50],
            [['pagepath'], 'string', 'max' => 100],
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
                'class' => SaveBehavior::class,
                'updatedAttribute' => 'update_time',
                'createdAttribute' => 'create_time',
                'time_type' => 'datetime',
            ],
        ];
    }

    function getChild()
    {
        return $this->hasMany(self::class,['parentId'=>'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => '粉丝id',
            'bloc_id' => 'Bloc ID',
            'store_id' => 'Store ID',
            'update_time' => '更新时间',
            'create_time' => 'Create Time',
            'menuName' => '菜单名称',
            'parentId' => '父级id',
            'menuLevel' => '菜单等级',
            'msgType' => '消息类型',
            'menuType' => '菜单类型',
            'menuUrl' => '菜单URL',
            'menuSort' => '菜单排序',
            'appid' => '小程序appid',
            'pagepath' => '小程序页面路径',
            'media_id' => '素材ID',
        ];
    }
}