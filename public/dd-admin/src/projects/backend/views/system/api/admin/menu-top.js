import request from '@projectName/utils/request'

export function fetchList(query) {
  return request({
    url: '/auth/menutop/index',
    method: 'get',
    params: query
  })
}

export function fetchRoute(data) {
  return request({
    url: '/auth/menutop/route',
    method: 'post',
    data
  })
}

export function createItem(data) {
  return request({
    url: '/auth/menutop/create',
    method: 'post',
    data
  })
}

export function updateItem(data) {
  return request({
    url: `/auth/menutop/update/${data.id}`,
    method: 'PUT',
    data
  })
}

export function deleteItem(id) {
  return request({
    url: `/auth/menutop/delete/${id}`,
    method: 'DELETE'
  })
}

export function fetchView(id) {
  return request({
    url: `/auth/menutop/view/${id}`,
    method: 'get'
  })
}
