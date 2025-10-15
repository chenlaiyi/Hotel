/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2024-07-23 23:08:48
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-02-09 06:25:30
 */
const API_PREFIX = process.env.API_PREFIX
const config = {
  siteName: '店滴云',
  siteKeywords: '店滴云，酒店，公寓，民宿休闲场所智能营销管理系统',
  siteDesc: '店滴云',
  changeMenu: '切换茶室',
  // 站点基础地址 末尾不带斜杠
  siteUrl: `https://${API_PREFIX}.dandicloud.cn`,
  // siteUrl: 'https://iot.l-rabbit.com',
  iotUrl: 'https://iot.ddicms.com',
  project_sn: 'X0rjHmTdh2qv1i4U',
  // siteUrl: process.env.VUE_APP_BASE_API,
  // 百度地址key
  bmapAk: 'sY7GGnljSvLzM44mEwVtGozS',
  // unit 单商户模式，units多商户模式
  modeType: 'units',
  // 集团ID
  bloc_id: 38,
  // 商户id
  store_id: 79,
  isRegister: true,
  menuType: 'system',
  Layout: 'left'
}

module.exports = config
