/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-11-29 23:22:59
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2021-11-30 14:32:45
 */
import request from '@projectName/utils/request'

export function getToken() {
  return request({
    url: '/qiniu/upload/token', // 假地址 自行替换
    method: 'get'
  })
}
