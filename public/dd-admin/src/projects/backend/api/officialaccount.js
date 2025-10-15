/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2024-07-31 00:22:51
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-07-31 00:24:07
 */

import request from '@projectName/utils/request'

export function getQrcode(data) {
  return request({
    url: '/officialaccount/basics/qrcode',
    method: 'post',
    data: data
  })
}

export function checkLogin(data) {
  return request({
    url: '/officialaccount/basics/check-login',
    method: 'post',
    data: data
  })
}
