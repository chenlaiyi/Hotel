/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-10-26 16:06:06
 */
import request from '@projectName/utils/request'

export function fetchList(query) {
  return request({
    url: '/addons/store/index',
    method: 'get',
    params: query
  })
}

export function getView(id) {
  return request({
    url: `/addons/store/view/${id}`,
    method: 'get'
  })
}

export function createItem(data) {
  return request({
    url: '/addons/store/create',
    method: 'post',
    data
  })
}

export function updateItem(data) {
  return request({
    url: `/addons/store/update/${data.store_id}`,
    method: 'PUT',
    data
  })
}

export function deleteItem(id) {
  return request({
    url: `/addons/store/delete/${id}`,
    method: 'DELETE'
  })
}

export function fetchView(id) {
  return request({
    url: `/addons/store/view/${id}`,
    method: 'get'
  })
}

export function getUnit() {
  return request({
    url: '/addons/store/unit',
    method: 'get'
  })
}

export function getSuppliers() {
  return request({
    url: '/addons/store/suppliers',
    method: 'get'
  })
}

export function getSalestatus() {
  return request({
    url: '/addons/store/salestatus',
    method: 'get'
  })
}

export function getBloc() {
  return request({
    url: '/addons/store/blocs',
    method: 'get'
  })
}

export function getStores() {
  return request({
    url: '/addons/store/stores',
    method: 'get'
  })
}

export function getReglevel() {
  return request({
    url: '/addons/store/reglevel',
    method: 'get'
  })
}

export function getStorestatus() {
  return request({
    url: '/addons/store/storestatus',
    method: 'get'
  })
}

export function getStoreLabel() {
  return request({
    url: '/addons/store/storelabel',
    method: 'get'
  })
}

export function getLevels() {
  return request({
    url: '/addons/store/levels',
    method: 'get'
  })
}

export function getCategory() {
  return request({
    url: '/addons/store/category',
    method: 'get'
  })
}

export function addonscreateItem(data) {
  return request({
    url: '/addons/store/store-create',
    method: 'post',
    data
  })
}
