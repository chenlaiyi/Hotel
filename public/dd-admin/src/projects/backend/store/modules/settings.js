/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-07-30 20:54:25
 */
import variables from '@/styles/element-variables.scss'
import defaultSettings from '@projectName/settings'
const {
  showSettings,
  tagsView,
  fixedHeader,
  sidebarLogo,
  menuType,
  Layout,
  isRegister,
  showTopNav
} = defaultSettings
console.log('defaultSettings', defaultSettings)
const state = {
  theme: variables.theme,
  showSettings: showSettings,
  tagsView: tagsView,
  fixedHeader: fixedHeader,
  sidebarLogo: sidebarLogo,
  menuType: menuType,
  isRegister: isRegister,
  Layout: Layout,
  showTopNav: showTopNav,
  isIndex: false,
  addonsTitle: '系统',
  plugins: {}
}

const mutations = {
  CHANGE_SETTING: (state, {
    key,
    value
  }) => {
    // eslint-disable-next-line no-prototype-builtins
    if (state.hasOwnProperty(key)) {
      state[key] = value
    }
  },
  SET_MENUTYPE: (state, menuType) => {
    state.menuType = menuType
  },
  SET_ADDONSTITLE: (state, addonsTitle) => {
    console.log('SET_ADDONSTITLE', addonsTitle)
    state.addonsTitle = addonsTitle
  },
  SET_LAYOUT: (state, Layout) => {
    state.Layout = Layout
  },
  SET_INDEX: (state, isIndex) => {
    state.isIndex = isIndex
  },
  SET_PLUGINS: (state, plugins) => {
    console.log('plugins00', plugins)
    state.plugins = plugins
  }
}

const actions = {
  changeSetting({
    commit
  }, data) {
    commit('CHANGE_SETTING', data)
  },
  setMenuType({
    commit
  }, menuType) {
    commit('SET_MENUTYPE', menuType)
  },
  setAddonsTitle({
    commit
  }, addonsTitle) {
    commit('SET_ADDONSTITLE', addonsTitle)
  },
  setIsIndex({
    commit
  }, isIndex) {
    commit('SET_INDEX', isIndex)
  },
  setPlugins({
    commit
  }, plugins) {
    commit('SET_PLUGINS', plugins)
  },
  setLayout({
    commit
  }, Layout) {
    commit('SET_LAYOUT', Layout)
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
