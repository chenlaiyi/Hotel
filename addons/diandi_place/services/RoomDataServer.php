<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-06-04 11:01:13
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-06-27 16:17:32
 */
namespace addons\diandi_place\services;
use addons\diandi_distribution\services\events\DdOrderEvent;
use addons\diandi_place\models\room\PlaceRoomServer;
use common\helpers\ErrorsHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\services\BaseService;
use Throwable;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
class RoomDataServer extends BaseService
{
    public static array $listeners = [
        // 订单创建事件
        DdOrderEvent::EVENT_ORDER_CREATE => [
            ['createOrder', 2],
            ['createOrderAddons', 1],
        ],
    ];
    public static function createOrder(DdOrderEvent $event): void
    {
        loggingHelper::writeLog('diandi_place', 'createOrder', '订单创建事件', [
            'event'=>$event
        ]);
    }
    public static function add(string $title, int $room_type_id = 0, int $room_id = 0): array
    {
        $PlaceRoomServer = new PlaceRoomServer();
        $PlaceRoomServer->load([
            'title' => $title,
            'room_type_id' => $room_type_id,
            'room_id' => $room_id
        ], '');
        if (!$PlaceRoomServer->save()) {
            $msg = ErrorsHelper::getModelError($PlaceRoomServer);
            return ResultHelper::json(400, $msg);
        }
        return $PlaceRoomServer->toArray();
    }
    /**
     * 添加房间服务
     * @param array $servers
     * @param int $room_id
     * @param int $room_type_id
     * @return array
     * @throws NotFoundHttpException
     */
    public static function adds(array $servers, int $room_id = 0, int $room_type_id = 0): array
    {
        $list = [];
        if ($servers) {
            $PlaceRoomServer = new PlaceRoomServer();
            foreach ($servers as $v) {
                $_PlaceRoomServer = clone $PlaceRoomServer;
                $data = [
                    'room_id' => $room_id,
                    'room_type_id' => $room_type_id,
                    'title' => $v['label'],
                ];
                $_PlaceRoomServer->setAttributes($data);
                $_PlaceRoomServer->save();
                $msg = ErrorsHelper::getModelError($_PlaceRoomServer);
                if ($msg) {
                    return ResultHelper::json(400, $msg, []);
                } else {
                    $list[] = $data;
                }
            }
        }
        return $list;
    }
    public static function del(int $id): array
    {
        $isHave = PlaceRoomServer::findOne($id);
        if (empty($isHave)) {
            return ResultHelper::json(400, '服务项不存在');
        }
        try {
            if ($isHave->delete()) {
                return ResultHelper::json(200, '服务项删除成功');
            } else {
                return ResultHelper::json(200, '服务项删除失败');
            }
        } catch (StaleObjectException $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        } catch (Throwable $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        }
    }
    public static function addOrDel(array $server, int $room_type_id = 0, int $room_id = 0): array|bool
    {
        loggingHelper::writeLog('diandi_place', 'addOrDel', '服务添加', $server);
        $PlaceRoomServer = new PlaceRoomServer();
        $server_ids = [];
        if ($room_id) {
            // 房间处理
            foreach ($server as $k => $v) {
                if (isset($v['server_id'])) {
                    // 房间选择房型
                    $server_ids[] = $v['server_id'];
                }
            }
            $server_have_ids = $PlaceRoomServer->find()->where(['room_id' => $room_id])->findBloc()->select('id')->column();
            // 删掉差异
            $delIds = array_diff($server_have_ids, $server_ids);
            loggingHelper::writeLog('diandi_place', 'addOrDel', '比对需要删除的', [
                'server_have_ids' => $server_have_ids,
                'server_ids' => $server_ids,
                'delIds' => $delIds,
                'serverArr' => $server,
            ]);
            if ($delIds) {
                $PlaceRoomServer->deleteAll([
                    'and',
                    ['id' => $delIds],
                    ['room_id' => $room_id]
                ]);
            }
            if ($server) {
                foreach ($server as $v) {
                    $_PlaceRoomServer = clone $PlaceRoomServer;
                    if (!in_array($v['server_id'], $server_have_ids)) {
                        $_PlaceRoomServer->setAttributes([
                            'title' => $v['title'],
                            'room_type_id' => $room_type_id,
                            'room_id' => $room_id
                        ]);
                        $_PlaceRoomServer->save();
                        $msg = ErrorsHelper::getModelError($_PlaceRoomServer);
                        if ($msg) {
                            return ResultHelper::json(400, $msg, []);
                        }
                    }
                }
            }
        } else {
            // 房型处理
            foreach ($server as $k => $v) {
                if (isset($v['server_id'])) {
                    $server_ids[] = $v['server_id'];
                    // unset($serverArr[$k]);
                }
            }
            // 查找已有的
            $server_have_ids = $PlaceRoomServer->find()->where(['room_type_id' => $room_type_id])->findBloc()->select('id')->column();
            // 删掉差异
            $delIds = array_diff($server_have_ids, $server_ids);
            loggingHelper::writeLog('diandi_place', 'addOrDel', '比对需要删除的', [
                'server_have_ids' => $server_have_ids,
                'server_ids' => $server_ids,
                'delIds' => $delIds,
                'serverArr' => $server,
            ]);
            if ($delIds) {
                $where = [];
                if ($room_type_id) {
                    $where['room_type_id'] = $room_type_id;
                }
                $PlaceRoomServer->deleteAll([
                    'and',
                    ['id' => $delIds],
                    $where
                ]);
                loggingHelper::writeLog('diandi_place', 'addOrDel', '刪除条件', [
                    'where' => $where,
                    'sql' => $PlaceRoomServer->find()->where(['id' => $delIds])->andFilterWhere($where)->createCommand()->getRawSql(),
                ]);
            }
            if ($server) {
                try {
                    foreach ($server as $v) {
                        $_PlaceRoomServer = clone $PlaceRoomServer;
                        if ($v['server_id']) {
                            $have = $_PlaceRoomServer->findOne($v['server_id']);
                            $have->title = $v['title'];
                            $have->room_type_id = $room_type_id;
                            $have->room_id = $room_id;
                            $have->update();
                        } else {
                            $_PlaceRoomServer->setAttributes([
                                'title' => $v['title'],
                                'room_type_id' => $room_type_id,
                                'room_id' => $room_id
                            ]);
                            $_PlaceRoomServer->save();
                            $msg = ErrorsHelper::getModelError($_PlaceRoomServer);
                            if ($msg) {
                                return ResultHelper::json(400, $msg, []);
                            }
                        }
                    }
                } catch (StaleObjectException $e) {
                    return ResultHelper::json(400, $e->getMessage(), (array)$e);
                } catch (Throwable $e) {
                    return ResultHelper::json(400, $e->getMessage(), (array)$e);
                }
            }
        }
        return true;
    }
}
