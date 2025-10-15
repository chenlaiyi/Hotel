<?php
namespace addons\diandi_place\backend;
use backend\controllers\BaseController;
use common\services\common\AddonsService;
/**
* Default controller for the `diandi_place` module
*/
class DefaultController extends BaseController
{
/**
* Renders the index view for the module
* @return string
*/
public function actionIndex()
{
    $info = AddonsService::getAddonsInfo("diandi_place");
    return $this->render('index',[
        'info'=>$info
    ]);
}
}