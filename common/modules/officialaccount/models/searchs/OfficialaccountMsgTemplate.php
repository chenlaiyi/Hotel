<?php

namespace common\modules\officialaccount\models\searchs;

use common\components\DataProvider\ArrayDataProvider;
use common\modules\officialaccount\models\OfficialaccountMsgTemplate as OfficialaccountMsgTemplateModel;
use Yii;
use yii\base\Model;
use yii\data\Pagination;

/**
 * OfficialaccountMsgTemplate represents the model behind the search form of
 * `common\modules\officialaccount\models\OfficialaccountMsgTemplate`.
 */
class OfficialaccountMsgTemplate extends OfficialaccountMsgTemplateModel
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'status'], 'integer'],
            [['template_id', 'title', 'content', 'example', 'create_time', 'update_time', 'sign', 'remark'], 'safe'],
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
     * Creates example provider instance with search query applied
     *
     * @param array $params
     *
     * @return array|bool
     */
    public function search(array $params): array|bool
    {
        $query = OfficialaccountMsgTemplateModel::find();


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return false;
        }

        // grid filtering conditions
        $query->andFilterWhere([
                'id'     => $this->id,
                'status' => $this->status,
            ]
        );

        $query->andFilterWhere(['like', 'template_id', $this->template_id])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'example', $this->example])
            ->andFilterWhere(['like', 'create_time', $this->create_time])
            ->andFilterWhere(['like', 'update_time', $this->update_time]);
        $query->findBlocs();

        $count    = $query->count();
        $pageSize = Yii::$app->request->input('pageSize', 20);
        $page     = Yii::$app->request->input('page');
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
                'totalCount' => $count,
                'pageSize'   => $pageSize,
                'page'       => $page - 1,
                // 'pageParam'=>'page'
            ]
        );

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
            ]
        );
        return $provider->toArray();

    }
}
