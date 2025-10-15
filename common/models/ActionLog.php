<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-07-29 18:17:57
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-19 11:17:55
 */

namespace common\models;

use diandi\addons\models\ActionLog as ModelsActionLog;

/**
 * This is the model class for table "{{%user_action_log}}".
 *
 * @public int         $id
 * @public string|null $user      用户
 * @public string|null $operation 操作
 * @public string|null $logtime   操作时间
 * @public string|null $logip     操作ip
 */
class ActionLog extends ModelsActionLog {}
