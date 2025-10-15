<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-26 12:50:48
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-19 15:06:33
 */

namespace common\middlewares;

use common\events\AddonsEvent;
use diandi\admin\components\Helper;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Module;
use yii\di\Instance;
use yii\web\ForbiddenHttpException;
use yii\web\User;

/**
 * Access Control Filter (ACF) is a simple authorization method that is best used by applications that only need some simple access control.
 * As its name indicates, ACF is an action filter that can be attached to a controllers or a module as a behavior.
 * ACF will check a set of access rules to make sure the current user can access the requested action.
 *
 * To use AccessControl, declare it in the application config as behavior.
 * For example.
 *
 * ```
 * 'as access' => [
 *     'class' => 'diandi/admin\components\AccessControl',
 *     'allowActions' => ['site/login', 'site/error']
 * ]
 * ```
 *
 * @property User $user
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 *
 * @since 1.0
 */
class AccessControl extends \yii\base\ActionFilter
{
    /**
     * @var User User for check access.
     */
    private $_user = 'user';
    /**
     * @var array List of action that not need to check access.
     */
    public array $allowActions = [];

    /**
     * Get user.
     *
     * @return User
     * @throws InvalidConfigException
     */
    public function getUser(): User
    {
        if (!$this->_user instanceof User) {
            $this->_user = Instance::ensure($this->_user, User::class);
        }

        return $this->_user;
    }

    /**
     * Set user.
     *
     * @param string|User $user
     */
    public function setUser(User|string $user): void
    {
        $this->_user = $user;
    }

    public function addonsEvent()
    {
        Yii::$app->on(AddonsEvent::EVENT_ADDONS, function ($event) {
            $addons = $event->addons;
            $method = $event->method;
            $params = $event->params;
            foreach ($addons as $addon) {
                if (Yii::$app->hasModule($addon) && method_exists(Yii::$app->getModule($addon), $method)){
                    Yii::$app->getModule($addon)->$method($params);
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     * @throws ForbiddenHttpException|InvalidConfigException
     */
    public function beforeAction($action)
    {
        $this->addonsEvent();
        $actionId = $action->getUniqueId();
        $user = $this->getUser();
        Yii::info([
            'getArr' => Yii::$app->getRequest()->get()
        ], 'checkRoute');
        if (Helper::checkRoute('/' . $actionId, Yii::$app->getRequest()->get(), $user)) {
            return true;
        }
        $this->denyAccess($user);
    }


    /**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     *
     * @param User $user the current user
     *
     * @throws ForbiddenHttpException if the user is already logged in.
     */
    protected function denyAccess(User $user)
    {
        if ($user->getIsGuest()) {
            throw new ForbiddenHttpException(Yii::t('yii', 'Login Required'));
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function isActive($action): bool
    {
        $uniqueId = $action->getUniqueId();
        if ($uniqueId === Yii::$app->getErrorHandler()->errorAction) {
            return false;
        }

        $user = $this->getUser();
        if ($user->getIsGuest()) {
            $loginUrl = null;
            if (is_array($user->loginUrl) && isset($user->loginUrl[0])) {
                $loginUrl = $user->loginUrl[0];
            } elseif (is_string($user->loginUrl)) {
                $loginUrl = $user->loginUrl;
            }
            if (!is_null($loginUrl) && trim($loginUrl, '/') === $uniqueId) {
                return false;
            }
        }

        if ($this->owner instanceof Module) {
            // convert action uniqueId into an ID relative to the module
            $mid = $this->owner->getUniqueId();
            $id = $uniqueId;
            if ($mid !== '' && str_starts_with($id, $mid . '/')) {
                $id = substr($id, strlen($mid) + 1);
            }
        } else {
            $id = $action->id;
        }

        foreach ($this->allowActions as $route) {
            if (str_ends_with($route, '*')) {
                $route = rtrim($route, '*');
                if ($route === '' || str_starts_with($id, $route) || str_starts_with($uniqueId, $route)) {
                    return false;
                }
            } else {
                if ($id === $route || $uniqueId === $route) {
                    return false;
                }
            }
        }

        if ($action->controller->hasMethod('allowAction') && (in_array($action->id, $action->controller->allowAction()) || in_array('*', $action->controller->allowAction()))) {
            return false;
        }

        return true;
    }
}
