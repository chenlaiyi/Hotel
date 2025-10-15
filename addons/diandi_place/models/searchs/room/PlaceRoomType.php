<?php
namespace addons\diandi_place\models\searchs\room;
use addons\diandi_place\models\room\PlaceRoomType as PlaceRoomTypeModel;
use common\components\DataProvider\ArrayDataProvider;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
/**
 * PlaceRoomType represents the model behind the search form of `addons\diandi_place\models\room\PlaceRoomType`.
 */
class PlaceRoomType extends PlaceRoomTypeModel
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'bloc_id', 'store_id', 'hotel_id', 'type_id', 'is_suite', 'room_num', 'toilet_num', 'bed_children', 'bed_adult', 'bed_guest', 'bed', 'persons', 'isshow', 'displayorder', 'area_show', 'floor_show', 'smoke_show', 'bed_show', 'persons_show', 'bedadd_show', 'score', 'breakfast', 'language', 'free_cancel'], 'integer'],
            [['title', 'thumb', 'thumbs', 'device', 'area', 'floor', 'bedadd', 'sales', 'checkIn_start', 'checkIn_end', 'cancel_start', 'cancel_end', 'remark'], 'safe'],
            [['oprice', 'cprice', 'mprice', 'cleaning_fee', 'server_fee'], 'number'],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ArrayDataProvider|bool|ActiveDataProvider
     */
    public function search(array $params): ArrayDataProvider|bool|ActiveDataProvider
    {
        $query = PlaceRoomTypeModel::find();
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return false;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'bloc_id' => $this->bloc_id,
            'store_id' => $this->store_id,
            'hotel_id' => $this->hotel_id,
            'type_id' => $this->type_id,
            'oprice' => $this->oprice,
            'cprice' => $this->cprice,
            'mprice' => $this->mprice,
            'is_suite' => $this->is_suite,
            'room_num' => $this->room_num,
            'toilet_num' => $this->toilet_num,
            'bed_children' => $this->bed_children,
            'bed_adult' => $this->bed_adult,
            'bed_guest' => $this->bed_guest,
            'bed' => $this->bed,
            'cleaning_fee' => $this->cleaning_fee,
            'server_fee' => $this->server_fee,
            'persons' => $this->persons,
            'isshow' => $this->isshow,
            'displayorder' => $this->displayorder,
            'area_show' => $this->area_show,
            'floor_show' => $this->floor_show,
            'smoke_show' => $this->smoke_show,
            'bed_show' => $this->bed_show,
            'persons_show' => $this->persons_show,
            'bedadd_show' => $this->bedadd_show,
            'score' => $this->score,
            'breakfast' => $this->breakfast,
            'language' => $this->language,
            'free_cancel' => $this->free_cancel,
            'checkIn_start' => $this->checkIn_start,
            'checkIn_end' => $this->checkIn_end,
            'cancel_start' => $this->cancel_start,
            'cancel_end' => $this->cancel_end,
        ]);
        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'thumb', $this->thumb])
            ->andFilterWhere(['like', 'thumbs', $this->thumbs])
            ->andFilterWhere(['like', 'device', $this->device])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'floor', $this->floor])
            ->andFilterWhere(['like', 'bedadd', $this->bedadd])
            ->andFilterWhere(['like', 'sales', $this->sales])
            ->andFilterWhere(['like', 'remark', $this->remark]);
        $query->findBlocs();
        $count = $query->count();
        $pageSize = \Yii::$app->request->input('pageSize');
        $page = \Yii::$app->request->input('page');
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => $pageSize,
            'page' => $page - 1,
            // 'pageParam'=>'page'
        ]);
        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        //foreach ($list as $key => &$value) {
        //    $value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
        //    $value['update_time'] = date('Y-m-d H:i:s',$value['update_time']);
        //} 
        return new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $list,
            'totalCount' => $count,
            'total' => $count,
            'sort' => [
                'attributes' => [
                    //'member_id',
                ],
                'defaultOrder' => [
                    //'member_id' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
    }
}
