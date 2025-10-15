<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-04-27 03:17:29
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-10 13:37:16
 */

namespace admin\controllers;

use admin\services\StoreService;
use common\helpers\ResultHelper;
use Yii;

class EnumsController extends AController
{
    public          $modelClass   = '';
    protected array $authOptional = ['*'];

    public int $bloc_id;

    public array $extras = [];

    public int $searchLevel = 0;

    /**
     * 获取用户授权的商户和公司数据-级联数据
     *
     * @return array
     * @date 2023-03-04
     * @example
     * @author Wang Chunsheng
     * @since
     */
    public function actionStoresbloc(): array
    {
        $user_id = Yii::$app->user->id ?: 0;
        $bloc_id = Yii::$app->request->input('bloc_id', 0);
        $list    = Yii::$app->cache->get('Storesbloc-' . $user_id . 'bloc-id' . $bloc_id);
        if ($list) {
            return ResultHelper::json(200, '获取成功', (array) $list);
        }

        $list = StoreService::getStoresAndBloc();
        Yii::$app->cache->set('Storesbloc-' . $user_id . 'bloc-id' . $bloc_id, $list, 3600);
        return ResultHelper::json(200, '获取成功', $list);
    }

    public function actionBlocs(): array
    {
        $user_id = Yii::$app->user->identity->user_id ?: 0;
        $bloc_id = Yii::$app->request->input('bloc_id', 0);
        $list    = Yii::$app->cache->get('blocsall_' . $user_id . 'bloc-id' . $bloc_id);
        if ($list) {
            return ResultHelper::json(200, '获取成功', (array) $list);
        }
        $list = StoreService::getAuthBlos();
        Yii::$app->cache->set('blocsall_' . $user_id . 'bloc-id' . $bloc_id, $list, 3600);
        return ResultHelper::json(200, '获取成功', (array) $list);
    }

    public function actionStores(): array
    {
        $user_id = Yii::$app->user->id ?? 0;
        $bloc_id = Yii::$app->request->input('bloc_id', 0);
        $list    = Yii::$app->cache->get('stores_' . $user_id . 'bloc-id' . $bloc_id);

        if ($list) {
            return ResultHelper::json(200, '获取成功', (array) $list);
        }

        // var_dump($user_id, $bloc_id);exit;

        $list = StoreService::getAuthStores($user_id);
        Yii::$app->cache->set('stores_' . $user_id . 'bloc-id' . $bloc_id, $list, 3600);
        return ResultHelper::json(200, '获取成功', (array) $list);
    }

    public function actionStoreList(): array
    {
        $page     = Yii::$app->request->get('page', 1);
        $pageSize = Yii::$app->request->get('pageSize', 10);
        $keywords = Yii::$app->request->get('keywords', '');
        $bloc_id  = Yii::$app->request->get('bloc_id', '');
        $list     = StoreService::getAuthStoreList($page, $pageSize, $bloc_id, $keywords);
        return ResultHelper::json(200, '获取成功', (array) $list);
    }
}
