/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-27 09:56:28
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-04 21:40:02
 */
import request from '@projectName/utils/request'

export function fetchList(query) {
  return request({
    url: '/addons/bloc/index',
    method: 'get',
    params: query
  })
}

export function getView(bloc_id) {
  return request({
    url: `/addons/bloc/view/${bloc_id}`,
    method: 'get'
  })
}

export function getStoreGroup() {
  return request({
    url: `/addons/bloc/storeGroup`,
    method: 'get'
  })
}

export function createItem(data) {
  return request({
    url: '/addons/bloc/create',
    method: 'post',
    data
  })
}

export function updateItem(data) {
  return request({
    url: `/addons/bloc/update/${data.bloc_id}`,
    method: 'PUT',
    data
  })
}

export function deleteItem(bloc_id) {
  return request({
    url: `/addons/bloc/delete/${bloc_id}`,
    method: 'DELETE'
  })
}

export function fetchView(bloc_id) {
  return request({
    url: `/addons/bloc/view/${bloc_id}`,
    method: 'get'
  })
}

export function getUnit() {
  return request({
    url: '/addons/bloc/unit',
    method: 'get'
  })
}

export function getSuppliers() {
  return request({
    url: '/addons/bloc/suppliers',
    method: 'get'
  })
}

export function getSalestatus() {
  return request({
    url: '/addons/bloc/salestatus',
    method: 'get'
  })
}

export function getParentbloc() {
  return request({
    url: '/addons/bloc/parentbloc',
    method: 'get'
  })
}

export function getChildBloc(data) {
  return request({
    url: '/addons/bloc/childbloc',
    method: 'post',
    data
  })
}

export function getStores() {
  return request({
    url: '/addons/bloc/stores',
    method: 'get'
  })
}

export function getReglevel() {
  return request({
    url: '/addons/bloc/reglevel',
    method: 'get'
  })
}

export function getBlocstatus() {
  return request({
    url: '/addons/bloc/blocstatus',
    method: 'get'
  })
}

export function getLevels() {
  return request({
    url: '/addons/bloc/levels',
    method: 'get'
  })
}

export function getStorelist() {
  return request({
    url: '/addons/bloc/storelist',
    method: 'get'
  })
}

export function getBlocStore() {
  return request({
    url: '/addons/bloc/bloc-store',
    method: 'get'
  })
}

