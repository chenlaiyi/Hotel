<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-03-26 12:10:29
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-10 15:04:14
 */
namespace addons\diandi_place\models\advertise;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
use yii\db\ActiveQuery;
/**
 * This is the model class for table "{{%diandi_place_advertise}}".
 *
 * @public int $id
 * @public int|null $store_id 商户id
 * @public int|null $bloc_id 公司id
 * @public string|null $name 位置名称
 * @public int|null $maxnum 显示数量
 * @public string|null $mark 英文标记
 * @public int|null $is_show 是否显示
 * @public string|null $page 页面
 * @public int|null $type 广告位类型
 * @public int|null $style 排列样式
 * @public string|null $thumb
 * @public int|null $is_show_thumb
 * @public int|null $hotel_id
 * @public string|null $url
 * @public int|null $displayorder 排序
 *
 * @property-read ActiveQuery $ad
 * @property-read ActiveQuery $rim
 * @property-read ActiveQuery $hotel
 * @property-read ActiveQuery $room
 */
class PlaceAdvertise extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_advertise}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['store_id', 'bloc_id', 'maxnum', 'is_show', 'type', 'style', 'is_show_thumb', 'hotel_id', 'displayorder'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['mark', 'page', 'thumb', 'url'], 'string', 'max' => 255],
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
     * 获取基础广告
     * @return ActiveQuery
     * @date 2023-03-26
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function getAd()
    {
        return $this->hasMany(HotelAdvertiseAd::class, ['id' => 'location_id']);
    }
    /**
     * 获取酒店广告
     * @return ActiveQuery
     * @date 2023-03-26
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function getHotel()
    {
        return $this->hasMany(HotelAdvertiseHotel::class, ['location_id' => 'id'])->joinWith('hotel');
    }
    /**
     * 获取周边广告
     * @return ActiveQuery
     * @date 2023-03-26
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function getRim()
    {
        return $this->hasMany(HotelAdvertiseRim::class, ['location_id' => 'id'])->joinWith('rim');
    }
    /**
     * 获取房间广告
     * @return ActiveQuery
     * @date 2023-03-26
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function getRoom()
    {
        return $this->hasMany(HotelAdvertiseRoom::class, ['location_id' => 'id'])->joinWith('room');
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => '商户id',
            'bloc_id' => '公司id',
            'name' => '位置名称',
            'maxnum' => '显示数量',
            'mark' => '英文标记',
            'is_show' => '是否显示',
            'page' => '页面',
            'type' => '广告位类型',
            'style' => '排列样式',
            'thumb' => 'Thumb',
            'is_show_thumb' => 'Is Show Thumb',
            'hotel_id' => 'Goods ID',
            'url' => 'Url',
            'displayorder' => '排序',
        ];
    }
}
