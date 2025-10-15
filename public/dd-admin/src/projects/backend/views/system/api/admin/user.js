/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-02-20 08:15:20
 */
import request from '@projectName/utils/request'

export function login(data) {
  return request({
    url: '/user/login',
    method: 'post',
    data
  })
}

export function getInfo(token) {
  return request({
    url: '/user/userinfo',
    method: 'get'
  })
}

export function logout() {
  return request({
    url: '/site/logout',
    method: 'post'
  })
}

export function getAddons(data) {
  return request({
    url: '/user/addons',
    method: 'post',
    params: data
  })
}

export function getUserList(data) {
  return request({
    url: '/auth/admin-user/index',
    method: 'get',
    params: data
  })
}

export function fetchView(id) {
  return request({
    url: `/auth/admin-user/view/${id}`,
    method: 'get'
  })
}

export function sendCode(data) {
  return request({
    url: `/user/sendcode`,
    method: 'post',
    data
  })
}

export function userSignup(data) {
  return request({
    url: `/user/signup`,
    method: 'post',
    data
  })
}

export function userForgetpass(data) {
  return request({
    url: `/user/forgetpass`,
    method: 'post',
    data
  })
}

// 删除用户
export function userDelete(data) {
  return request({
    url: `/user/delete/${data.id}`,
    method: 'delete',
    data
  })
}

// 审核用户
export function userActivate(id, data) {
  return request({
    url: `/user/activate/${id}`,
    method: 'post',
    data
  })
}

// 修改用户状态
export function userUpstatus(data) {
  return request({
    url: `/user/upstatus`,
    method: 'post',
    data
  })
}

export function createUser(data) {
  return request({
    url: `/user/create`,
    method: 'post',
    data
  })
}

export function updateUser(data) {
  return request({
    url: `/user/update?id=${data.id}`,
    method: 'post',
    data
  })
}

export function getUserSet(data) {
  return request({
    url: '/user/setinfo',
    method: 'post',
    data
  })
}

export function setDefault(data) {
  return request({
    url: '/user/default',
    method: 'post',
    data
  })
}

export function getDefault(data) {
  return request({
    url: '/user/defaultinfo',
    method: 'post',
    data
  })
}

export function getsignup(data) {
  return request({
    url: '/wechat/signup',
    method: 'post',
    data: data
  })
}
