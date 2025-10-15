/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-27 09:56:28
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-08-02 00:06:25
 */
import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@projectName/layout'
import sysPage from '@projectName/components/system-page/system-route'
import LoginMap from '@projectName/components/login/login-router-backend.js'

/* Router Modules */
// import componentsRouter from './modules/components'
// import chartsRouter from './modules/charts'
// import tableRouter from './modules/table'
// import formRouter from './modules/form'
// import example from './modules/example'
// import service from './modules/service'

// import centerHonorayEdit from './diandi_honorary/center_honoray/center_honoray_edit'

/**
  * Note: sub-menu only appear when route children.length >= 1
  * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
  *
  * hidden: true                   if set true, item will not show in the sidebar(default is false)
  * alwaysShow: true               if set true, will always show the root menu
  *                                if not set alwaysShow, when item has more than one children route,
  *                                it will becomes nested mode, otherwise not show the root menu
  * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
  * name:'router-name'             the name is used by <keep-alive> (must set!!!)
  * meta : {
     roles: ['admin','editor']    control the page roles (you can set multiple roles)
     title: 'title'               the name show in sidebar and breadcrumb (recommend set)
     icon: 'svg-name'/'el-icon-x' the icon show in the sidebar
     noCache: true                if set true, the page will no be cached(default is false)
     affix: true                  if set true, the tag will affix in the tags-view
     breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
     activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
   }
  */
console.log('sysPage2', sysPage, LoginMap)
/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  {
    path: '/redirect',
    component: Layout,
    hidden: true,
    children: [
      {
        path: '/redirect/:path(.*)',
        component: sysPage['redirect-index']
      }
    ]
  },
  {
    path: '/login',
    component: LoginMap['login-index'],
    hidden: true
  },
  {
    path: '/forget',
    component: LoginMap['login-forget'],
    hidden: true
  },
  {
    path: '/register',
    component: LoginMap['login-register'],
    hidden: true
  },
  {
    path: '/auth-redirect',
    component: LoginMap['login-auth-redirect'],
    hidden: true
  },
  {
    path: '/401',
    component: sysPage['error-page-401'],
    hidden: true
  },
  {
    path: '/profile-index',
    component: Layout,
    redirect: '/views/default/profile/index',
    hidden: true,
    children: [
      {
        path: '/profile/index',
        name: 'profile-index',
        component: () => import('@projectName/views/default/profile/index'),
        meta: {
          title: '我的资料',
          icon: 'user',
          noCache: true
        }
      }
    ]
  },
  {
    path: '/seting-index',
    component: sysPage['setting-index'],
    hidden: true
  },
  {
    path: '/system-account-index',
    component: sysPage['system-account-index'],
    hidden: true
  },
  {
    path: '/setting-administrator',
    component: sysPage['setting-administrator'],
    hidden: true
  },
  {
    path: '/setting-attestation',
    component: sysPage['setting-attestation'],
    hidden: true
  },
  {
    path: '/setting-storeattest',
    component: sysPage['setting-storeattest'],
    hidden: true
  },
  {
    path: '/system-notification-index',
    component: sysPage['system-notification-index'],
    hidden: true
  }
]
// 404 page must be placed at the end !!!
// {
//   path: '*',
//   redirect: '/404',
//   hidden: true
// }

/**
 * asyncRoutes
 * the routes that need to be dynamically loaded based on user roles
 */
export const asyncRoutes = [
  /** when your routing map is too long, you can split it into small modules **/
  {
    path: '/error',
    component: Layout,
    redirect: 'noRedirect',
    name: 'ErrorPages',
    meta: {
      title: 'Error Pages',
      icon: '404'
    },
    children: [
      {
        path: '401',
        component: sysPage['error-page-401'],
        name: 'Page401',
        meta: {
          title: '401',
          noCache: true
        }
      },
      {
        path: '404',
        component: sysPage['error-page-404'],
        name: 'Page404',
        meta: {
          title: '404',
          noCache: true
        }
      }
    ]
  }
]

const createRouter = () =>
  new Router({
    mode: 'history', // require service support
    base: '/backend',
    scrollBehavior: () => ({
      y: 0
    }),
    routes: asyncRoutes.concat(constantRoutes)
    //  routes: constantRoutes
  })

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
