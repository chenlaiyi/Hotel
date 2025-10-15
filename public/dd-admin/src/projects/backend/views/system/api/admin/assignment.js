/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2022-01-11 02:52:37
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-01-23 17:59:16
 */
import request from '@projectName/utils/request'

export function AssignmentFetchView(id) {
  return request({
    url: `/auth/assignment/view/${id}`,
    method: 'get'
  })
}

export function AssignmentFetchAssign(id, data) {
  return request({
    url: `/auth/assignment/assign/${id}`,
    method: 'post',
    data: data
  })
}

export function AssignmentFetchChange(data) {
  return request({
    url: `/auth/assignment/change`,
    method: 'post',
    data: data
  })
}

export function AssignmentFetchRevoke(id, data) {
  return request({
    url: `/auth/assignment/revoke/${id}`,
    method: 'post',
    data
  })
}
