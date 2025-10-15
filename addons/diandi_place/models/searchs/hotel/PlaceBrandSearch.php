<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-20 10:18:42
 */
namespace addons\diandi_place\models\searchs\hotel;
use addons\diandi_place\models\place\PlaceBrand;
use yii\base\Model;
use common\components\DataProvider\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
/**
 * PlaceBrandSearch represents the model behind the search form of `addons\diandi_place\models\PlaceBrand`.
 */
class PlaceBrandSearch extends PlaceBrand
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'bloc_id', 'displayorder', 'status'], 'integer'],
            [['title', 'create_time', 'update_time'], 'safe'],
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
     * @return ArrayDataProvider|false
     */
    public function search($params)
   {
        $query = PlaceBrand::find();
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
            'displayorder' => $this->displayorder,
            'status' => $this->status,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);
        $query->andFilterWhere(['like', 'title', $this->title]);
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
        $provider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $list,
            'totalCount' => isset($count) ? $count : 0,
            'total' => isset($count) ? $count : 0,
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
        return $provider;
    }
}
