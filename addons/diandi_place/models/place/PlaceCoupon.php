<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-31 17:01:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-09 10:21:30
 */
namespace addons\diandi_place\models\place;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * This is the model class for table "{{%diandi_place_coupon}}".
 *
 * @public int $id 卡券id
 * @public int|null $bloc_id 人脸库组id
 * @public int|null $store_id
 * @public string|null $create_time
 * @public string|null $update_time
 * @public string $name 卡券名称
 * @public string|null $explain 卡券说明
 * @public int|null $type 卡券类型  1：代金券 2：时长卡  3：次卡 4：折扣券 5：体验券
 * @public float|null $price 卡券价格
 * @public string|null $use_start 时间限制-开始时间
 * @public string|null $use_end 时间限制-结束时间
 * @public string|null $enable_start 有效期开始时间
 * @public string|null $enable_end 有效期结束时间
 * @public int|null $use_num 已使用数量
 * @public string|null $max_time 消费时长
 * @public string|null $enable_store 适用店铺
 * @public string|null $enable_week 适用星期(分别对应1~7）
 * @public string|null $third_party 第三方编号
 * @public int|null $all_num 总发放量
 * @public int|null $max_num 最多可购买数量
 * @public string|null $background 卡券背景图
 * @public float|null $cash 代金券金额
 * @public float|null $discount 折扣券折扣
 * @public string|null $coupon_img 卡券图片
 * @public string|null $use_hourse 使用房间
 * @public int|null $num_sort 排序
 * @public int|null $meal_type 默认套餐类型
 * @public int|null $hotel_id 酒店id
 * @public int|null $room_id 房间/单位id
 */
class PlaceCoupon extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%diandi_place_coupon}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'type', 'use_num', 'all_num', 'max_num', 'num_sort', 'meal_type', 'hotel_id', 'room_id'], 'integer'],
            [['create_time', 'update_time', 'use_start', 'use_end', 'enable_start', 'enable_end'], 'safe'],
            [['name'], 'required'],
            [['price', 'cash', 'discount', 'min_order_price'], 'number'],
            [['name', 'max_time', 'enable_store', 'third_party'], 'string', 'max' => 100],
            [['explain', 'enable_week', 'background', 'coupon_img', 'use_hourse'], 'string', 'max' => 255],
        ];
    }
    /**
     * 行为.
     */
    public function behaviors()
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
    public function attributeLabels()
    {
        return [
            'id'           => '卡券id',
            'bloc_id'      => '人脸库组id',
            'store_id'     => 'Store ID',
            'create_time'  => 'Create Time',
            'update_time'  => 'Update Time',
            'name'         => '卡券名称',
            'explain'      => '卡券说明',
            'type'         => '卡券类型  1：代金券 2：时长卡  3：次卡 4：折扣券 5：体验券',
            'price'        => '卡券价格',
            'use_start'    => '时间限制-开始时间',
            'use_end'      => '时间限制-结束时间',
            'enable_start' => '有效期开始时间',
            'enable_end'   => '有效期结束时间',
            'use_num'      => '已使用数量',
            'max_time'     => '消费时长',
            'enable_store' => '适用店铺',
            'enable_week'  => '适用星期(分别对应1~7）',
            'third_party'  => '第三方编号',
            'all_num'      => '总发放量',
            'max_num'      => '最多可购买数量',
            'background'   => '卡券背景图',
            'cash'         => '代金券金额',
            'discount'     => '折扣券折扣',
            'coupon_img'   => '卡券图片',
            'use_hourse'   => '使用房间',
            'num_sort'     => '排序',
            'meal_type'    => '默认套餐类型',
            'hotel_id'     => '酒店id',
            'room_id'      => '房间/单位id',
        ];
    }
}
