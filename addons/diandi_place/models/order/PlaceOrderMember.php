<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-11 15:12:36
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-12 16:05:32
 */
namespace addons\diandi_place\models\order;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * This is the model class for table "{{%diandi_hotel_order_member}}".
 *
 * @property int|mixed|null $status
 * @property mixed|null $order_id
 * @public int $id
 * @public int|null $bloc_id 公司ID
 * @public int|null $store_id 商户ID
 * @public int|null $order_id
 * @public int|null $member_id 会员ID
 * @public string|null $face_img 脸部照片
 * @public string|null $realname 真实姓名
 * @public string|null $mobile 手机号
 * @public int|null $status 用户状态
 * @public string|null $icard_code 身份证号码
 * @public string|null $icard_front 身份证正面
 * @public string|null $icard_back 身份证反面
 * @public int|null $is_vip 是否是会员
 * @public string|null $create_time 创建时间
 * @public string|null $update_time 更新时间
 */
class PlaceOrderMember extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%diandi_place_order_member}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bloc_id', 'store_id', 'room_id', 'member_id', 'status', 'is_vip', 'check_in', 'allow_add_key', 'notice','hotel_id', 'room_id'], 'integer'],
            [['create_time', 'update_time', 'start_time', 'end_time',], 'safe'],
            [['face_img', 'realname', 'mobile', 'icard_code', 'personGuid'], 'string', 'max' => 255],
            [['icard_front', 'icard_back'], 'string', 'max' => 200],
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
                'time_type'        => 'datetime'
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'bloc_id'       => '公司ID',
            'store_id'      => '商户ID',
            'order_id'      => 'Order ID',
            'member_id'     => '会员ID',
            'face_img'      => '脸部照片',
            'realname'      => '真实姓名',
            'mobile'        => '手机号',
            'status'        => '用户状态',
            'icard_code'    => '身份证号码',
            'icard_front'   => '身份证正面',
            'icard_back'    => '身份证反面',
            'is_vip'        => '是否是会员',
            'create_time'   => '创建时间',
            'update_time'   => '更新时间',
            'start_time'    => '开始时间',
            'end_time'      => '结束时间',
            'allow_add_key' => '是否允许添加钥匙',
            'notice'        => '是否短信通知',
        ];
    }
}
