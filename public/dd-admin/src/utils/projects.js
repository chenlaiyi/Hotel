/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2024-07-30 22:41:37
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-02-09 06:29:01
 */
const isShop = process.env.BASE_URL === '/shop/'
console.log('isShop', config, process, process.env.BASE_URL, isShop)
import { getConfig } from '@projectName/api/website.js'
import ResizeMixin from '@projectName/layout/mixin/ResizeHandler'
import {
  createItem,
  updateItem,
  getParentbloc,
  getBlocstatus,
  getLevels,
  getStoreGroup

} from '@projectName/api/bloc.js'
import {
  getCitylist
} from '@projectName/api/system.js'

import {
  getView,
  createItem as createItemStore,
  updateItem as updateItemStore,
  getStorestatus,
  getStoreLabel,
  fetchList
} from '@projectName/api/store.js'
import { enumsStoresbloc } from '@projectName/api/Enums.js'
import { debounce } from '@projectName/utils'

// import waves from '@projectName/directive/waves' // waves directive
import { parseTime } from '@projectName/utils'
import Render from '@projectName/directive/columnRender'
import request from '@projectName/utils/request'
import { addClass, removeClass } from '@projectName/utils'
import { scrollTo } from '@projectName/utils/scroll-to'
import { sendCode, userSignup, userForgetpass } from '@projectName/api/user.js'
import { config } from '@projectName/utils/publicUtil'
import { checkLogin, getQrcode } from '@projectName/api/officialaccount'

import { getConfig as backendgetConfig } from '@projectName/api/website.js'
// import ResizeMixin as ResizeMixin from '@projectName/layout/mixin/ResizeHandler'
import {
  createItem as createItemBackend,
  updateItem as updateItemBackend,
  getParentbloc as getParentblocBackend,
  getBlocstatus as getBlocstatusBackend,
  getLevels as getLevelsBackend,
  getStoreGroup as getStoreGroupBackend
} from '@projectName/api/bloc.js'
import {
  getCitylist as getCitylistBackend
} from '@projectName/api/system.js'

import {
  getView as getViewBackend,
  createItem as createItemStoreBackend,
  updateItem as updateItemStoreBackend,
  getStorestatus as getStorestatusBackend,
  getStoreLabel as getStoreLabelBackend,
  fetchList as fetchListBackend
} from '@projectName/api/store.js'
import { enumsStoresbloc as enumsStoresblocBackend } from '@projectName/api/Enums.js'
import { debounce as debounceBackend } from '@projectName/utils'

// import waves from '@projectName/directive/waves' // waves directive
import { parseTime as parseTimeBackend } from '@projectName/utils'
// import Render as RenderBackend from '@projectName/directive/columnRender'
import { request as requestBackend } from '@projectName/utils/request'
import { addClass as addClassBackend, removeClass as removeClassBackend } from '@projectName/utils'
import { scrollTo as scrollToBackend } from '@projectName/utils/scroll-to'
import { sendCode as sendCodeBackend, userSignup as userSignupBackend, userForgetpass as userForgetpassBackend } from '@projectName/api/user.js'
import { config as configBackend } from '@projectName/utils/publicUtil'

import {
  fetchList as fetchListPermission,
  deletePermission,
  getRules,
  fetchLevels,
  fetchAddons,
  fetchView as fetchViewPermission
} from '@projectName/api/permission.js'
import { validMobile, validEmail, validUsername } from '@/utils/validate'

export default {
  mixins: [ResizeMixin],
  components: {
    Render
  },
  methods: {
    getConfig: isShop ? getConfig : backendgetConfig,
    getView: isShop ? getView : getViewBackend,
    createItem: isShop ? createItem : createItemBackend,
    updateItem: isShop ? updateItem : updateItemBackend,
    getCitylist: isShop ? getCitylist : getCitylistBackend,
    getParentbloc: isShop ? getParentbloc : getParentblocBackend,
    getBlocstatus: isShop ? getBlocstatus : getBlocstatusBackend,
    getLevels: isShop ? getLevels : getLevelsBackend,
    getStorestatus: isShop ? getStorestatus : getStorestatusBackend,
    getStoreLabel: isShop ? getStoreLabel : getStoreLabelBackend,
    createItemStore: isShop ? createItemStore : createItemStoreBackend,
    updateItemStore: isShop ? updateItemStore : updateItemStoreBackend,
    enumsStoresbloc: isShop ? enumsStoresbloc : enumsStoresblocBackend,
    debounce: isShop ? debounce : debounceBackend,
    getStoreGroup: isShop ? getStoreGroup : getStoreGroupBackend,
    fetchList: isShop ? fetchList : fetchListBackend,
    fetchListPermission: isShop ? fetchListPermission : fetchListPermission,
    deletePermission: isShop ? deletePermission : deletePermission,
    getRules: isShop ? getRules : getRules,
    fetchLevels: isShop ? fetchLevels : fetchLevels,
    fetchAddons: isShop ? fetchAddons : fetchAddons,
    parseTime: isShop ? parseTime : parseTimeBackend,
    fetchViewPermission: isShop ? fetchViewPermission : fetchViewPermission,
    request: (...arr) => {
      return isShop ? request(...arr) : requestBackend(...arr)
    },
    addClass: isShop ? addClass : addClassBackend,
    removeClass: isShop ? removeClass : removeClassBackend,
    scrollTo: isShop ? scrollTo : scrollToBackend,
    validMobile: validMobile,
    validEmail: validEmail,
    sendCode: isShop ? sendCode : sendCodeBackend,
    userSignup: isShop ? userSignup : userSignupBackend,
    userForgetpass: isShop ? userForgetpass : userForgetpassBackend,
    validUsername: isShop ? validUsername : validUsername,
    config: () => {
      return isShop ? config : configBackend
    },
    checkLogin: checkLogin,
    getQrcode: getQrcode
  }
}
