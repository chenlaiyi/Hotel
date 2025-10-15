/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2021-12-01 10:03:59
 */
import request from '@projectName/utils/request'

export function fetchList(data) {
  return request({
    url: '/store/blocs',
    method: 'post',
    data
  })
}

export function getView(id) {
  return request({
    url: `/store/view/${id}`,
    method: 'get'
  })
}

export function createItem(data) {
  return request({
    url: '/store/create',
    method: 'post',
    data
  })
}

export function updateItem(data) {
  return request({
    url: `/store/update/${data.spec_id}`,
    method: 'PUT',
    data
  })
}

export function deleteItem(id) {
  return request({
    url: `/self_help/goods/delete/${id}`,
    method: 'DELETE'
  })
}

export function fetchView(id) {
  return request({
    url: `/store/view/${id}`,
    method: 'get'
  })
}
