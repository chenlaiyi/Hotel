<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-07-17 20:08:49
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-07-17 20:55:42
 */


namespace admin\traits;


use admin\controllers\UserController;
use admin\modules\customer\controllers\UserController as ControllersUserController;
use ReflectionClass;
use Yii;

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-07-17 20:08:49
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-07-17 20:16:44
 */
trait UserTrait
{
    public function beforeAction($action): bool
    {
        $currentClass = get_class($this);
        $params = Yii::$app->request->getBodyParams(); // 使用 getBodyParams() 替代 input()

        // 检查是否需要重定向到其他控制器
        if (in_array($currentClass, [UserController::class, ControllersUserController::class])) {
            $userType = Yii::$app->params['userType'] ?? null;

            if ($userType === 'admin' && $currentClass === ControllersUserController::class) {
                // 重定向到 admin 用户控制器
                $this->redirectToController('user', $action->id, $params);
                return false; // 终止当前动作执行
            }

            if ($userType === 'customer' && $currentClass === UserController::class) {
                // 重定向到 customer 用户控制器
                $this->redirectToController('customer/user', $action->id, $params);
                return false;
            }
        }

        return parent::beforeAction($action);
    }

    /**
     * 重定向到指定控制器并输出响应
     */
    protected function redirectToController(string $controller, string $action, array $params = [])
    {
        // 创建新的请求对象
        $request = Yii::$app->request;
        $newRequest = clone $request;
        $newRequest->setBodyParams($params);

        // 保存原始响应对象
        $originalResponse = Yii::$app->response;

        try {
            // 创建新的响应对象
            $response = new \yii\web\Response();
            Yii::$app->set('response', $response);

            // 执行目标控制器动作
            $result = Yii::$app->runAction("{$controller}/{$action}", $params);

            // 处理结果
            if ($result instanceof \yii\web\Response) {
                $result->send();
            } else {
                $response->data = $result;
                $response->send();
            }
            die;
        } finally {
            // 恢复原始响应对象
            Yii::$app->set('response', $originalResponse);
        }

        // 终止当前请求处理
        Yii::$app->end();
    }
}
