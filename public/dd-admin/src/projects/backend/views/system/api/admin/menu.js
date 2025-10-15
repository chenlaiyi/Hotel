/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2021-12-01 10:03:55
 */
import request from '@projectName/utils/request'

export function fetchList(query) {
  return request({
    url: '/auth/menu/index',
    method: 'get',
    params: query
  })
}

export function fetchLevels() {
  return request({
    url: '/auth/menu/levels',
    method: 'get'
  })
}

export function createMenu(data) {
  return request({
    url: '/auth/menu/create',
    method: 'post',
    data
  })
}

export function updateMenu(data) {
  return request({
    url: `/auth/menu/update/${data.id}`,
    method: 'PUT',
    data
  })
}

export function deleteMenu(id) {
  return request({
    url: `/auth/menu/delete/${id}`,
    method: 'DELETE'
  })
}

export function fetchView(id) {
  return request({
    url: `/auth/menu/view/${id}`,
    method: 'get'
  })
}

export function fetchRoute(data) {
  return request({
    url: '/auth/menu/route',
    method: 'post',
    data
  })
}

export function fetchAvailable() {
  return request({
    url: '/auth/route/available',
    method: 'post'
  })
}
