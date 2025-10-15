import request from '@projectName/utils/request'

export function fetchList(query) {
  return request({
    url: '/addons/bloclevel/index',
    method: 'get',
    params: query
  })
}

export function getView(id) {
  return request({
    url: `/addons/bloclevel/view/${id}`,
    method: 'get'
  })
}

export function createItem(data) {
  return request({
    url: '/addons/bloclevel/create',
    method: 'post',
    data
  })
}

export function updateItem(data) {
  return request({
    url: `/addons/bloclevel/update/${data.id}`,
    method: 'PUT',
    data
  })
}

export function deleteItem(id) {
  return request({
    url: `/addons/bloclevel/delete/${id}`,
    method: 'DELETE'
  })
}

export function fetchView(id) {
  return request({
    url: `/addons/bloclevel/view/${id}`,
    method: 'get'
  })
}

export function getUnit() {
  return request({
    url: '/addons/bloclevel/unit',
    method: 'get'
  })
}

export function getSuppliers() {
  return request({
    url: '/addons/bloclevel/suppliers',
    method: 'get'
  })
}

export function getSalestatus() {
  return request({
    url: '/addons/bloclevel/salestatus',
    method: 'get'
  })
}

export function getParentbloc() {
  return request({
    url: '/addons/bloclevel/parentbloc',
    method: 'get'
  })
}

export function getStores() {
  return request({
    url: '/addons/bloclevel/stores',
    method: 'get'
  })
}

export function getReglevel() {
  return request({
    url: '/addons/bloclevel/reglevel',
    method: 'get'
  })
}

export function getBlocstatus() {
  return request({
    url: '/addons/bloclevel/bloclevelstatus',
    method: 'get'
  })
}

export function getLevels() {
  return request({
    url: '/addons/bloclevel/levels',
    method: 'get'
  })
}
