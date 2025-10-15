<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-27 11:40:46
 */
namespace addons\diandi_place\models\room;
use addons\diandi_place\events\RoomInit;
use addons\diandi_place\models\place\PlaceComment;
use addons\diandi_place\models\place\PlaceLandlord;
use addons\diandi_place\models\place\PlaceList;
use addons\diandi_place\models\place\PlaceRim;
use addons\diandi_place\models\place\PlaceTier;
use addons\diandi_place\services\RoomDataServer;
use common\components\events\DdDispatcher;
use common\events\AddonsEvent;
use common\helpers\loggingHelper;
use common\traits\ActiveQuery\StoreTrait;
use Yii;
use yii\db\ActiveQuery;
/**
 * This is the model class for table "{{%diandi_place_room}}".
 *
 * @public int $id
 * @public int|null $bloc_id 公司ID
 * @public int|null $store_id 商户ID
 * @public int|null $hotel_id 酒店ID
 * @public string|null $title 房间名称
 * @public string|null $thumb 房间主图
 * @public int|null $oprice 原价
 * @public int|null $cprice 现价
 * @public int|null $mprice 会员价
 * @public string|null $thumbs 房间相册
 * @public string|null $device 服务设施
 * @public string|null $area
 * @public int|null $room_num 几室
 * @public int|null $toilet_num 几卫
 * @public string|null $floor 楼层
 * @public string|null $bed 床位数
 * @public int|null $persons 最多容纳人数
 * @public string|null $bedadd 是否可加床
 * @public int|null $status 房间状态
 * @public int|null $isshow 是否显示
 * @public string|null $sales 销售数量
 * @public int|null $displayorder 排序
 * @public int|null $area_show 是否显示具体位置
 * @public int|null $floor_show 是否显示楼层
 * @public int|null $smoke_show 是否显示抽烟
 * @public int|null $bed_show 是否添加床位
 * @public int|null $persons_show 是否显示添加人数
 * @public int|null $bedadd_show 是否显示添加床位
 * @public int|null $score 订房积分
 * @public int|null $breakfast 0无早 1单早 2双早
 * @public int $lanuage 语言类型标志/默认中文0
 * @public int|null $free_cancel 是否免费取下1是0否
 * @public string|null $checkIn_start 入住开始时间
 * @public string|null $checkIn_end 入住结束时间
 * @public string|null $out_time 退房时间
 * @public string|null $remark 备注
 * @public int|null $room_type_id 房型id
 *
 * @property-read ActiveQuery $server
 * @property-read ActiveQuery $landlord
 * @property-read ActiveQuery $childRoom
 * @property-read ActiveQuery $tier
 * @property-read ActiveQuery $price
 * @property-read ActiveQuery $service
 * @property-read ActiveQuery $slide
 * @property-read ActiveQuery $rim
 * @property-read ActiveQuery $hotel
 * @property-read ActiveQuery $comment
 * @property-read ActiveQuery $roomType
 * @property-read ActiveQuery $child
 * @property-read ActiveQuery $order
 * @property mixed|null $device
 * @property mixed|null $store_id
 * @property mixed|null $id
 * @property mixed|null $room_type_id
 * @property mixed|null $bed_children
 * @property mixed|null $bed_adult
 * @property int|mixed|null $status
 */
class PlaceRoom extends \common\components\ActiveRecord\YiiActiveRecord
{
    use StoreTrait;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%diandi_place_room}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'thumbs', 'thumb', 'tier_id'], 'required'],
            [['bloc_id', 'room_type_id', 'store_id', 'hotel_id', 'room_num', 'toilet_num', 'persons', 'status', 'isshow', 'displayorder', 'area_show', 'floor_show', 'smoke_show', 'bed_show',
                'persons_show', 'bedadd_show', 'score', 'breakfast', 'language', 'free_cancel', 'room_pid', 'is_suite', 'time_type', 'time_length', 'lease_type', 'room_type_id', 'landlord_id', 'room_type'], 'integer'],
            [['bed_children', 'bed_adult', 'comment_num'], 'integer'], //床位数
            [['hotel_id', 'tier_id', 'unit_id', 'type_id'], 'integer'], //楼栋
            [['cleaning_fee', 'server_fee', 'oprice', 'cprice', 'mprice', 'comment_start', 'area'], 'number'], //清洁费、服务费
            [['thumbs', 'device', 'sales', 'remark'], 'string'],
            [['checkin_start', 'checkin_end', 'cancel_start', 'cancel_end', 'out_time', 'content'], 'string'],
            [['title', 'thumb', 'bed', 'desc'], 'string', 'max' => 255],
            [['bedadd'], 'string', 'max' => 30],
            [['wifi_ssid','wifi_password'], 'string', 'max' => 30],
            [['hotel_id', 'title', 'tier_id'], 'unique', 'targetAttribute' => ['hotel_id', 'title', 'tier_id'], 'message' => '房间名称重复']
        ];
    }
    public function afterSave($insert, $changedAttributes): void
    {
        $hotel_id = Yii::$app->request->input('hotel_id', 0);
        $type_id = Yii::$app->request->input('type_id');
        //根据房型的ID确定当前房间的全局房型
        $room_type = PlaceRoomType::find()->where(['id' => $this->room_type_id])->select('room_type')->scalar();
        $data = [
            'hotel_id' => $hotel_id,
            'type_id' => $hotel['type'] ?? 0,
            'bed' => array_sum([$this->bed_children, $this->bed_adult])
        ];
        if (empty($hotel_id)) {
            $hotel = PlaceList::find()->where(['store_id' => $this->store_id])->select(['id', 'type'])->one();
            $data['hotel_id'] = $hotel['id'] ?? 0;
        } else {
            $data['type_id'] = $type_id;
        }
        if ($room_type) {
            $data['room_type'] = $room_type;
        }
        $this->updateAll($data, ['id' => $this->id]);
        Yii::$app->trigger(AddonsEvent::EVENT_ADDONS, new AddonsEvent([
            'addons' => ['diandi_tea','diandi_hotel','diandi_apartment'],
            'method' => 'afterSaveRoom',
            'params'=> [
                'insert'=>$insert,
                'data'=> $this->getAttributes()
            ]
        ]));
        parent::afterSave($insert, $changedAttributes);
    }
    function afterDelete()
    {
        Yii::$app->trigger(AddonsEvent::EVENT_ADDONS, new AddonsEvent([
            'addons' => ['diandi_tea','diandi_hotel','diandi_apartment'],
            'method' => 'afterDeleteRoom',
            'params'=> $this->getAttributes()
        ]));
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
                'time_type' => 'datetime'
            ],
        ];
    }
    public function getChildRoom(): ActiveQuery
    {
        return $this->hasMany(self::class, ['room_pid' => 'id']);
    }
    public function getHotel(): ActiveQuery
    {
        return $this->hasOne(PlaceList::class, ['store_id' => 'store_id']);
    }
    public function getRim(): ActiveQuery
    {
        return $this->hasOne(PlaceRim::class, ['room_id' => 'id']);
    }
    public function getServer(): ActiveQuery
    {
        return $this->hasMany(PlaceRoomServer::class, ['room_id' => 'id']);
    }
    public function getPrice(): ActiveQuery
    {
        return $this->hasOne(PlaceRoomPrice::class, ['room_id' => 'id']);
    }
    public function getSlide(): ActiveQuery
    {
        return $this->hasMany(PlaceRoomSlide::class, ['room_id' => 'id']);
    }
    public function getTier(): ActiveQuery
    {
        return $this->hasOne(PlaceTier::class, ['id' => 'tier_id']);
    }
    public function getChild(): ActiveQuery
    {
        return $this->hasOne(self::class, ['pid' => 'id']);
    }
    public function getRoomType(): ActiveQuery
    {
        return $this->hasOne(PlaceRoomType::class, ['id' => 'room_type_id']);
    }
    /**
     * 房东
     * @return ActiveQuery
     * @date 2023-06-05
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function getLandlord(): ActiveQuery
    {
        return $this->hasOne(PlaceLandlord::class, ['id' => 'landlord_id']);
    }
    public function getRoomStatus(): ActiveQuery
    {
        return $this->hasOne(PlaceRoomStatus::class, ['room_id' => 'id']);
    }
    public function getComment(): ActiveQuery
    {
        return $this->hasOne(PlaceComment::class, ['room_id' => 'id'])->with(['member'])->orderBy([
            'create_time' => SORT_DESC
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('place', 'ID'),
            'bloc_id' => Yii::t('place', '公司ID'),
            'store_id' => Yii::t('place', '商户ID'),
            'hotel_id' => Yii::t('place', '酒店ID'),
            'title' => Yii::t('place', '房间名称'),
            'landlord_id' => Yii::t('place', '房东'),
            'thumb' => Yii::t('place', '房间主图'),
            'oprice' => Yii::t('place', '原价'),
            'cprice' => Yii::t('place', '现价'),
            'mprice' => Yii::t('place', '会员价'),
            'thumbs' => Yii::t('place', '房间相册'),
            'device' => Yii::t('place', '服务设施'),
            'area' => Yii::t('place', 'Area'),
            'room_num' => Yii::t('place', '几室'),
            'toilet_num' => Yii::t('place', '几卫'),
            'bed' => Yii::t('place', '床位数'),
            'persons' => Yii::t('place', '最多容纳人数'),
            'bedadd' => Yii::t('place', '是否可加床'),
            'status' => Yii::t('place', '房间状态'),
            'isshow' => Yii::t('place', '是否显示'),
            'sales' => Yii::t('place', '销售数量'),
            'displayorder' => Yii::t('place', '排序'),
            'area_show' => Yii::t('place', '是否显示具体位置'),
            'floor_show' => Yii::t('place', '是否显示楼层'),
            'smoke_show' => Yii::t('place', '是否显示抽烟'),
            'bed_show' => Yii::t('place', '是否添加床位'),
            'persons_show' => Yii::t('place', '是否显示添加人数'),
            'bedadd_show' => Yii::t('place', '是否显示添加床位'),
            'score' => Yii::t('place', '订房积分'),
            'breakfast' => Yii::t('place', '0无早 1单早 2双早'),
            'language' => Yii::t('place', '语言类型标志/默认中文0'),
            'free_cancel' => Yii::t('place', '是否免费取下1是0否'),
            'checkIn_start' => Yii::t('place', '入住开始时间'),
            'checkIn_end' => Yii::t('place', '入住结束时间'),
            'out_time' => Yii::t('place', '退房时间'),
            'room_pid' => Yii::t('place', '主房间'),
            'is_suite' => Yii::t('place', '是否套房'),
            'building_id' => Yii::t('place', '所属楼栋'),
            'tier_id' => Yii::t('place', '所属楼层'),
            'unit_id' => Yii::t('place', '所属单元'),
            'time_type' => Yii::t('place', '租约类型（长租和短租）'),
            'remark' => Yii::t('place', '备注'),
        ];
    }
}
