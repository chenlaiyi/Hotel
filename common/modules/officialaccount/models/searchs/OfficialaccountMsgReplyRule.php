<?php

namespace common\modules\officialaccount\models\searchs;

use common\components\DataProvider\ArrayDataProvider;
use common\modules\officialaccount\models\OfficialaccountMsgReplyRule as OfficialaccountMsgReplyRuleModel;
use yii\base\Model;
use yii\data\Pagination;

/**
 * OfficialaccountMsgReplyRule represents the model behind the search form of
 * `common\modules\officialaccount\models\OfficialaccountMsgReplyRule`.
 */
class OfficialaccountMsgReplyRule extends OfficialaccountMsgReplyRuleModel
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['rule_id', 'exact_match', 'status', 'priority'], 'integer'],
            [['appid', 'rule_name', 'match_value', 'reply_type', 'reply_content', 'desc', 'effect_time_start', 'effect_time_end', 'create_time', 'update_time'], 'safe'],
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
     * @return array|bool
     */
    public function search(array $params): array|bool
    {
        $query = OfficialaccountMsgReplyRuleModel::find();


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return false;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'rule_id'     => $this->rule_id,
            'exact_match' => $this->exact_match,
            'status'      => $this->status,
            'priority'    => $this->priority,
        ]);

        $query->andFilterWhere(['like', 'appid', $this->appid])
            ->andFilterWhere(['like', 'rule_name', $this->rule_name])
            ->andFilterWhere(['like', 'match_value', $this->match_value])
            ->andFilterWhere(['like', 'reply_type', $this->reply_type])
            ->andFilterWhere(['like', 'reply_content', $this->reply_content])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'effect_time_start', $this->effect_time_start])
            ->andFilterWhere(['like', 'effect_time_end', $this->effect_time_end])
            ->andFilterWhere(['like', 'create_time', $this->create_time])
            ->andFilterWhere(['like', 'update_time', $this->update_time]);
        $query->findBlocs();

        $count    = $query->count();
        $pageSize = \Yii::$app->request->input('pageSize', 20);
        $page     = \Yii::$app->request->input('page');
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize'   => $pageSize,
            'page'       => $page - 1,
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
            'key'        => 'id',
            'allModels'  => $list,
            'totalCount' => $count,
            'total'      => $count,
            'sort'       => [
                'attributes'   => [
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
        return $provider->toArray();

    }
}
