<?php
namespace addons\diandi_place\models\searchs\place;
use addons\diandi_place\models\place\PlaceLandlord as PlaceLandlordModel;
use common\components\DataProvider\ArrayDataProvider;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
/**
 * PlaceLandlord represents the model behind the search form of `addons\diandi_place\models\place\PlaceLandlord`.
 */
class PlaceLandlord extends PlaceLandlordModel
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'bloc_id', 'store_id', 'member_id', 'user_id', 'language', 'status', 'user_id'], 'integer'],
            [['realname', 'desc', 'content', 'mobile', 'icard_code', 'icard_front', 'icard_back', 'contract'], 'safe'],
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
        $query = PlaceLandlordModel::find()->with(['member', 'user', 'bloc', 'store']);
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
            'member_id' => $this->member_id,
            'user_id' => $this->user_id,
            'language' => $this->language,
            'status' => $this->status,
        ]);
        $query->andFilterWhere(['like', 'realname', $this->realname])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'icard_code', $this->icard_code])
            ->andFilterWhere(['like', 'icard_front', $this->icard_front])
            ->andFilterWhere(['like', 'icard_back', $this->icard_back])
            ->andFilterWhere(['like', 'contract', $this->contract]);
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
            ->asArray()
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
