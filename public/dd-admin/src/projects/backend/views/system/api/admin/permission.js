/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-01-11 02:52:37
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-02-20 07:26:05
 */
import request from '@projectName/utils/request'

export function fetchList(query) {
  return request({
    url: '/auth/permission/index',
    method: 'get',
    params: query
  })
}

export function fetchLevels(query) {
  return request({
    url: '/auth/permission/levels',
    method: 'get',
    params: query
  })
}

export function fetchArticle(id) {
  return request({
    url: '/auth/permission/detail',
    method: 'get',
    params: { id }
  })
}

export function fetchPv(pv) {
  return request({
    url: '/auth/permission/pv',
    method: 'get',
    params: { pv }
  })
}

export function createPermission(data) {
  return request({
    url: '/auth/permission/create',
    method: 'post',
    data
  })
}

export function updatePermission(data) {
  return request({
    url: '/auth/permission/updateitem',
    method: 'post',
    data
  })
}

export function deletePermission(id) {
  return request({
    url: `/auth/permission/delete/${id}`,
    method: 'DELETE'
  })
}

export function fetchView(id, data) {
  return request({
    url: `/auth/permission/view/${id}`,
    method: 'get',
    params: data
  })
}

export function fetchAddons() {
  return request({
    url: '/auth/permission/addons',
    method: 'get'
  })
}

export function fetchRoute() {
  return request({
    url: '/auth/permission/route',
    method: 'get'
  })
}

export function getRules() {
  return request({
    url: '/auth/permission/rule',
    method: 'get'
  })
}

export function fetchChange(data) {
  return request({
    url: `/auth/permission/change`,
    method: 'post',
    data: data
  })
}

export function fetchAssign(id, data) {
  return request({
    url: `/auth/permission/assign/${id}`,
    method: 'post',
    data: data
  })
}

export function fetchRemove(id, data) {
  return request({
    url: `/auth/permission/remove/${id}`,
    method: 'post',
    data
  })
}
