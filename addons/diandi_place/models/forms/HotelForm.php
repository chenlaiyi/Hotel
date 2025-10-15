<?php
namespace addons\diandi_place\models\forms;
use yii\base\Model;
class HotelForm extends Model
{
      public $bloc_id;
      public $store_id;
      public $name;
      public $lng;
      public $lat;
      public $is_show;
      public $address_show;
      public $type;
      public $address;
      public $location_p;
      public $location_c;
      public $location_a;
      public $roomcount;
      public $status;
      public $phone;
      public $mail;
      public $thumb;
      public $description;
      public $content;
      public $traffic;
      public $thumbs;
      public $sales;
      public $displayorder;
      public $comment_num;
      public $comment_start;
      public $level;
      public $device;
      public $brandid;
      public $language;
      public $landlord_id;
      public $apartment_type;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['store_id'], 'unique','on'=>'create'],
            [['is_show', 'address_show', 'bloc_id', 'location_p', 'location_c', 'location_a', 'roomcount', 'status', 'displayorder', 'level', 'brandid', 'language', 'type', 'landlord_id','apartment_type'], 'integer'],
            [['lng', 'lat', 'comment_start', 'comment_num'], 'number'],
            [['description', 'content', 'traffic', 'thumbs', 'sales', 'device'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['name', 'address', 'phone', 'mail', 'thumb'], 'string', 'max' => 255],
            [['bloc_id', 'name'], 'unique', 'targetAttribute' => ['bloc_id', 'name'], 'message' => '�Ƶ������ظ�']
        ];
    }
    /**
     * @param mixed $apartment_type
     * @return HotelForm
     */
    public function setApartmentType($apartment_type)
    {
        $this->apartment_type = $apartment_type;
        return $this;
    }
}
