<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2020-05-12 20:49:40
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-19 11:17:14
 */

namespace common\models;

use common\traits\ActiveQuery\StoreTrait;
use diandi\addons\models\Bloc;
use diandi\addons\models\BlocStore;
use diandi\addons\models\UserStore as ModelsUserStore;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "diandi_user_bloc".
 *
 * @public int         $id
 * @public int|null    $user_id     管理员id
 * @public int|null    $bloc_id     集团id
 * @public int|null    $store_id    子公司id
 * @public string|null $create_time
 * @public string|null $update_time
 */
class UserStore extends ModelsUserStore
{
    use StoreTrait;
}
