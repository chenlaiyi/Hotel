<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:39
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-07-15 20:02:16
 */


namespace common\models;

use common\traits\ActiveQuery\StoreTrait;

/**
 * This is the model class for table "{{%website_station_group}}".
 *
 * @property int $id
 * @property string|null $flogo 前台logo
 * @property string|null $blogo 后台logo
 * @property string|null $domain_url 域名
 * @property string|null $name 站点名称
 * @property string|null $intro 站点介绍
 * @property string|null $keywords 站点检索词
 * @property string|null $description 站点描述
 * @property string|null $footerleft 底部左侧
 * @property string|null $footerright 底部右侧
 * @property string|null $location
 * @property string|null $icp 备案信息
 * @property string|null $create_time
 * @property string|null $update_time
 * @property string|null $mobile 联系电话
 * @property string|null $city 所在城市
 * @property string|null $company_name 公司名称
 * @property string|null $wechat 微信号
 * @property int|null $status 0申请，1付款，2已部署
 * @property int|null $bloc_id
 * @property int|null $store_id
 */
class WebsiteStationGroup extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%website_station_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['create_time', 'update_time'], 'safe'],
            [['status', 'bloc_id', 'store_id'], 'integer'],
            [['flogo', 'blogo','ico', 'intro', 'loginbg', 'keywords', 'description', 'footerleft', 'footerright', 'location', 'icp'], 'string', 'max' => 255],
            [['domain_url', 'name', 'company_name'], 'string', 'max' => 100],
            [['login_top','login_left'],'number'],
            [['mobile'], 'string', 'max' => 20],
            [['city'], 'string', 'max' => 10],
            [['wechat'], 'string', 'max' => 50],
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
            'flogo' => '前台logo',
            'blogo' => '后台logo',
            'domain_url' => '域名',
            'name' => '站点名称',
            'intro' => '站点介绍',
            'keywords' => '站点检索词',
            'description' => '站点描述',
            'footerleft' => '底部左侧',
            'footerright' => '底部右侧',
            'location' => 'Location',
            'icp' => '备案信息',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'mobile' => '联系电话',
            'city' => '所在城市',
            'company_name' => '公司名称',
            'wechat' => '微信号',
            'status' => '0申请，1付款，2已部署',
            'bloc_id' => 'Bloc ID',
            'store_id' => 'Store ID',
        ];
    }
}
