<template>
  <div class="navbar-top" :class="{'navbar-top-flex':device === 'mobile'}">
    <div class="conent">
      <el-row type="flex" :gutter="10">
        <div v-if="device !== 'mobile'" class="sidebar-logo-main">
          <sidebar-logo v-if="showLogo" :collapse="!isCollapse" />
        </div>
        <el-col :lg="18" :md="8" :sm="12" :xs="10" class="justify-start flex-l">
          <div class="bloc-top">
            <hamburger
              id="hamburger-container"
              :is-active="sidebar.opened"
              class="hamburger-container"
              @toggleClick="toggleSideBar"
            />
          </div>

          <!-- <div v-if="device !== 'mobile' && Layout !== 'left' && showTopNav" class="nav-top">
            <div
              v-for="(item, index) in addons"
              :key="index"
              :class="{ 'nav-top-item': menuType != item.identifie, 'nav-top-item-active': menuType === item.identifie }"
              @click="goAddons(item)"
            >
              <div class="nav-top-item-icon" />
              <div class="nav-top-item-title">{{ item.title }}</div>
            </div>
          </div> -->
          <search id="header-search" class="search-input-menu hidden-xs-only" />

        </el-col>
        <el-col :lg="6" :md="16" :sm="12" :xs="14" class="user-right-main">
          <div class="flex-l justify-end align-center padding-right">
            <!-- 业务结束 -->
            <el-dropdown class="right-nav">
              <div v-if="num > 0" class="user-right">
                <el-badge :value="num" class="item">
                  <div class="navbar-notic text-pointer">
                    <svg-icon icon-class="sys-top-message" :size="16" />
                  </div>
                </el-badge>
              </div>

              <div v-else class="navbar-notic user-right text-pointer">
                <svg-icon icon-class="sys-top-message" :size="16" />
              </div>

              <el-dropdown-menu slot="dropdown" style="width: 280px">
                <el-dropdown-item>
                  <div class="center">
                    <div>消息中心</div>
                    <!-- <div class="icon" @click="delectRow">
                      <i class="el-icon-delete" />
                    </div> -->
                  </div>
                </el-dropdown-item>
                <el-dropdown-item divided>
                  <div v-if="messageist" style="color: #b4b4b4; font-size: 11px">
                    {{ messageist.title }}
                  </div>
                  <div v-if="messageist" style="font-size: 9px">
                    {{ messageist.content }}
                  </div>
                  <div v-if="!messageist" class="text-center">暂无消息</div>
                </el-dropdown-item>
                <el-dropdown-item divided @click.native="checkall">
                  <div class="all text-center">查看全部</div>
                  <!-- <router-link to="/system/notification.vue">
                    <el-dropdown-item>查看全部</el-dropdown-item>
                  </router-link> -->
                </el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
            <el-dropdown class="right-nav" trigger="hover">
              <div class="user-right">
                <div class="flex user-right-img">
                  <el-image
                    class="navbar-img text-pointer"
                    fit="contain"
                    :src="userinfo.avatar ? userinfo.avatar : headImg"
                  />
                </div>
                <div class="flex">
                  <div class="sub-el-icon el-icon-arrow-down" />
                </div>
              </div>
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item @click.native="goprofile">
                  个人资料</el-dropdown-item>
                <el-dropdown-item v-permission="['总管理员']" @click.native="clearCache">清除缓存</el-dropdown-item>
                <el-dropdown-item divided @click.native="logout"><span
                  style="display: block"
                >退出登陆</span></el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </div>
        </el-col>
      </el-row>
    </div>
  </div>
</template>

<script>
import headImg from '@/static/img/head.png'
import Search from './HeaderSearch/index.vue'
import SidebarLogo from './Sidebar/Logo'
import { fetchAuth } from '@projectName/api/addons.js'
import {
  clearCache,
  getMessagesList,
  getmeaasgenum
} from '@projectName/api/system.js'
import { mapGetters } from 'vuex'
import Hamburger from '@/components/Hamburger'
import { messagelistDelete } from '@projectName/api/message.js'
export default {
  name: 'NavbarTop',
  components: {
    Search,
    Hamburger,
    SidebarLogo
  },
  data() {
    return {
      isCollapse: false,
      userinfo: '',
      input1: '',
      messageist: {},
      num: 0,
      headImg: headImg,
      addons: []
    }
  },
  computed: {
    ...mapGetters(['sidebar', 'userInfo', 'userBloc', 'device', 'webSite', 'menuType', 'Layout', 'isIndex', 'showTopNav']),
    showLogo() {
      return this.$store.state.settings.sidebarLogo
    }
  },
  created() {
    const that = this
    console.log('that.showTop1', that.showTopNav, that.Layout)
    that.userinfo = that.userInfo.userInfo
    that.logo = that.userInfo.webSite.blogo
    this.getMessage()
    this.getMessagenum()
    this.getAddons()
  },
  methods: {
    goIndex() {
      console.log('goIndex')
      this.$router.push({ name: 'dashboard' })
    },
    getAddons() {
      fetchAuth().then((res) => {
        console.log('已授权', res)
        this.addons = res.data
      })
    },
    // 获取消息
    getMessage() {
      getMessagesList().then((res) => {
        this.messageist = res.data.dataProvider.allModels[0]
      })
    },
    // 获取未读消息数量
    getMessagenum() {
      getmeaasgenum().then((res) => {
        this.num = res.data.unread
      })
    },
    toggleSideBar() {
      this.$store.dispatch('app/toggleSideBar')
    },
    async logout() {
      await this.$store.dispatch('user/logout')
      this.$router.push(`/login?redirect=${this.$route.fullPath}`)
    },
    clearCache: function() {
      const that = this
      clearCache({
        cache: true,
        template: true
      }).then((res) => {
        const { code } = res
        if (code === 200) {
          that.$message.success('清理成功')
        }
      })
    },
    backSys() {
      this.$store.dispatch('settings/setMenuType', 'sys')
      this.$store.dispatch('settings/setAddonsTitle', '系统')
      this.$router.push({ name: 'dashboard' })
    },
    // 消息中心
    checkall() {
      this.$router.push({
        name: 'system-notification-index'
      })
    },
    // 账号设置
    setaccount() {
      this.$router.push({
        name: 'system-account-index'
      })
    },
    // 个人资料
    goprofile() {
      this.$router.push({
        name: 'profile-index'
      })
    },
    // 切换店铺
    changestore() {
      this.$router.push({
        name: 'system-store-index'
      })
    },
    // 删除
    delectRow() {
      const that = this
      messagelistDelete(that.messageist.id).then((response) => {
        if (response.code === 200) {
          that.getMessage()
          that.$message.success(response.message)
        } else {
          that.$message.error(response.message)
        }
      })
    },
    goAddons: function(item) {
      const menuType = item.identifie
      this.$store.dispatch('settings/setMenuType', menuType)
      this.$store.dispatch('settings/setAddonsTitle', item.title)
      this.$store.dispatch('settings/setPlugins', item)
      const path = '/' + menuType + '/default/index.vue'
      console.log('path', path, item)

      this.$router.push({ path: path })
    },
    goAddonsService: function(item) {
      const menuType = item.identifie
      this.$store.dispatch('settings/setMenuType', menuType)
      this.$store.dispatch('settings/setAddonsTitle', item.title)
      this.$store.dispatch('settings/setPlugins', item)
      const path = '/' + menuType + '/service/subscription.vue'
      console.log('path', path, item)

      this.$router.push({ path: path })
    }
  }
}
</script>

<style lang="scss" scoped>

</style>
