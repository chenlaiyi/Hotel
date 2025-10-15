<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-02-15 15:06:04
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-03-30 21:15:10
 */
namespace addons\diandi_place\models\staff;
use addons\bea_cloud\models\member\BeaMember;
use addons\bea_cloud\models\member\BeaTrack;
use addons\bea_cloud\models\order\BeaOrder;
use admin\models\addons\models\Bloc;
use common\traits\ActiveQuery\StoreTrait;
use diandi\addons\models\BlocStore;
use Yii;
use common\components\ActiveRecord\YiiActiveRecord as ActiveRecord;
/**
 * This is the model class for table "{{%bea_cloud_store_staff}}".
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
 *
 * @property-read mixed $beaMember
 * @property-read mixed $track
 * @property-read mixed $order
 */
class PlaceStoreStaff extends ActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bea_cloud_store_staff}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['store_id', 'bloc_id', 'member_id', 'status', 'is_boss'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['staff_code', 'mobile', 'name', 'password'], 'string', 'max' => 50],
            [['staff_code'], 'unique'],
            [['name'], 'classExists', "on" => "insert"]
        ];
    }
    /**
     * Validate class exists
     */
    public function classExists()
    {
        $bloc_id = $this->bloc_id;
        $isHave = $this->find()->where(['bloc_id' => $bloc_id, 'is_boss' => 1])->one();
        if ($isHave) {
            $this->addError('name', '该公司已存在管理员请解绑后添加');
        }
    }
    public function getStore()
    {
        return $this->hasOne(BlocStore::class, ['store_id' => 'store_id']);
    }
    public function getBloc()
    {
        return $this->hasOne(Bloc::class, ['bloc_id' => 'bloc_id']);
    }
    public function getTrack()
    {
        return $this->hasOne(BeaTrack::class, ['member_id' => 'member_id']);
    }
    public function getBeaMember()
    {
        return $this->hasOne(BeaMember::class, ['mobile' => 'mobile']);
    }
    public function getOrder()
    {
        return $this->hasOne(BeaOrder::class, ['member_id' => 'member_id']);
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
            'id' => 'ID',
            'store_id' => 'Store ID',
            'bloc_id' => 'Bloc ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'member_id' => 'Member ID',
            'staff_code' => 'Staff Code',
            'mobile' => 'Mobile',
            'status' => 'Status',
        ];
    }
}
