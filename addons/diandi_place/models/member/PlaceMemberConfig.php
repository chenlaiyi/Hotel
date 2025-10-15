<?php
/**
 * @Author: YuH  email:1186751716@qq.com
 * @Date:   2023-05-22 16:37:11
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-25 09:23:45
 */
namespace addons\diandi_place\models\member;
use common\traits\ActiveQuery\StoreTrait;
/**
 * This is the model class for table "{{%diandi_place_member_config}}".
 *
 * @public int $id
 * @public int|null $bloc_id 公司ID
 * @public int|null $store_id 商户ID
 * @public int|null $member_id 会员ID
 * @public int|null $is_open 房源是否公开
 * @public int|null $lead_time 提前授权时间
 * @public   int|null $delay_time 延迟授权时间
 * @public int|null $maintain_time 维护时间
 * @public string|null $create_time 创建时间
 * @public string|null $update_time 更新时间
 */
class PlaceMemberConfig extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_user_config}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id','user_id'], 'integer'],
            [['lead_time', 'delay_time', 'maintain_time', 'electrovalence'], 'number'],
            [['is_open'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
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
                'class'            => \common\behaviors\SaveBehavior::class,
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
            'id'            => 'ID',
            'bloc_id'       => '公司ID',
            'store_id'      => '商户ID',
            'member_id'     => '会员ID',
            'is_open'       => '房源是否公开',
            'lead_time'     => '提前授权时间',
            'delay_time'    => '延迟授权时间',
            'maintain_time' => '维护时间',
            'create_time'   => '创建时间',
            'update_time'   => '更新时间',
        ];
    }
}
