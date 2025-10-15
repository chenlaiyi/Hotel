/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:27
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-08-08 14:49:29
 */
import request from '@projectName/utils/request'

export function fetchList(query) {
  return request({
    url: '/member/dd-member-group/index',
    method: 'get',
    params: query
  })
}

export function getView(id) {
  return request({
    url: `/member/dd-member-group/view/${id}`,
    method: 'get'
  })
}

export function createItem(data) {
  return request({
    url: '/member/dd-member-group/create',
    method: 'post',
    data
  })
}

export function updateItem(data) {
  return request({
    url: `/member/dd-member-group/update/${data.group_id}`,
    method: 'PUT',
    data
  })
}

export function deleteItem(id) {
  return request({
    url: `/member/dd-member-group/delete/${id}`,
    method: 'DELETE'
  })
}

export function fetchView(id) {
  return request({
    url: `/member/dd-member-group/view/${id}`,
    method: 'get'
  })
}

export function fetchCate(data) {
  return request({
    url: '/member/dd-member-group/cate',
    method: 'post',
    data
  })
}
