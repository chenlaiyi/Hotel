/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-03-16 17:09:28
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-10 13:49:52
 */
import request from '@projectName/utils/request'

export function enumsStoresbloc(data) {
  return request({
    url: '/enums/storesbloc',
    method: 'get',
    params: data
  })
}

export function enumsBlocs(data) {
  return request({
    url: '/enums/blocs',
    method: 'get',
    params: data
  })
}

export function enumsStores(data) {
  return request({
    url: '/enums/stores',
    method: 'get',
    params: data
  })
}
