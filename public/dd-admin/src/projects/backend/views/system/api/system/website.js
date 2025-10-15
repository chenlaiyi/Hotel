/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-02-14 19:34:51
 */
import request from '@projectName/utils/request'

export function fetchList(query) {
  return request({
    url: '/website/dd-website-slide/index',
    method: 'get',
    params: query
  })
}

export function fetchArticle(id) {
  return request({
    url: '/website/dd-website-slide/detail',
    method: 'get',
    params: {
      id
    }
  })
}

export function fetchPv(pv) {
  return request({
    url: '/website/dd-website-slide/pv',
    method: 'get',
    params: {
      pv
    }
  })
}

export function createSlide(data) {
  return request({
    url: '/website/dd-website-slide/create',
    method: 'post',
    data
  })
}

export function updateSlide(data) {
  return request({
    url: '/website/dd-website-slide/updateitem',
    method: 'post',
    data
  })
}

export function deleteSlide(data) {
  return request({
    url: '/website/dd-website-slide/deleteitem',
    method: 'post',
    data
  })
}

export function setConfig(data) {
  return request({
    url: '/website/setting/config',
    method: 'post',
    data
  })
}

export function getConfig(data) {
  return request({
    url: '/website/setting/info',
    method: 'get',
    params: data
  })
}

export function updatead() {}
export function createad() {}
export function deletead() {}
