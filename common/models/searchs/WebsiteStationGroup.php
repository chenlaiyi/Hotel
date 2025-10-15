<?php

namespace common\models\searchs;

use common\components\DataProvider\ArrayDataProvider;
use common\helpers\ImageHelper;
use common\models\WebsiteStationGroup as WebsiteStationGroupModel;
use yii\base\Model;
use yii\data\Pagination;

/**
 * WebsiteStationGroup represents the model behind the search form of `common\models\WebsiteStationGroup`.
 */
class WebsiteStationGroup extends WebsiteStationGroupModel
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'status', 'bloc_id', 'store_id'], 'integer'],
            [['flogo', 'blogo', 'domain_url', 'name', 'intro', 'keywords', 'description', 'footerleft', 'footerright', 'location', 'icp', 'create_time', 'update_time', 'mobile', 'city', 'company_name', 'wechat'], 'safe'],
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
        $query = WebsiteStationGroupModel::find();


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return false;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'          => $this->id,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'status'      => $this->status,
            'bloc_id'     => $this->bloc_id,
            'store_id'    => $this->store_id,
        ]);

        $query->andFilterWhere(['like', 'flogo', $this->flogo])
            ->andFilterWhere(['like', 'blogo', $this->blogo])
            ->andFilterWhere(['like', 'domain_url', $this->domain_url])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'intro', $this->intro])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'footerleft', $this->footerleft])
            ->andFilterWhere(['like', 'footerright', $this->footerright])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'icp', $this->icp])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'wechat', $this->wechat]);

        $count    = $query->count();
        $pageSize = \Yii::$app->request->input('pageSize');
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

        foreach ($list as $key => &$value) {
            $value['flogo'] = ImageHelper::tomedia($value['flogo']);
            $value['blogo'] = ImageHelper::tomedia($value['blogo']);
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
