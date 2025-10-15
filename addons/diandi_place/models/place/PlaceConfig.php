<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-05 11:03:49
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-07-21 14:16:34
 */
namespace addons\diandi_place\models\place;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
/**
 * 酒店配置
 * This is the model class for table "{{%diandi_place_global_config}}".
 *
 * @public int $id ID
 * @public int $bloc_id 公司ID
 * @public int $store_id
 * @public string|null $create_time
 * @public string|null $update_time
 * @public string $mumber_scale 会员积分比例
 * @public string $vip_scale vip积分比例
 * @public string|null $store_introduce 商户简介
 * @public string|null $admin_ids 管理员
 * @public string|null $sms_order_template 短信订单通知模板
 * @public string|null $sms_order_sign 短信订单通知签名
 * @public string|null $sms_mobiles 短信通知手机号，逗号隔开
 * @public string|null $order_create_template 订单下单小程序订阅模板
 * @public string|null $order_end_template 订单到期小程序订阅模板
 * @public string|null $recharge_template 充值成功通知模板
 * @public string|null $renew_template 续费通知模板
 */
class PlaceConfig extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%diandi_place_global_config}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['mumber_scale', 'vip_scale'], 'default', 'value' => 0],
            [['bloc_id', 'store_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['mumber_scale', 'vip_scale','maintain_time','befter_time','after_time'],'number'],
            [['store_introduce'], 'string'],
            [[ 'sms_mobiles', 'index_thumb'], 'string', 'max' => 255],
            [['admin_ids', 'sms_order_template', 'order_create_template', 'order_end_template', 'recharge_template', 'renew_template'], 'string', 'max' => 100],
            [['sms_order_sign'], 'string', 'max' => 30],
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
                'class' => \common\behaviors\SaveBehavior::class,
                'updatedAttribute' => 'update_time',
                'createdAttribute' => 'create_time',
                'time_type' => 'datetime'
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('place', 'ID'),
            'bloc_id' => Yii::t('place', '公司ID'),
            'store_id' => Yii::t('place', 'Store ID'),
            'create_time' => Yii::t('place', 'Create Time'),
            'update_time' => Yii::t('place', 'Update Time'),
            'mumber_scale' => Yii::t('place', '会员积分比例'),
            'vip_scale' => Yii::t('place', 'vip积分比例'),
            'store_introduce' => Yii::t('place', '商户简介'),
            'admin_ids' => Yii::t('place', '管理员'),
            'sms_order_template' => Yii::t('place', '短信订单通知模板'),
            'sms_order_sign' => Yii::t('place', '短信订单通知签名'),
            'sms_mobiles' => Yii::t('place', '短信通知手机号，逗号隔开'),
            'order_create_template' => Yii::t('place', '订单下单小程序订阅模板'),
            'order_end_template' => Yii::t('place', '订单到期小程序订阅模板'),
            'recharge_template' => Yii::t('place', '充值成功通知模板'),
            'renew_template' => Yii::t('place', '续费通知模板'),
        ];
    }
}
