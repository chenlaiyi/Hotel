/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-01-18 18:14:05
 */
import request from '@projectName/utils/request'
// 其他子页面中间件

export function getMenus(token) {
  return request({
    url: '/system/index/menus',
    method: 'get'
  })
}

export function setBlocCache(data) {
  return request({
    url: 'system/settings/set-cache',
    method: 'post',
    data
  })
}

export function clearCache(data) {
  return request({
    url: 'system/settings/clear-cache',
    method: 'post',
    data
  })
}

export function getCitylist() {
  return request({
    url: '/map/citylist',
    method: 'get'
  })
}

export function getMessagesList(data) {
  return request({
    url: '/messages/messages/list',
    method: 'get',
    params: data
  })
}

export function addonscate(query) {
  return request({
    url: '/diandi_cloud/addons-cate/list',
    method: 'get',
    params: query
  })
}

export function getmeaasgenum(query) {
  return request({
    url: '/messages/messages/unread',
    method: 'get',
    params: query
  })
}
