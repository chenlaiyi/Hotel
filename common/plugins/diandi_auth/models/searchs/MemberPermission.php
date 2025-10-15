<?php


namespace common\plugins\diandi_auth\models\searchs;


use common\components\DataProvider\ArrayDataProvider;
use common\plugins\diandi_auth\models\MemberPermission as ZyjMemberPermissionModel;
use yii\base\Model;
use yii\data\Pagination;


/**
 * ZyjMemberPermission represents the model behind the search form of `\plugins\diandi_auth\models\ZyjMemberPermission`.
 */
class MemberPermission extends ZyjMemberPermissionModel
{

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'bloc_id', 'store_id', 'nav_id'], 'integer'],
            [['name', 'tag', 'create_time', 'update_time'], 'safe'],
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
        $query = ZyjMemberPermissionModel::find()->with(['nav', 'children']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return false;
        }

        $query->where(['pid' => 0]);


        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'tag', $this->tag]);
        $list = $query->asArray()->all();

        $count    = $query->count();
        $pageSize = \Yii::$app->request->input('pageSize');
        $page     = \Yii::$app->request->input('page');
        // 使用总数来创建一个分页对象

        $pagination = new Pagination([
                'totalCount' => $count,
                'pageSize'   => $pageSize,
                'page'       => $page - 1,
                // 'pageParam'=>'page'
            ]
        );


//        $ids = $query->offset($pagination->offset)
//            ->select('id')
//            ->column();

//        $all_ids = self::getChildIds($ids, $ids);

//        $list = ZyjMemberPermissionModel::find()->where(['id' => $all_ids])->findBloc()->with(['nav'])->asArray()->all();
//
//        $lists = ArrayHelper::itemsMerge($list, 0, 'id', 'pid', 'children');


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


    public function getChildIds($ids, $data = [])
    {

        $ids = ZyjMemberPermissionModel::find()->select('id')->where(['pid' => $ids])->column();
        if (!empty($ids)) {
            $data = array_merge($data, $ids);
            self::getChildIds($ids, $data);
        }
        return $data;
    }

}

