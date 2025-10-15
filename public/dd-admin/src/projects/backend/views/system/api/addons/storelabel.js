import request from '@projectName/utils/request'

export function fetchList(query) {
  return request({
    url: '/addons/storelabel/index',
    method: 'get',
    params: query
  })
}

export function getView(id) {
  return request({
    url: `/addons/storelabel/view/${id}`,
    method: 'get'
  })
}

export function createItem(data) {
  return request({
    url: '/addons/storelabel/create',
    method: 'post',
    data
  })
}

export function updateItem(data) {
  return request({
    url: `/addons/storelabel/update/${data.id}`,
    method: 'PUT',
    data
  })
}

export function deleteItem(id) {
  return request({
    url: `/addons/storelabel/delete/${id}`,
    method: 'DELETE'
  })
}

export function fetchView(id) {
  return request({
    url: `/addons/storelabel/view/${id}`,
    method: 'get'
  })
}

export function getUnit() {
  return request({
    url: '/addons/storelabel/unit',
    method: 'get'
  })
}

export function getSuppliers() {
  return request({
    url: '/addons/storelabel/suppliers',
    method: 'get'
  })
}

export function getSalestatus() {
  return request({
    url: '/addons/storelabel/salestatus',
    method: 'get'
  })
}

export function getParentbloc() {
  return request({
    url: '/addons/storelabel/parentbloc',
    method: 'get'
  })
}

export function getStores() {
  return request({
    url: '/addons/storelabel/storelabels',
    method: 'get'
  })
}

export function getReglevel() {
  return request({
    url: '/addons/storelabel/reglevel',
    method: 'get'
  })
}

export function getBlocstatus() {
  return request({
    url: '/addons/storelabel/storelabelstatus',
    method: 'get'
  })
}

export function getLevels() {
  return request({
    url: '/addons/storelabel/levels',
    method: 'get'
  })
}

export function getCategory() {
  return request({
    url: '/addons/storelabel/category',
    method: 'get'
  })
}
