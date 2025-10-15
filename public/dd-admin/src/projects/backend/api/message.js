/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-11-12 17:53:14
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-01-18 13:09:03
 */
import request from '@projectName/utils/request'

export function messageIndex(data) {
  return request({
    url: '/diandi_hub/messages-category/index',
    method: 'get',
    params: data
  })
}
export function messageView(id) {
  return request({
    url: `/diandi_hub/messages-category/view/${id}`,
    method: 'get'
  })
}

export function messageCreate(data) {
  return request({
    url: '/diandi_hub/messages-category/create',
    method: 'post',
    data: data
  })
}

export function messageUpdate(data) {
  return request({
    url: `/diandi_hub/messages-category/update/${data.id}`,
    method: 'put',
    data: data
  })
}

export function messageDelete(id) {
  return request({
    url: `/diandi_hub/messages-category/delete/${id}`,
    method: 'delete'
  })
}

export function messagelistIndex(data) {
  return request({
    url: '/diandi_hub/messages/index',
    method: 'get',
    params: data
  })
}
export function messagelistView(id) {
  return request({
    url: `/diandi_hub/messages/view/${id}`,
    method: 'get'
  })
}

export function messagelistCreate(data) {
  return request({
    url: '/diandi_hub/messages/create',
    method: 'post',
    data: data
  })
}

export function messagelistUpdate(data) {
  return request({
    url: `/diandi_hub/messages/update/${data.id}`,
    method: 'put',
    data: data
  })
}

export function messagelistDelete(id) {
  return request({
    url: `/diandi_hub/messages/delete/${id}`,
    method: 'delete'
  })
}

export function changestates(id) {
  return request({
    url: `/diandi_hub/messages/read/${id}`,
    method: 'get'
  })
}

