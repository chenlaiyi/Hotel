<?php

namespace addons\diandi_place\models\place;

use common\traits\ActiveQuery\StoreTrait;
use Yii;

/**
* This is the model class for table "{{%diandi_place_customer}}".
*
    * @property int $id
    * @property int $customer_id 主键ID
    * @property int $bloc_id 公司ID
    * @property int|null $store_id 商户ID
    * @property string $customer_name 客商名称
    * @property string|null $contact_person 采购联系人
    * @property string|null $contact_number 联系方式
    * @property int|null $customer_type 客商类型
    * @property string|null $taxpayer_id 纳税人识别号
    * @property string|null $website 公司网址
    * @property string|null $phone 座机
    * @property string|null $fax 传真
    * @property string|null $address 地址
    * @property string|null $detailed_address 详细地址
    * @property float|null $longitude 经度
    * @property float|null $latitude 纬度
    * @property string|null $remark 备注
    * @property string|null $company_english_name 公司英文名
    * @property string|null $organization_code 组织机构代码
    * @property string|null $registration_number 注册号
    * @property string|null $company_type 公司类型
    * @property string|null $registration_authority 登记机关
    * @property string|null $legal_person 法人
    * @property string|null $legal_person_type 法人类型
    * @property string|null $enterprise_status 企业状态
    * @property string|null $approval_date 核准日期
    * @property string|null $establishment_date 成立时间
    * @property string|null $business_start_date 营业期限自
    * @property string|null $business_end_date 营业期限至
    * @property string|null $cancellation_date 注销日期
    * @property string|null $registered_capital 注册资金
    * @property string|null $business_scope 经营范围
    * @property int|null $industry 所属行业
    * @property string|null $tax_registration_number 税务登记号
    * @property string $create_time 创建时间
    * @property string $update_time 更新时间
    * @property int|null $province 省
    * @property int|null $city 市
    * @property int|null $district 区
    * @property int|null $status
*/
class DiandiPlaceCustomer extends \yii\db\ActiveRecord
{
use StoreTrait;

/**
* {@inheritdoc}
*/
public static function tableName(): string
{
return '{{%diandi_place_customer}}';
}

/**
* {@inheritdoc}
*/
public function rules(): array
{
    return [
            [['customer_id', 'bloc_id', 'customer_name', 'create_time', 'update_time'], 'required'],
            [['customer_id', 'bloc_id', 'store_id', 'customer_type', 'industry', 'province', 'city', 'district', 'status'], 'integer'],
            [['longitude', 'latitude'], 'number'],
            [['remark', 'business_scope'], 'string'],
            [['approval_date', 'establishment_date', 'business_start_date', 'business_end_date', 'cancellation_date', 'create_time', 'update_time'], 'safe'],
            [['customer_name', 'contact_person', 'website', 'address', 'detailed_address', 'company_english_name', 'registration_authority', 'legal_person'], 'string', 'max' => 255],
            [['contact_number', 'taxpayer_id', 'organization_code', 'registration_number', 'company_type', 'legal_person_type', 'enterprise_status', 'tax_registration_number'], 'string', 'max' => 50],
            [['phone', 'fax'], 'string', 'max' => 20],
            [['registered_capital'], 'string', 'max' => 55],
            [['store_id', 'phone'], 'unique', 'targetAttribute' => ['store_id', 'phone']],
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
    'id' => 'ID',
    'customer_id' => '主键ID',
    'bloc_id' => '公司ID',
    'store_id' => '商户ID',
    'customer_name' => '客商名称',
    'contact_person' => '采购联系人',
    'contact_number' => '联系方式',
    'customer_type' => '客商类型',
    'taxpayer_id' => '纳税人识别号',
    'website' => '公司网址',
    'phone' => '座机',
    'fax' => '传真',
    'address' => '地址',
    'detailed_address' => '详细地址',
    'longitude' => '经度',
    'latitude' => '纬度',
    'remark' => '备注',
    'company_english_name' => '公司英文名',
    'organization_code' => '组织机构代码',
    'registration_number' => '注册号',
    'company_type' => '公司类型',
    'registration_authority' => '登记机关',
    'legal_person' => '法人',
    'legal_person_type' => '法人类型',
    'enterprise_status' => '企业状态',
    'approval_date' => '核准日期',
    'establishment_date' => '成立时间',
    'business_start_date' => '营业期限自',
    'business_end_date' => '营业期限至',
    'cancellation_date' => '注销日期',
    'registered_capital' => '注册资金',
    'business_scope' => '经营范围',
    'industry' => '所属行业',
    'tax_registration_number' => '税务登记号',
    'create_time' => '创建时间',
    'update_time' => '更新时间',
    'province' => '省',
    'city' => '市',
    'district' => '区',
    'status' => 'Status',
];
}
}