/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-02-09 06:36:47
 */
import Cookies from 'js-cookie'
import ConfigGlobal from '@/config/config'
const state = {
  sidebar: {
    opened: true,
    withoutAnimation: false
  },
  device: 'desktop',
  size: Cookies.get('size') || 'medium',
  blocId: Cookies.get('bloc-id') || ConfigGlobal.bloc_id,
  storeId: Cookies.get('store-id') || ConfigGlobal.store_id,
  storeName: '',
  config: {}
}

const mutations = {
  SET_CONFIG: (state, data) => {
    const blocId = data.modeType === 'unit' ? data.bloc_id : 0
    const storeId = data.modeType === 'unit' ? data.store_id : 0
    state.blocId = blocId
    state.storeId = storeId
  },
  TOGGLE_SIDEBAR: state => {
    state.sidebar.opened = !state.sidebar.opened
    state.sidebar.withoutAnimation = false
    if (state.sidebar.opened) {
      Cookies.set('sidebarStatus', 1)
    } else {
      Cookies.set('sidebarStatus', 0)
    }
  },
  CLOSE_SIDEBAR: (state, withoutAnimation) => {
    Cookies.set('sidebarStatus', 0)
    state.sidebar.opened = false
    state.sidebar.withoutAnimation = withoutAnimation
  },
  TOGGLE_DEVICE: (state, device) => {
    state.device = device
  },
  SET_SIZE: (state, size) => {
    state.size = size
    Cookies.set('size', size)
  },
  SET_BLOCS: (state, data) => {
    state.storeId = data.storeId
    state.blocId = data.blocId
    state.storeName = data.storeName
    state.config = data.config
    Cookies.set('bloc-id', data.blocId)
    Cookies.set('store-id', data.storeId)
  }
}

const actions = {
  toggleSideBar({
    commit
  }) {
    commit('TOGGLE_SIDEBAR')
  },
  closeSideBar({
    commit
  }, {
    withoutAnimation
  }) {
    commit('CLOSE_SIDEBAR', withoutAnimation)
  },
  closePage(state, option) {
    // 调用全局挂载的方法,关闭当前标签页
    option.vm.$store.dispatch('tagsView/delView', option.vm.$route)
    // 返回上一步路由，返回上一个标签页
    // this.$router.go(-1);
    if (!option.isForm) {
      option.vm.$router.push({
        name: option.toName,
        params: option.params
      })
    }
  },
  toggleDevice({
    commit
  }, device) {
    commit('TOGGLE_DEVICE', device)
  },
  setSize({
    commit
  }, size) {
    commit('SET_SIZE', size)
  },
  setConfig({
    commit
  }, config) {
    commit('SET_CONFIG', config)
  },
  setBlocs({
    commit
  }, blocs) {
    const data = {
      storeId: blocs.store_id,
      blocId: blocs.bloc_id,
      storeName: blocs.name,
      config: blocs.config
    }
    commit('SET_BLOCS', data)
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
