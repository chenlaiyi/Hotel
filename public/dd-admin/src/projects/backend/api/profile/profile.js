/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-01-18 17:21:58
 */
import request from '@projectName/utils/request'

export function getuserinfo(data) {
  return request({
    url: '/user/userinfo',
    method: 'post',
    data: data
  })
}

export function setpassword(data) {
  return request({
    url: '/user/repassword',
    method: 'post',
    data: data
  })
}

export function getcode(data) {
  return request({
    url: '/user/sendcode',
    method: 'post',
    data: data
  })
}

export function edituserinfo(data) {
  return request({
    url: '/user/edituserinfo',
    method: 'post',
    data: data
  })
}

export function getlog(data) {
  return request({
    url: '/user/log',
    method: 'post',
    data: data
  })
}
