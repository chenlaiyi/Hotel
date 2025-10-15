<?php

namespace common\plugins\diandi_website\models\searchs;

use common\plugins\diandi_website\models\enums\InquiryTypeEnum;
use yii\base\Model;
use common\components\DataProvider\ArrayDataProvider;
use common\plugins\diandi_website\models\WebsiteFeedback as WebsiteFeedbackModel;
use yii\data\Pagination;


/**
 * WebsiteFeedback represents the model behind the search form of `common\plugins\diandi_website\models\WebsiteFeedback`.
 */
class WebsiteFeedback extends WebsiteFeedbackModel
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['inquiry_type', 'name', 'contact', 'email', 'remark','company_name'], 'safe'],
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
     * @return array|bool

     */
    public function search($params)
   {
        $query = WebsiteFeedbackModel::find();

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return false;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id
        ]);

        $query->andFilterWhere(['like', 'inquiry_type', $this->inquiry_type])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'contact', $this->contact])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'remark', $this->remark]);
        
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
            ->orderBy('id desc')
            ->asArray()
            ->all();
        
        foreach ($list as $key => &$value) {
            $value['status_label'] = $value['status'] === 1?'未处理':'已处理';
            $value['inquiry_type'] = InquiryTypeEnum::getLabel($value['inquiry_type']);
        }
            

        $provider = new ArrayDataProvider([
            'key'=>'id',
            'allModels' => $list,
            'totalCount' => $count ?? 0,
            'total'=> $count ?? 0,
            'sql' => $query->createCommand()->getRawSql(),
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
        
        return $provider->toArray();
        
    }
}
