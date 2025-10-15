<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-09-16 11:53:43
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2021-09-16 11:58:58
 */


namespace common\plugins\diandi_website\models;

/**
 * This is the model class for table "{{%diandi_website_feedback}}".
 *
 * @public int $id
 * @public string $inquiry_type
 * @public string $name
 * @public string $contact
 * @public string $email
 * @public string $remark
 * @public int $created_at
 * @public int $updated_at
 */
class WebsiteFeedback extends \common\components\ActiveRecord\YiiActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_website_feedback}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['inquiry_type'], 'integer'],
            [['product_name','company_name'], 'string', 'max' => 125],
            [['name'], 'string', 'max' => 100],
            [['contact', 'email'], 'string', 'max' => 50],
            [['remark'], 'string', 'max' => 255],
            [['created_time','updated_time'],'safe']
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
                'time_type'             => 'datetime',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'         => 'ID',
            'inquiry_type'    => 'inquiry_type',
            'name'       => 'Name',
            'contact'      => 'contact',
            'email'      => 'Email',
            'remark'       => 'remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
