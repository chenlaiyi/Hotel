/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-02-12 13:37:56
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-02-14 18:59:40
 */
import request from '@projectName/utils/request'

export function fetchList(query) {
  return request({
    url: '/auth/route/index',
    method: 'get',
    params: query
  })
}

export function fetchLevels() {
  return request({
    url: '/auth/route/levels',
    method: 'get'
  })
}

export function fetchArticle(id) {
  return request({
    url: '/auth/route/detail',
    method: 'get',
    params: { id }
  })
}

export function fetchPv(pv) {
  return request({
    url: '/auth/route/pv',
    method: 'get',
    params: { pv }
  })
}

export function createRoute(data) {
  return request({
    url: '/auth/route/create',
    method: 'post',
    data
  })
}

export function updateRoute(data) {
  return request({
    url: `/auth/route/update/${data.id}`,
    method: 'PUT',
    data
  })
}

export function deleteRoute(id) {
  return request({
    url: `/auth/route/delete/${id}`,
    method: 'DELETE'
  })
}

export function fetchView(id) {
  return request({
    url: `/auth/route/view/${id}`,
    method: 'get'
  })
}

export function fetchAddons() {
  return request({
    url: '/auth/route/addons',
    method: 'get'
  })
}

export function fetchAvailable() {
  return request({
    url: '/auth/route/available',
    method: 'post'
  })
}

export function getRules() {
  return request({
    url: '/auth/route/rule',
    method: 'get'
  })
}

export function fetchAssign(data) {
  return request({
    url: '/auth/route/assign',
    method: 'post',
    data
  })
}

export function fetchRemove(data) {
  return request({
    url: '/auth/route/remove',
    method: 'post',
    data
  })
}

export function fetchRefresh(data) {
  return request({
    url: '/auth/route/refresh',
    method: 'post',
    data
  })
}
