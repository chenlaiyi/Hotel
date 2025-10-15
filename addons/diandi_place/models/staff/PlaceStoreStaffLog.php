<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-02-15 15:06:27
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-03-14 18:29:02
 */
namespace addons\diandi_place\models\staff;
use common\traits\ActiveQuery\StoreTrait;
use diandi\addons\models\BlocStore;
use Yii;
/**
 * This is the model class for table "{{%bea_cloud_store_staff_log}}".
 *
 * @public int $id
 * @public int|null $store_id
 * @public int|null $bloc_id
 * @public string|null $create_time
 * @public string|null $update_time
 * @public int|null $member_id 会员ID
 * @public string|null $staff_code 员工编码
 * @public string|null $mobile 手机号
 * @public int|null $status 状态
 * @public int|null $old_store_id 原门店
 *
 * @property-read mixed $staff
 * @property-read mixed $oldStore
 */
class PlaceStoreStaffLog extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bea_cloud_store_staff_log}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['store_id', 'bloc_id', 'member_id', 'status', 'old_store_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['staff_code', 'mobile'], 'string', 'max' => 50]
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
                'time_type'=>'datetime'
            ],
        ];
    }
    public function getStore()
    {
        return $this->hasOne(BlocStore::class,['store_id'=>'store_id']);
    }
    public function getOldStore()
    {
        return $this->hasOne(BlocStore::class,['store_id'=>'old_store_id']);
    }
    public function getStaff()
    {
        return $this->hasOne(BeaStoreStaff::class,['member_id'=>'member_id']);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'bloc_id' => 'Bloc ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'member_id' => 'Member ID',
            'staff_code' => 'Staff Code',
            'mobile' => 'Mobile',
            'status' => 'Status',
            'old_store_id' => 'Old Store ID',
        ];
    }
}
