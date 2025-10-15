/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-10-30 10:40:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2024-03-19 19:18:54
 */
import {
  login,
  logout,
  getInfo
} from '@projectName/api/user.js'
import {
  getToken,
  setToken,
  removeToken
} from '@projectName/utils/auth'
import router, {
  resetRouter
} from '@projectName/router'

const state = {
  access_token: getToken(),
  addons: {},
  name: '',
  avatar: '',
  introduction: '',
  roles: [],
  webSite: {},
  userInfo: {}
}

const mutations = {
  SET_TOKEN: (state, token) => {
    state.access_token = token
  },
  SET_ROLES: (state, roles) => {
    state.roles = roles
  },
  SET_WEBSITE: (state, webSite) => {
    state.webSite = webSite
  },
  SET_INTRODUCTION: (state, introduction) => {
    state.introduction = introduction
  },
  SET_NAME: (state, name) => {
    state.name = name
  },
  SET_USER: (state, userInfo) => {
    state.userInfo = userInfo
  },
  SET_AVATAR: (state, avatar) => {
    state.avatar = avatar
  },
  SET_ADDONS: (state, addons) => {
    state.addons = addons
  }
}

const actions = {
  // user login
  login({
    commit
  }, userInfo) {
    const {
      username,
      openid,
      password,
      sms_code,
      type,
      mobile
    } = userInfo
    return new Promise((resolve, reject) => {
      const data = type === 1 ? {
        username: username.trim(),
        password: password,
        openid: openid,
        type
      } : {
        password: password,
        openid: openid,
        sms_code,
        type,
        mobile
      }
      login(data).then(response => {
        const {
          access_token,
          addons
        } = response.data
        if (addons) {
          commit('SET_ADDONS', addons)
        }

        commit('SET_TOKEN', access_token)
        setToken(access_token)
        resolve(response)
      }).catch(error => {
        console.log(error)
        reject(error)
      })
    })
  },
  autoLogin({
    commit,
    state
  }, userinfo) {
    return new Promise((resolve, reject) => {
      // console.log(response)
      if (!userinfo) {
        reject('Verification failed, please Login again.')
      }
      const {
        user,
        addons,
        access_token
      } = userinfo
      commit('SET_USER', user)
      commit('SET_NAME', user.username)
      commit('SET_AVATAR', user.avatar ? user.avatar : '')
      if (addons) {
        commit('SET_ADDONS', addons)
      }
      commit('SET_TOKEN', access_token)
      setToken(access_token)
      resolve(userinfo)
    })
  },
  // get user info
  getInfo({
    commit,
    state
  }, url) {
    return new Promise((resolve, reject) => {
      getInfo({ url: url }).then(response => {
        const {
          data
        } = response
        // console.log(response)
        if (!data) {
          reject('Verification failed, please Login again.')
        }

        const {
          Website,
          userinfo
        } = data
        // console.log('Website', Website, userinfo)
        const {
          user
        } = userinfo
        commit('SET_USER', user)
        commit('SET_WEBSITE', Website)
        commit('SET_NAME', user.username)
        commit('SET_AVATAR', user.avatar ? user.avatar : '')
        resolve(userinfo)
      }).catch(error => {
        reject(error)
      })
    })
  },
  setRoles({
    commit
  }, roles) {
    console.log('roles1', roles)

    commit('SET_ROLES', roles)
  },
  // user logout
  logout({
    commit,
    state,
    dispatch
  }) {
    return new Promise((resolve, reject) => {
      logout(state.token).then(() => {
        commit('SET_TOKEN', '')
        removeToken()
        resetRouter()
        // reset visited views and cached views
        // to fixed https://github.com/PanJiaChen/vue-element-admin/issues/2485
        dispatch('tagsView/delAllViews', null, {
          root: true
        })

        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },
  // remove token
  resetToken({
    commit
  }) {
    return new Promise(resolve => {
      commit('SET_TOKEN', '')
      removeToken()
      resolve()
    })
  },
  set_token({
    commit
  }, token) {
    commit('SET_TOKEN', token)
    setToken(token)
  },
  // dynamically modify permissions
  async changeRoles({
    commit,
    dispatch
  }, role) {
    const token = role + '-token'

    commit('SET_TOKEN', token)
    setToken(token)

    const {
      roles
    } = await dispatch('getInfo')

    resetRouter()

    // generate accessible routes map based on roles
    const accessRoutes = await dispatch('permission/generateRoutes', roles, {
      root: true
    })
    // dynamically add accessible routes
    router.addRoutes(accessRoutes)

    // reset visited views and cached views
    dispatch('tagsView/delAllViews', null, {
      root: true
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
