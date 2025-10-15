<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-01-31 08:18:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-05-30 10:03:57
 */
namespace addons\diandi_place\admin;
use addons\diandi_place\services\EnumService;
use api\controllers\AController;
use common\helpers\ResultHelper;
/**
 * 枚举
 * @date 2023-05-30
 * @example
 * @author Wang Chunsheng
 * @since
 */
class EnumsController extends AController
{
    public $modelClass = '';
    protected array $authOptional = ['*'];
    public function actionIndex(): array
   {
        $list = EnumService::getEnums();
        return ResultHelper::json(200, '请求成功', $list);
    }
}
