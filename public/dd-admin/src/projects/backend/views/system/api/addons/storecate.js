/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2023-03-14 16:44:07
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-04 15:08:24
 */
import request from '@projectName/utils/request'

export function fetchList(query) {
  return request({
    url: '/addons/category/index',
    method: 'get',
    params: query
  })
}

export function getView(id) {
  return request({
    url: `/addons/category/view/${id}`,
    method: 'get'
  })
}

export function createItem(data) {
  return request({
    url: '/addons/category/create',
    method: 'post',
    data
  })
}

export function updateItem(data) {
  return request({
    url: `/addons/category/update/${data.category_id}`,
    method: 'PUT',
    data
  })
}

export function deleteItem(id) {
  return request({
    url: `/addons/category/delete/${id}`,
    method: 'DELETE'
  })
}

export function fetchView(id) {
  return request({
    url: `/addons/category/view/${id}`,
    method: 'get'
  })
}

export function getUnit() {
  return request({
    url: '/addons/category/unit',
    method: 'get'
  })
}

export function getCategory() {
  return request({
    url: '/addons/category/category',
    method: 'get'
  })
}
