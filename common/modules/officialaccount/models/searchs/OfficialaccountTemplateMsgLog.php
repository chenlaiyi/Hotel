<?php

namespace common\modules\officialaccount\models\searchs;

use common\components\DataProvider\ArrayDataProvider;
use common\modules\officialaccount\models\OfficialaccountTemplateMsgLog as OfficialaccountTemplateMsgLogModel;
use yii\base\Model;
use yii\data\Pagination;

/**
 * OfficialaccountTemplateMsgLog represents the model behind the search form of
 * `common\modules\officialaccount\models\OfficialaccountTemplateMsgLog`.
 */
class OfficialaccountTemplateMsgLog extends OfficialaccountTemplateMsgLogModel
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['log_id'], 'integer'],
            [['appid', 'touser', 'template_id', 'data', 'url', 'miniprogram', 'send_time', 'send_result'], 'safe'],
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
        $query = OfficialaccountTemplateMsgLogModel::find();


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return false;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'log_id' => $this->log_id,
        ]);

        $query->andFilterWhere(['like', 'appid', $this->appid])
            ->andFilterWhere(['like', 'touser', $this->touser])
            ->andFilterWhere(['like', 'template_id', $this->template_id])
            ->andFilterWhere(['like', 'data', $this->data])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'miniprogram', $this->miniprogram])
            ->andFilterWhere(['like', 'send_time', $this->send_time])
            ->andFilterWhere(['like', 'send_result', $this->send_result]);
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
