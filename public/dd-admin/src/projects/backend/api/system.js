/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:38
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-07-28 18:40:39
 */
import request from '@projectName/utils/request'
import Layout from '@projectName/layout'
import sysPage from '@projectName/components/system-page/system-route'
// 其他子页面中间件
export function getMenus(data) {
  return request({
    url: '/system/index/menus',
    method: 'get',
    params: data
  })
}

export function setBlocCache(data) {
  return request({
    url: 'system/settings/set-cache',
    method: 'post',
    data
  })
}

export function clearCache(data) {
  return request({
    url: 'system/settings/clear-cache',
    method: 'post',
    data
  })
}

export function getCitylist() {
  return request({
    url: '/map/citylist',
    method: 'get'
  })
}

export function getMessagesList(data) {
  return request({
    url: '/messages/messages/list',
    method: 'get',
    params: data
  })
}

export function addonscate(query) {
  return request({
    url: '/diandi_cloud/addons-cate/list',
    method: 'get',
    params: query
  })
}

export function getmeaasgenum(query) {
  return request({
    url: '/messages/messages/unread',
    method: 'get',
    params: query
  })
}

export function loadFile(item) {
  console.log('loadFile', item.type)
  if (sysPage[item.name]) {
    return sysPage[item.name]
  } else {
    return (resolve) => require(['@projectName/views' + item.path], resolve)
  }
}

const visitedRoutes = new Set() // 用于去重的集合

export function initMenus(menus) {
  menus.forEach((item, index) => {
    const routeKey = `${item.name}_${item.path}` // 使用名称和路径生成路由的唯一标识
    console.log('每次一', item, routeKey)
    visitedRoutes.add(routeKey)

    if (visitedRoutes.has(routeKey)) {
      console.log('重复处理', routeKey)
      // return;
    }

    // 如果是第一级别的菜单 并且子菜单不为空，name给一级菜单设置全局布局
    switch (item.level_type) {
      case 1:
        // 一级菜单
        item.component = Layout
        if (item.children.length === 1) {
          item.redirect = item.children[0].path
          delete item.name
          // console.log('一2级菜单2 ', item.children)
        }
        // console.log('一2级菜单2 ', item.name, item)
        break
      case 2:
        // 一级菜单无二级
        item.component = Layout // loadFile(item.path)
        break
      case 3:
        // 二级菜单
        item.component = loadFile(item)
        break
      case 4:
        // 二级菜单无三级
        item.component = loadFile(item)
        break
      case 5:
        // 三级菜单,三级菜单下不能包含子页面菜单，只能包含非页面菜单
        item.component = loadFile(item)
        break
      case 6:
        // 非菜单页面
        item.component = loadFile(item)
        break
      default:
        // 默认一级菜单
        item.component = Layout
        break
    }

    if (item.children.length > 0) {
      initMenus(item.children)
    }

    return item
  })

  return menus
}

export function initLeftMenus(menus) {
  const leftMenu = []
  menus.forEach((item, index) => {
    // 如果是第一级别的菜单 并且子菜单不为空，name给一级菜单设置全局布局
    if (item.parent === 0) {
      leftMenu.push(item)
    }
  })
  return leftMenu
}

// 初始化菜单
export function getMenuRoutes(menus) {
  const menuRoutes = initMenus(menus)
  buildMenuRoutesStep2(menuRoutes)
  return menuRoutes
}

// 处理非菜单页面，提升一个父子节点
/**
 *
 * @param {当前数据} routes
 * @param {非菜单所在集合} root
 * @returns
 */
function buildMenuRoutesStep2(routes, root) {
  let list = []
  return routes.filter(its => {
    //  if (its.level_type === 6) {
    //    // 过滤掉非页面菜单
    //   //  root.children = root.children.filter(chi => chi.id === its.id)
    //  }
    if (its.level_type !== 6) {
      root = its
      // 过滤孙子辈
      list = its.children.filter(son => {
        if (son.children.length > 0) {
          const sonl = son.children.filter(sonchi => sonchi.level_type === 6)
          return sonl.length
        } else {
          return false
        }
      })
      //  将非菜单页面提升一个父子集节点
      if (list.length > 0) {
        upRoute(list, its)
      }
    }

    if (root.children.length > 0) {
      return buildMenuRoutesStep2(its.children, root)
    }
  })
}

function upRoute(list, its) {
  list.forEach((l, index) => {
    its.children = its.children.concat(l.children)
    l.children = []
  })
}
