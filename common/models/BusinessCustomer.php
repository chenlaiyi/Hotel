<?php

namespace common\models;

use common\traits\ActiveQuery\StoreTrait;

class BusinessCustomer extends \yii\db\ActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_business_customer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['customer_name'], 'required'],
            [['bloc_id', 'store_id', 'opportunity_id', 'follower_id', 'inspector_id', 'last_follow_id', 'time_ok'], 'integer'],
            [['longitude', 'latitude', 'registered_capital'], 'number'],
            [['inspection_time', 'approval_date', 'establishment_date', 'business_start_date', 'business_end_date', 'cancellation_date', 'create_time', 'update_time', 'last_opportunity'], 'safe'],
            [['remark', 'business_scope', 'follower_data'], 'string'],
            [['category', 'customer_name', 'contact_person', 'website', 'address', 'detailed_address', 'company_english_name', 'registration_authority', 'legal_person', 'industry'], 'string', 'max' => 255],
            [['contact_number', 'phone', 'fax'], 'string', 'max' => 20],
            [['customer_type', 'taxpayer_id', 'organization_code', 'registration_number', 'company_type', 'legal_person_type', 'enterprise_status', 'tax_registration_number'], 'string', 'max' => 50],
            [['follow_time'], 'string', 'max' => 22],
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
            'id' => '主键ID',
            'bloc_id' => '公司ID',
            'store_id' => '商户ID',
            'opportunity_id' => '商机ID',
            'category' => '客商分类',
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
            'follower_id' => '跟进人',
            'longitude' => '经度',
            'latitude' => '纬度',
            'follow_time' => '跟进时间',
            'inspector_id' => '巡检人',
            'inspection_time' => '巡检时间',
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
            'follower_data' => '储存联系人ID（数组序列化）',
        ];
    }
}