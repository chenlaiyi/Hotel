/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-05-12 18:05:10
 */
import request from '@projectName/utils/request'

export function ManageInstall(data) {
  return request({
    url: `/addons/manage/install?addon=${data.identifie}`,
    method: 'post',
    data: data
  })
}

export function ManageUninstall(data) {
  return request({
    url: `/addons/manage/uninstall?addon=${data.identifie}`,
    method: 'post',
    data: data
  })
}

export function ManageAuth(data) {
  return request({
    url: '/addons/manage/auth',
    method: 'post',
    data: data
  })
}

