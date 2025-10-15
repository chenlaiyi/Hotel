/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-01-18 22:08:04
 */
import request from '@projectName/utils/request'

export function fetchList(query) {
  return request({
    url: '/auth/group/index',
    method: 'get',
    params: query
  })
}

export function fetchLevels() {
  return request({
    url: '/auth/group/levels',
    method: 'get'
  })
}

export function fetchArticle(id) {
  return request({
    url: '/auth/group/detail',
    method: 'get',
    params: { id }
  })
}

export function fetchPv(pv) {
  return request({
    url: '/auth/group/pv',
    method: 'get',
    params: { pv }
  })
}

export function createGroup(data) {
  return request({
    url: '/auth/group/create',
    method: 'post',
    data
  })
}

export function updateGroup(data) {
  return request({
    url: `/auth/group/update/${data.id}`,
    method: 'PUT',
    data
  })
}

export function deleteGroup(id) {
  return request({
    url: `/auth/group/delete/${id}`,
    method: 'DELETE'
  })
}

export function fetchView(id) {
  return request({
    url: `/auth/group/view/${id}`,
    method: 'get'
  })
}

export function fetchAddons() {
  return request({
    url: '/auth/group/addons',
    method: 'get'
  })
}

export function fetchAvailable() {
  return request({
    url: '/auth/group/available',
    method: 'post'
  })
}

export function getRules() {
  return request({
    url: '/auth/group/rule',
    method: 'get'
  })
}

export function fetchAssign(id, data) {
  return request({
    url: `/auth/group/assign/${id}`,
    method: 'post',
    data
  })
}

export function fetchRemove(id, data) {
  return request({
    url: `/auth/group/remove/${id}`,
    method: 'post',
    data
  })
}

export function fetchChange(data) {
  return request({
    url: `/auth/group/change`,
    method: 'post',
    data: data
  })
}

