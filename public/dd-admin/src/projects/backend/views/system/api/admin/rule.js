/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-02-20 07:31:29
 */
import request from '@projectName/utils/request'

export function fetchList(query) {
  return request({
    url: '/auth/rule/index',
    method: 'get',
    params: query
  })
}

export function getView(id) {
  return request({
    url: `/auth/rule/view/${id}`,
    method: 'get'
  })
}

export function createItem(data) {
  return request({
    url: '/auth/rule/create',
    method: 'post',
    data
  })
}

export function updateItem(data) {
  return request({
    url: `/auth/rule/update/${data.store_id}`,
    method: 'PUT',
    data
  })
}

export function deleteItem(id) {
  return request({
    url: `/auth/rule/delete/${id}`,
    method: 'DELETE'
  })
}

export function fetchView(id) {
  return request({
    url: `/auth/rule/view/${id}`,
    method: 'get'
  })
}

export function getRules() {
  return request({
    url: '/auth/permission/rule',
    method: 'get'
  })
}
