<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-04-22 15:01:51
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-20 09:46:15
 */

namespace common\traits\ActiveQuery;

use admin\services\UserService;
use common\models\User;
use common\models\UserStore;
use diandi\addons\models\BlocStore;
use diandi\addons\models\UserBloc;
use Yii;
use yii\db\ActiveQuery;

class CommonQueryback extends ActiveQuery
{
    public int $bloc_id;

    public int $store_id;

    public int $department_id;

    public array $user = [];


    public function init(): void
    {
        parent::init();
        $this->bloc_id = (int)(Yii::$app->request->input('bloc_id', 0));
        $this->store_id = (int)(Yii::$app->request->input('store_id', 0));
        $user =  User::find()->where(['id' => Yii::$app->user->identity->user_id ?? 0])->asArray()->one();
        $this->user = $user??[];
    }

    public function findBloc($alias = ''): static
    {

        if ($this->where) {
            $this->andWhere([($alias ? $alias . '.' : '') . 'bloc_id' => $this->bloc_id]);
        } else {
            $this->where([($alias ? $alias . '.' : '') . 'bloc_id' => $this->bloc_id]);
        }
        return $this;
    }

    public function findStore($alias = ''): static
    {
        if ($this->where) {
            $this->andWhere([($alias ? $alias . '.' : '') . 'store_id' => $this->store_id, ($alias ? $alias . '.' : '') . 'bloc_id' => $this->bloc_id]);
        } else {
            $this->where([($alias ? $alias . '.' : '') . 'store_id' => $this->store_id, ($alias ? $alias . '.' : '') . 'bloc_id' => $this->bloc_id]);
        }
        return $this;
    }

    function findDepartments($alias = ''): static
    {
        /**
         * 如果是部门领导
         */
        $department_ids = UserStore::find()->where(['user_id' => Yii::$app->user->identity->user_id ?? 0])->select('department_id')->groupBy('department_id')->column();
        if ($this->where) {
            $this->andWhere([($alias ? $alias . '.' : '') . 'department_id' => $department_ids]);
        } else {
            $this->where([($alias ? $alias . '.' : '') . 'department_id' => $department_ids]);
        }
        return $this;

    }

    function findUserLinkUid($field = 'admin_id',$alias=''): static
    {
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        if ($this->user['is_super_admin'] != 1 && $this->user['is_business_admin'] != 1) {
            $user_ids = UserService::getAuthUserLink($user_id);

            if ($this->where) {
                $this->andWhere([($alias ? $alias . '.' : '') . $field => $user_ids]);
            } else {
                $this->where([($alias ? $alias . '.' : '') . $field => $user_ids]);
            }
        }
        return $this;

    }

    function findUserLinkDepartmentId($field = 'department_id',$alias=''): static
    {
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        $user_ids = UserService::getAuthUserLink($user_id);
        $department_ids = UserStore::find()->where(['user_id' => $user_ids])->select('department_id')->groupBy('department_id')->column();
        if ($this->where) {
            $this->andWhere([($alias ? $alias . '.' : '') . $field => $department_ids]);
        } else {
            $this->where([($alias ? $alias . '.' : '') . $field => $department_ids]);
        }
        return $this;
    }


    function findUserLinkStore($field = 'store_id',$alias=''): static
    {
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        if ($this->user['is_super_admin'] == 1){
           return $this;
        }else if($this->user['is_business_admin'] == 1){
            $bloc_id = $this->user['bloc_id'];
            $store_ids = BlocStore::find()->where(['bloc_id' => $bloc_id])->select('store_id')->groupBy('store_id')->column();
            if (empty($store_ids)){
                return $this;
            }
//            $user_ids = UserService::getAuthUserLink($user_id);
//            /**
//             * store_id,department_id,admin_id
//             * 获取当前用户与关联用户store_id 的合集
//             * 获取当前用户与关联用户department_id 的合集
//             * 获取当前用户与关联用户admin_id 的合集
//             */
//            $store_ids = UserStore::find()->where(['user_id' => $user_ids])->select('store_id')->groupBy('store_id')->column();
        }else{
            $user_ids = UserService::getAuthUserLink($user_id);
            /**
             * store_id,department_id,admin_id
             * 获取当前用户与关联用户store_id 的合集
             * 获取当前用户与关联用户department_id 的合集
             * 获取当前用户与关联用户admin_id 的合集
             */
            $store_ids = UserStore::find()->where(['user_id' => $user_ids])->select('store_id')->groupBy('store_id')->column();

        }


        // 确保 $alias 是字符串类型，并去除前后空白
        $alias = is_string($alias) ? trim($alias) : '';
        if ($this->where) {
            $this->andWhere([($alias ? $alias . '.' : '') . $field => $store_ids]);
        } else {
            $this->where([($alias ? $alias . '.' : '') . $field => $store_ids]);
        }
        return $this;
    }


    /**
     * 根据用户授权进行检索
     * @param string $alias
     * @return CommonQuery
     * @date 2023-03-03
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function findBlocs(string $alias = ''): static
    {
        $is_super_admin = $this->user['is_super_admin'] ?? 0;
        if ($is_super_admin == 1){
            return $this;
        }
        $bloc_ids = UserBloc::find()->where([
            'user_id' => Yii::$app->user->identity->user_id ?? 0
        ])->select('bloc_id')->groupBy('bloc_id')->column();

        if ($this->where) {
            $this->andWhere([($alias ? $alias . '.' : '') . 'bloc_id' => $bloc_ids]);
        } else {
            $this->where([($alias ? $alias . '.' : '') . 'bloc_id' => $bloc_ids]);
        }
        return $this;
    }

    /**
     * 根据bloc_id和admin_id进行检索
     * @param string $alias
     * @return CommonQuery
     * @date 2025/3/11 22:52
     * @example
     * @author 大军  @E-Mail：512826@qq.com
     * @since
     */
    public function findBlocByAdmin(string $alias = ''): static
    {
        if ($this->where) {
            $this->andWhere([($alias ? $alias . '.' : '') . 'bloc_id' => Yii::$app->user->identity->bloc_id, ($alias ? $alias . '.' : '') . 'admin_id' => Yii::$app->user->identity->id]);
        } else {
            $this->where([($alias ? $alias . '.' : '') . 'bloc_id' => Yii::$app->user->identity->bloc_id, ($alias ? $alias . '.' : '') . 'admin_id' => Yii::$app->user->identity->id]);
        }
        return $this;
    }


    /**
     * 根据用户授权的商户进行检索
     * @param string $alias
     * @return CommonQuery
     * @date 2023-03-03
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function findStores(string $alias = ''): static
    {
        $is_super_admin = $this->user['is_super_admin'] ?? 0;
        $is_business_admin = $this->user['is_business_admin'] ?? 0;
        if ($is_super_admin == 1){
            return $this;
        }

        /**
         *
         */
        if ($is_business_admin){
            $bloc_ids = UserBloc::find()->where(['user_id' => Yii::$app->user->identity->user_id ?? 0])->select('bloc_id')->groupBy('bloc_id')->column();
            if ($this->where) {
                $this->andWhere([($alias ? $alias . '.' : '') . 'bloc_id' => $bloc_ids]);
            } else {
                $this->where([($alias ? $alias . '.' : '') . 'bloc_id' => $bloc_ids]);
            }
            return $this;
        }
        $bloc_ids = UserBloc::find()->where(['user_id' => Yii::$app->user->identity->user_id ?? 0])->select('bloc_id')->groupBy('bloc_id')->column();

        $store_ids = UserStore::find()->where(['user_id' => Yii::$app->user->identity->user_id ?? 0])->select('store_id')->groupBy('store_id')->column();
        if ($this->where) {
            $this->andWhere([($alias ? $alias . '.' : '') . 'store_id' => $store_ids, ($alias ? $alias . '.' : '') . 'bloc_id' => $bloc_ids]);
        } else {
            $this->where([($alias ? $alias . '.' : '') . 'store_id' => $store_ids, ($alias ? $alias . '.' : '') . 'bloc_id' => $bloc_ids]);
        }
        return $this;
    }


    /**
     * 根据用户授权的商户进行检索
     * @param array $defaultSort
     * @return CommonQuery
     * @date 2023-03-03
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function findBySort(string $alias = '',array $defaultSort = []): static
    {
        $post = Yii::$app->request->input();
        $sort = isset($post["sort"])?json_decode($post["sort"], true):[];
        $data = [];
        /**
         * ["create_time" => "desc"]
         * ["create_time" => "asc"]
         */
        if (!empty($sort)) {
            foreach ($sort as $k => $v) {
                if (is_string($k) && is_string($v)) {
                    if ($alias){
                        $k = $alias . "." . $k;
                    }
                    if ($v == "desc") {
                        $data[$k] = SORT_DESC;
                    } else {
                        $data[$k] = SORT_ASC;
                    }
                }
            }
        }
        if (isset($sort) && $data) {
            $param["sort"]["defaultOrder"] = $data;
        }

        if ($data) {
            $this->orderBy($data);
        } else {
            if ($defaultSort) {
                $this->orderBy($defaultSort);
            }
        }
        return $this;
    }
}
