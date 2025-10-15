<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-03-26 12:00:05
 */
namespace addons\diandi_place\models\searchs\member;
use addons\diandi_place\models\member\PlaceMemberCoupon;
use common\components\DataProvider\ArrayDataProvider;
use yii\base\Model;
use yii\data\Pagination;
/**
 * PlaceMemberCouponSearch represents the model behind the search form of `addons\diandi_place\models\PlaceMemberCoupon`.
 */
class PlaceMemberCouponSearch extends PlaceMemberCoupon
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'bloc_id', 'store_id', 'member_id', 'coupon_type', 'coupon_id', 'use_num', 'surplus_num', 'receive_type'], 'integer'],
            [['create_time', 'update_time', 'coupon_name', 'buy_time', 'end_time', 'use_time'], 'safe'],
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
     * @return ArrayDataProvider
     */
    public function search($params)
    {
        $query = PlaceMemberCoupon::find();
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
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'member_id' => $this->member_id,
            'coupon_type' => $this->coupon_type,
            'coupon_id' => $this->coupon_id,
            'buy_time' => $this->buy_time,
            'end_time' => $this->end_time,
            'use_time' => $this->use_time,
            'use_num' => $this->use_num,
            'surplus_num' => $this->surplus_num,
            'receive_type' => $this->receive_type,
        ]);
        $query->andFilterWhere(['like', 'coupon_name', $this->coupon_name]);
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
