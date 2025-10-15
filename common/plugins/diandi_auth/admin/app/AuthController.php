<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-16 11:38:40
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-17 10:08:37
 */


namespace common\plugins\diandi_auth\admin\app;

use admin\controllers\AController;

class AuthController extends AController
{
    public string $modelSearchName = "DiandiAuth";
    public int $searchLevel = 1;
    public function actionListByRule() {}
}
