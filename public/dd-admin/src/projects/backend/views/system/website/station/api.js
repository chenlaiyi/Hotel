/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2024-01-28 13:50:17
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-01-28 21:57:39
 */

import request from '@projectName/utils/request'
import { path } from './init'

export function initList(data) {
  return request({
    url: path.api + '/index',
    method: 'get',
    params: data
  })
}

export function getView(id) {
  return request({
    url: path.api + `/view/${id}`,
    method: 'get'
  })
}

export function itemCreate(data) {
  return request({
    url: path.api + '/create',
    method: 'post',
    data: data
  })
}

export function itemUpdate(id, data) {
  return request({
    url: path.api + `/update/${id}`,
    method: 'put',
    data: data
  })
}

export function itemDelete(id) {
  return request({
    url: path.api + `/delete/${id}`,
    method: 'delete'
  })
}
