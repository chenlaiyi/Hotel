<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-04-23 19:37:34
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-21 14:12:44
 */
namespace addons\diandi_place\api\manage\place;
use addons\diandi_place\models\forms\HotelForm;
use addons\diandi_place\services\PlaceService;
use addons\diandi_place\Traits\LandlordTrait;
use api\controllers\AController;
use common\helpers\ResultHelper;
use Yii;
/**
 * 楼栋管理
 * @date 2023-04-23
 * @example
 * @author Wang Chunsheng
 * @since
 */
class ListController extends AController
{
    use LandlordTrait;
    /**
     * 楼栋列表
     * @return array|object[]|string[]
     */
    public function actionIndex(): array
    {
        $type_id = Yii::$app->request->input('type_id');
        if (empty($type_id)){
            return ResultHelper::json(400, 'type_id 不能为空');
        }
        $keywords = Yii::$app->request->input('keywords');
        $time_type = Yii::$app->request->input('time_type');
        $list = PlaceService::initIndex($type_id, $time_type, $keywords);
        return ResultHelper::json(200, '获取成功', $list);
    }
    /**
     * 楼栋添加
     * @return array
     */
    public function actionAdd(): array
    {
//        $rules = [
//            [['name', 'type', 'is_show', 'address_show', 'lng', 'lat', 'address', 'location_p', 'location_c', 'location_a', 'roomcount', 'status', 'phone', 'mail', 'thumb', 'description', 'content', 'traffic', 'thumbs', 'sales', 'displayorder', 'level', 'device', 'brandid', 'lanuage'], 'required'],
//            [['name', 'address'], 'string', 'max' => 255],
//            [['type', 'is_show', 'address_show', 'status', 'roomcount', 'displayorder', 'level', 'brandid', 'lanuage'], 'integer'],
//            [['lng', 'lat'], 'number'],
//            [['phone'], 'string', 'max' => 20],
//            [['mail'], 'email'],
//            [['thumb', 'description', 'content', 'traffic'], 'string'],
//            [['thumbs'], 'each', 'rule' => ['url']],
//            [['sales'], 'number', 'min' => 0],
//            [['device'], 'each', 'rule' => ['string']],
//        ];
//        try {
//            $this->validateParams($rules);
//        } catch (ErrorException|InvalidConfigException $e) {
//            return ResultHelper::json(400, $e->getMessage(), (array)$e);
//        }
        $title = Yii::$app->request->input('name'); //酒店名称
        $type = Yii::$app->request->input('type');  //房源类型
        $is_show = Yii::$app->request->input('is_show');      //是否公开
        $address_show = Yii::$app->request->input('address_show'); //是否公开
        $lng = Yii::$app->request->input('lng');          //经度
        $lat = Yii::$app->request->input('lat');          //维度
        $address = Yii::$app->request->input('address');      //具体地址
        $location_p = Yii::$app->request->input('location_p');   //省份
        $location_c = Yii::$app->request->input('location_c');   //城市
        $location_a = Yii::$app->request->input('location_a');   //区县
        $roomcount = Yii::$app->request->input('roomcount');    //房间总量
        $status = Yii::$app->request->input('status');       //酒店状态
        $phone = Yii::$app->request->input('phone');        //联系电话
        $mail = Yii::$app->request->input('mail');         //联系邮箱
        $thumb = Yii::$app->request->input('thumb');        //酒店图片
        $description = Yii::$app->request->input('description');  //酒店简介
        $content = Yii::$app->request->input('content');      //酒店介绍
        $traffic = Yii::$app->request->input('traffic');      //周边交通
        $thumbs = Yii::$app->request->input('thumbs');       //酒店相册
        $sales = Yii::$app->request->input('sales');
        $displayorder = Yii::$app->request->input('displayorder'); //排序
        $level = Yii::$app->request->input('level');        //酒店星级
        $device = Yii::$app->request->input('device');       //服务设施
        $brandid = Yii::$app->request->input('brandid');      //所属品牌
        $lanuage = Yii::$app->request->input('lanuage');      //语言类型标志/默认中文0
        $apartment_type = Yii::$app->request->input('apartment_type',0);      //语言类型标志/默认中文0
        $REs = PlaceService::add($title, $type, $is_show, $address_show, $lng, $lat, $address, $location_p, $location_c, $location_a, $roomcount, $status, $phone, $mail, $thumb, $description, $content, $traffic, $thumbs, $sales, $displayorder, $level, $device, $brandid, $lanuage,$apartment_type);
        return ResultHelper::json(200, '添加成功', $REs->toArray());
    }
    public function actionEdit(): array
    {
        $id = Yii::$app->request->input('id');           //酒店名称
        $title = Yii::$app->request->input('name'); //酒店名称
        $is_show = Yii::$app->request->input('is_show');      //是否公开
        $address_show = Yii::$app->request->input('address_show'); //是否公开
        $lng = Yii::$app->request->input('lng');          //经度
        $lat = Yii::$app->request->input('lat');          //维度
        $type = Yii::$app->request->input('type');         //房源类型
        $address = Yii::$app->request->input('address');      //具体地址
        $location_p = Yii::$app->request->input('location_p');   //省份
        $location_c = Yii::$app->request->input('location_c');   //城市
        $location_a = Yii::$app->request->input('location_a');   //区县
        $roomcount = Yii::$app->request->input('roomcount');    //房间总量
        $status = Yii::$app->request->input('status');       //酒店状态
        $phone = Yii::$app->request->input('phone');        //联系电话
        $mail = Yii::$app->request->input('mail');         //联系邮箱
        $thumb = Yii::$app->request->input('thumb');        //酒店图片
        $description = Yii::$app->request->input('description');  //酒店简介
        $content = Yii::$app->request->input('content');      //酒店介绍
        $traffic = Yii::$app->request->input('traffic');      //周边交通
        $thumbs = Yii::$app->request->input('thumbs');       //酒店相册
        $sales = Yii::$app->request->input('sales');
        $displayorder = Yii::$app->request->input('displayorder'); //排序
        $level = Yii::$app->request->input('level');        //酒店星级
        $device = Yii::$app->request->input('device');       //服务设施
        $brandid = Yii::$app->request->input('brandid');      //所属品牌
        $lanuage = Yii::$app->request->input('lanuage');      //语言类型标志/默认中文0
        $apartment_type = Yii::$app->request->input('apartment_type',0);      //语言类型标志/默认中文0
        $REs = PlaceService::edit($id, $title, $type, $is_show, $address_show, $lng, $lat, $address, $location_p, $location_c, $location_a, $roomcount, $status, $phone, $mail, $thumb, $description, $content, $traffic, $thumbs, $sales, $displayorder, $level, $device, $brandid, $lanuage,$apartment_type);
        return ResultHelper::json(200, '编辑成功', $REs);
    }
    public function actionDel(): array
    {
        $REs = PlaceService::del(Yii::$app->request->input('id'));
        return ResultHelper::json(200, '删除成功', $REs);
    }
    public function actionList(): array
    {
        $page = Yii::$app->request->input('page') ?? 1;
        $pageSize = Yii::$app->request->input('pageSize') ?? 10;
        $type = (int)Yii::$app->request->input('type');
        $where = [];
        if ($type) {
            $where['type'] = $type;
        }
        $REs = PlaceService::blocList($page, $pageSize, $where);
        return ResultHelper::json(200, '获取成功', $REs);
    }
}