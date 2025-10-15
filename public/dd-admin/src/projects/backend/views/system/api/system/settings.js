/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-02-13 17:22:13
 */
import request from '@projectName/utils/request'

export function saveWeburl(data) {
  return request({
    url: '/system/settings/weburl',
    method: 'post',
    data: data
  })
}

export function saveBaidu(data) {
  return request({
    url: '/system/settings/baidu',
    method: 'post',
    data: data
  })
}

export function saveWechatpay(data) {
  return request({
    url: '/system/settings/wechatpay',
    method: 'post',
    data: data
  })
}

export function saveSms(data) {
  return request({
    url: '/system/settings/sms',
    method: 'post',
    data: data
  })
}

export function saveEmail(data) {
  return request({
    url: '/system/settings/email',
    method: 'post',
    data: data
  })
}

export function saveWxapp(data) {
  return request({
    url: '/system/settings/wxapp',
    method: 'post',
    data: data
  })
}

export function saveWechat(data) {
  return request({
    url: '/system/settings/wechat',
    method: 'post',
    data: data
  })
}

export function saveMicroapp(data) {
  return request({
    url: '/system/settings/microapp',
    method: 'post',
    data: data
  })
}

export function saveApp(data) {
  return request({
    url: '/system/settings/app',
    method: 'post',
    data: data
  })
}

export function saveMap(data) {
  return request({
    url: '/system/settings/map',
    method: 'post',
    data: data
  })
}

export function saveOss(data) {
  return request({
    url: '/system/settings/oss',
    method: 'post',
    data: data
  })
}

export function getWeburl(data) {
  return request({
    url: '/system/settings/weburl',
    method: 'get',
    params: data
  })
}

export function getBaidu(data) {
  return request({
    url: '/system/settings/baidu',
    method: 'get',
    params: data
  })
}

export function getWechatpay(data) {
  return request({
    url: '/system/settings/wechatpay',
    method: 'get',
    params: data
  })
}

export function getSms(data) {
  return request({
    url: '/system/settings/sms',
    method: 'get',
    params: data
  })
}

export function getEmail(data) {
  return request({
    url: '/system/settings/email',
    method: 'get',
    params: data
  })
}

export function getWxapp(data) {
  return request({
    url: '/system/settings/wxapp',
    method: 'get',
    params: data
  })
}

export function getWechat(data) {
  return request({
    url: '/system/settings/wechat',
    method: 'get',
    params: data
  })
}

export function getMicroapp(data) {
  return request({
    url: '/system/settings/microapp',
    method: 'get',
    params: data
  })
}

export function getApp(data) {
  return request({
    url: '/system/settings/app',
    method: 'get',
    params: data
  })
}

export function getMap(data) {
  return request({
    url: '/system/settings/map',
    method: 'get',
    params: data
  })
}

export function getOss(data) {
  return request({
    url: '/system/settings/oss',
    method: 'get',
    params: data
  })
}
