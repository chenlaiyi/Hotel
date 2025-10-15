<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-05-29 20:44:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-05 15:13:23
 */
namespace addons\diandi_place\models\room;
use addons\diandi_hotel\Traits\HotelTrait;
use addons\diandi_place\Traits\PlaceTrait;
use common\traits\ActiveQuery\StoreTrait;
/**
 * This is the model class for table "{{%diandi_place_room_type}}".
 *
 * @public int         $id
 * @public int|null    $bloc_id       公司ID
 * @public int|null    $store_id      商户ID
 * @public int|null    $type_id       房源ID
 * @public string|null $title         房型名称
 * @public string|null $thumb         房间主图
 * @public float|null  $oprice        原价
 * @public float|null  $cprice        现价
 * @public float|null  $mprice        会员价
 * @public string|null $thumbs        房间相册
 * @public string|null $device        服务设施
 * @public int|null    $is_suite      是否是套房
 * @public string|null $area          面积
 * @public int|null    $room_num      几室
 * @public int|null    $toilet_num    几卫
 * @public string|null $floor         楼层
 * @public int|null    $bed_children  儿童床位数
 * @public int|null    $bed_adult     成人床位数
 * @public int|null    $bed_guest     客人床位数
 * @public int|null    $bed           床位数
 * @public float|null  $cleaning_fee  清洁费
 * @public float|null  $server_fee    服务费
 * @public int|null    $persons       最多容纳人数
 * @public string|null $bedadd        是否可加床
 * @public int|null    $isshow        是否显示
 * @public string|null $sales         销售数量
 * @public int|null    $displayorder  排序
 * @public int|null    $area_show     是否显示具体位置
 * @public int|null    $floor_show    是否显示楼层
 * @public int|null    $smoke_show    是否显示抽烟
 * @public int|null    $bed_show      是否添加床位
 * @public int|null    $persons_show  是否显示添加人数
 * @public int|null    $bedadd_show   是否显示添加床位
 * @public int|null    $score         订房积分
 * @public int|null    $breakfast     0无早 1单早 2双早
 * @public int         $language       语言类型标志/默认中文0
 * @public int|null    $free_cancel   是否免费取下1是0否
 * @public string|null $checkIn_start 入住开始时间
 * @public string|null $checkIn_end   入住结束时间
 * @public string|null $cancel_start  取消开始时间
 * @public string|null $cancel_end    退房结束时间
 * @public string|null $remark
 *
 * @property-read mixed $server
 */
class PlaceRoomType extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait,PlaceTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_room_type}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            // [['room_type'],'required'],
            [['bloc_id', 'room_type', 'store_id', 'hotel_id', 'type_id', 'is_suite', 'room_num', 'toilet_num', 'bed_children', 'bed_adult', 'bed_guest', 'bed', 'persons', 'isshow', 'displayorder', 'area_show', 'floor_show', 'smoke_show', 'bed_show', 'persons_show', 'bedadd_show', 'score', 'breakfast', 'language', 'free_cancel'], 'integer'],
            [['oprice', 'cprice', 'mprice', 'cleaning_fee', 'server_fee'], 'number'],
            [['thumbs', 'device', 'sales'], 'string'],
            [['checkIn_start', 'checkIn_end', 'cancel_start', 'cancel_end'], 'safe'],
            [['title', 'thumb', 'area', 'floor', 'remark'], 'string', 'max' => 255],
            [['bedadd'], 'string', 'max' => 30],
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
                'class' => \common\behaviors\SaveBehavior::class,
                'updatedAttribute' => 'update_time',
                'createdAttribute' => 'create_time',
                'time_type' => 'datetime',
            ],
        ];
    }
    public function getServer(): \yii\db\ActiveQuery
    {
        return $this->hasMany(PlaceRoomServer::class, ['room_type_id' => 'id']);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'bloc_id' => '公司ID',
            'store_id' => '商户ID',
            'type_id' => '房源ID',
            'title' => '房型名称',
            'thumb' => '房间主图',
            'oprice' => '原价',
            'cprice' => '现价',
            'mprice' => '会员价',
            'thumbs' => '房间相册',
            'device' => '服务设施',
            'is_suite' => '是否是套房',
            'area' => '面积',
            'room_num' => '几室',
            'toilet_num' => '几卫',
            'room_type' => '房型',
            'floor' => '楼层',
            'bed_children' => '儿童床位数',
            'bed_adult' => '成人床位数',
            'bed_guest' => '客人床位数',
            'bed' => '床位数',
            'cleaning_fee' => '清洁费',
            'server_fee' => '服务费',
            'persons' => '最多容纳人数',
            'bedadd' => '是否可加床',
            'isshow' => '是否显示',
            'sales' => '销售数量',
            'displayorder' => '排序',
            'area_show' => '是否显示具体位置',
            'floor_show' => '是否显示楼层',
            'smoke_show' => '是否显示抽烟',
            'bed_show' => '是否添加床位',
            'persons_show' => '是否显示添加人数',
            'bedadd_show' => '是否显示添加床位',
            'score' => '订房积分',
            'breakfast' => '0无早 1单早 2双早',
            'language' => '语言类型标志/默认中文0',
            'free_cancel' => '是否免费取下1是0否',
            'checkIn_start' => '入住开始时间',
            'checkIn_end' => '入住结束时间',
            'cancel_start' => '取消开始时间',
            'cancel_end' => '退房结束时间',
            'remark' => '备注',
        ];
    }
}
