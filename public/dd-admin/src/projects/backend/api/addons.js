/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-12-14 10:21:20
 */
import request from '@projectName/utils/request'

export function fetchList(query) {
  return request({
    url: '/addons/addons/list',
    method: 'get',
    params: query
  })
}

export function fetchAuth() {
  return request({
    url: '/addons/addons/auth',
    method: 'post'
  })
}

export function fetchInfo(query) {
  return request({
    url: '/addons/addons/info',
    method: 'get',
    params: query
  })
}

export function fetchChild(data) {
  return request({
    url: '/addons/addons/child',
    method: 'post',
    data: data
  })
}

export function fetchUninstalled(data) {
  return request({
    url: '/addons/addons/uninstalled',
    method: 'post',
    data: data
  })
}

