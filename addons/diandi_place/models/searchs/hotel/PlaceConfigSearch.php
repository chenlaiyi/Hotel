<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-03-26 12:00:54
 */
namespace addons\diandi_place\models\searchs\hotel;
use addons\diandi_place\models\place\PlaceConfig;
use common\components\DataProvider\ArrayDataProvider;
use yii\base\Model;
use yii\data\Pagination;
/**
 * PlaceConfigSearch represents the model behind the search form of `addons\diandi_place\models\PlaceConfig`.
 */
class PlaceConfigSearch extends PlaceConfig
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'bloc_id', 'store_id'], 'integer'],
            [['create_time', 'update_time', 'mumber_scale', 'vip_scale', 'store_introduce', 'admin_ids', 'sms_order_template', 'sms_order_sign', 'sms_mobiles', 'order_create_template', 'order_end_template', 'recharge_template', 'renew_template'], 'safe'],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function scenarios()
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
    public function search($params)
    {
        $query = PlaceConfig::find();
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
        ]);
        $query->andFilterWhere(['like', 'mumber_scale', $this->mumber_scale])
            ->andFilterWhere(['like', 'vip_scale', $this->vip_scale])
            ->andFilterWhere(['like', 'store_introduce', $this->store_introduce])
            ->andFilterWhere(['like', 'admin_ids', $this->admin_ids])
            ->andFilterWhere(['like', 'sms_order_template', $this->sms_order_template])
            ->andFilterWhere(['like', 'sms_order_sign', $this->sms_order_sign])
            ->andFilterWhere(['like', 'sms_mobiles', $this->sms_mobiles])
            ->andFilterWhere(['like', 'order_create_template', $this->order_create_template])
            ->andFilterWhere(['like', 'order_end_template', $this->order_end_template])
            ->andFilterWhere(['like', 'recharge_template', $this->recharge_template])
            ->andFilterWhere(['like', 'renew_template', $this->renew_template]);
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
