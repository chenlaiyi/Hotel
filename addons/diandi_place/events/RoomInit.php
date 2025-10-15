<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-05-22 00:56:37
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-06-16 19:50:22
 */
namespace addons\diandi_place\events;
use common\components\events\DdHandleAddonsMethodEvent;
use common\helpers\loggingHelper;
/**
 * The order.placed event is dispatched each time an order is created
 * in the system. 每当系统新建订单时，order.placed事件都会被派遣.
 * 需要跨越模块调度的集成DdHandleAddonsMethodEvent，不需要跨越的集成DdEvent.
 */
class RoomInit extends DdHandleAddonsMethodEvent
{
    const EVENT_NAME = 'diandi_place.room.insert';
    const EVENT_ORDER_CREATE = 'diandi_distribution.order_create';
    protected $user_id;
    protected $extend;
    public function __construct($user_id, $extend = [])
    {
        loggingHelper::writeLog('diandi_place', 'RoomInit', 'RoomInit', [
            'user_id' => $user_id,
            'extend' => $extend,
        ]);
        $this->user_id = $user_id;
        $this->extend = $extend;
    }
    public function getOrder()
    {
        return $this->order;
    }
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
    }
}
