<?php

namespace common\modules\officialaccount\models\searchs;

use common\components\DataProvider\ArrayDataProvider;
use common\helpers\ImageHelper;
use common\modules\officialaccount\models\OfficialaccountWechatMedia as OfficialaccountWechatMediaModel;
use yii\base\Model;
use yii\data\Pagination;

/**
 * OfficialaccountWechatMedia represents the model behind the search form of
 * `common\modules\officialaccount\models\OfficialaccountWechatMedia`.
 */
class OfficialaccountWechatMedia extends OfficialaccountWechatMediaModel
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'bloc_id', 'store_id', 'status'], 'integer'],
            [['filename', 'result'], 'safe'],
            [['type'], 'string', 'max' => 50]
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
        $query = OfficialaccountWechatMediaModel::find();


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return false;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'       => $this->id,
            'bloc_id'  => $this->bloc_id,
            'store_id' => $this->store_id,
            'type'     => $this->type,
            'status'   => $this->status,
        ]);

        $query->andFilterWhere(['like', 'filename', $this->filename])
            ->andFilterWhere(['like', 'result', $this->result]);
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
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->all();

        foreach ($list as $key => &$value) {
            $value['local_url'] = ImageHelper::tomedia($value['local_url']);
//            $value['update_time'] = date('Y-m-d H:i:s',$value['update_time']);
        }


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
