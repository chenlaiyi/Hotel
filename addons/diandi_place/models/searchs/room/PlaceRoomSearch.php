<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-03-27 13:35:50
 */
namespace addons\diandi_place\models\searchs\room;
use addons\diandi_place\models\room\PlaceRoom;
use yii\base\Model;
use common\components\DataProvider\ArrayDataProvider;
use yii\data\Pagination;
/**
 * PlaceRoomSearch represents the model behind the search form of `addons\diandi_place\models\PlaceRoom`.
 * @property mixed|null $title
 * @property mixed|null $language
 * @property mixed|null $breakfast
 * @property mixed|null $bloc_id
 */
class PlaceRoomSearch extends PlaceRoom
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'bloc_id', 'store_id', 'hotel_id', 'oprice', 'cprice', 'mprice', 'room_num', 'toilet_num', 'persons', 'status', 'isshow',
                'displayorder', 'area_show', 'floor_show', 'smoke_show', 'bed_show', 'persons_show', 'bedadd_show', 'score', 'breakfast', 'language', 'free_cancel'], 'integer'],
            [['title', 'thumb', 'thumbs', 'device', 'area', 'bed', 'bedadd', 'sales', 'checkin_start', 'checkin_end', 'out_time'], 'safe'],
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
     * @return ArrayDataProvider|false
     */
    public function search(array $params): ArrayDataProvider|bool
   {
        $query = PlaceRoom::find();
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
            'oprice' => $this->oprice,
            'cprice' => $this->cprice,
            'mprice' => $this->mprice,
            'room_num' => $this->room_num,
            'toilet_num' => $this->toilet_num,
            'persons' => $this->persons,
            'status' => $this->status,
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
            'free_cancel' => $this->free_cancel
        ]);
        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'device', $this->device])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'bed', $this->bed])
            ->andFilterWhere(['like', 'bedadd', $this->bedadd])
            ->andFilterWhere(['like', 'sales', $this->sales]);
        $query->findBlocs();
        $count = $query->count();
        $pageSize   =\Yii::$app->request->input('pageSize');
        $page       =\Yii::$app->request->input('page');
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
            'totalCount' => $count ?? 0,
            'total' => $count ?? 0,
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
