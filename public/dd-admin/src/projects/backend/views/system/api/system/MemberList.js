/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2021-11-14 20:59:19
 */
import request from '@projectName/utils/request'

export function fetchList(query) {
  return request({
    url: '/member/dd-member/index',
    method: 'get',
    params: query
  })
}

export function getView(id) {
  return request({
    url: `/member/dd-member/view/${id}`,
    method: 'get'
  })
}

export function createItem(data) {
  return request({
    url: '/member/dd-member/create',
    method: 'post',
    data
  })
}

export function updateItem(data) {
  return request({
    url: `/member/dd-member/update/${data.dd - member_id}`,
    method: 'PUT',
    data
  })
}

export function deleteItem(id) {
  return request({
    url: `/member/dd-member/delete/${id}`,
    method: 'DELETE'
  })
}

export function fetchView(id) {
  return request({
    url: `/member/dd-member/view/${id}`,
    method: 'get'
  })
}

