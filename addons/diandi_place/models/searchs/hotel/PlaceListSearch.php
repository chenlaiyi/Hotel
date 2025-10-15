<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-20 13:10:11
 */
namespace addons\diandi_place\models\searchs\hotel;
use addons\diandi_place\models\place\PlaceList;
use common\components\DataProvider\ArrayDataProvider;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
/**
 * PlaceListSearch represents the model behind the search form of `addons\diandi_place\models\PlaceList`.
 */
class PlaceListSearch extends PlaceList
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'bloc_id', 'province', 'city', 'county', 'roomcount', 'status', 'displayorder', 'level', 'brandid', 'language'], 'integer'],
            [['name', 'address', 'phone', 'mail', 'thumb', 'description', 'content', 'traffic', 'thumbs', 'sales', 'device', 'create_time', 'update_time'], 'safe'],
            [['lng', 'lat'], 'number'],
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
     * Creates data provider instance with a search query applied
     *
     * @param array $params
     *
     * @return ArrayDataProvider|bool|ActiveDataProvider
     */
    public function search(array $params): ArrayDataProvider|bool|ActiveDataProvider
    {
        $query = PlaceList::find();
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
            'lng' => $this->lng,
            'lat' => $this->lat,
            'province' => $this->province,
            'city' => $this->city,
            'county' => $this->county,
            'roomcount' => $this->roomcount,
            'status' => $this->status,
            'displayorder' => $this->displayorder,
            'level' => $this->level,
            'brandid' => $this->brandid,
            'language' => $this->language,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'mail', $this->mail])
            ->andFilterWhere(['like', 'thumb', $this->thumb])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'traffic', $this->traffic])
            ->andFilterWhere(['like', 'thumbs', $this->thumbs])
            ->andFilterWhere(['like', 'sales', $this->sales])
            ->andFilterWhere(['like', 'device', $this->device]);
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
