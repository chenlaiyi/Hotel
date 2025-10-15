/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-10-13 15:55:11
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-10-18 14:34:15
 */
import request from '@projectName/utils/request'

export function getorderlist(data) {
  return request({
    url: '/diandi_hub/api/goods/lists',
    method: 'get',
    params: data
  })
}

export function getgoodsdetail(data) {
  return request({
    url: '/diandi_hub/api/goods/detail',
    method: 'get',
    params: data
  })
}

export function createOrder(data) {
  return request({
    url: '/diandi_hub/api/order/creategoodsorder',
    method: 'post',
    data
  })
}

export function getgoodsOrderList(data) {
  return request({
    url: '/diandi_hub/api/order/list',
    method: 'post',
    data
  })
}

export function createPayparameters(data) {
  return request({
    url: '/diandi_hub/api/order/pay',
    method: 'post',
    data
  })
}

export function getorderdetail(data) {
  return request({
    url: '/diandi_hub/api/order/detail',
    method: 'post',
    data
  })
}

export function cancelorder(data) {
  return request({
    url: '/diandi_hub/api/refund/cancel',
    method: 'post',
    data
  })
}
