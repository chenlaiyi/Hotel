/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2024-07-30 22:41:37
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-12-29 22:03:34
 */

import { getConfig as backendgetConfig } from '@projectName/api/website.js'
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

import { checkLogin, getQrcode } from '@projectName/api/officialaccount'
import ResizeMixin from '@projectName/layout/mixin/ResizeHandler'
import Render from '@projectName/directive/columnRender'

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
    getConfig: backendgetConfig,
    getView: getViewBackend,
    createItem: createItemBackend,
    updateItem: updateItemBackend,
    getCitylist: getCitylistBackend,
    getParentbloc: getParentblocBackend,
    getBlocstatus: getBlocstatusBackend,
    getLevels: getLevelsBackend,
    getStorestatus: getStorestatusBackend,
    getStoreLabel: getStoreLabelBackend,
    createItemStore: createItemStoreBackend,
    updateItemStore: updateItemStoreBackend,
    enumsStoresbloc: enumsStoresblocBackend,
    debounce: debounceBackend,
    getStoreGroup: getStoreGroupBackend,
    fetchList: fetchListBackend,
    fetchListPermission: fetchListPermission,
    deletePermission: deletePermission,
    getRules: getRules,
    fetchLevels: fetchLevels,
    fetchAddons: fetchAddons,
    parseTime: parseTimeBackend,
    fetchViewPermission: fetchViewPermission,
    request: (...arr) => {
      return requestBackend(...arr)
    },
    addClass: addClassBackend,
    removeClass: removeClassBackend,
    scrollTo: scrollToBackend,
    validMobile: validMobile,
    validEmail: validEmail,
    sendCode: sendCodeBackend,
    userSignup: userSignupBackend,
    userForgetpass: userForgetpassBackend,
    validUsername: validUsername,
    config: () => {
      return configBackend
    },
    checkLogin: checkLogin,
    getQrcode: getQrcode
  }
}
