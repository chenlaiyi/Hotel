<template>
  <div class="left-sidebar-main left-sidebar-main-opened" :class="{'left-lay-left':Layout === 'left'}">
    <div v-if="Layout === 'unSubfield'">
      <el-scrollbar
        class="text-left"
        wrap-class="scrollbar-wrapper"
        @mouseleave.native="isHover = false"
      >
        <el-menu
          ref="menu"
          :default-active="activeMenu"
          :collapse="isCollapse"
          :background-color="variables.leftmenuBg"
          :text-color="variables.leftmenuText"
          :unique-opened="true"
          :active-text-color="variables.leftmenuActiveText"
          :collapse-transition="false"
          mode="vertical"
          @open="handleOpen"
          @close="handleClose"
        >
          <!-- 正常菜单 start -->
          <sidebar-item
            v-for="(route, index) in leftSubMenu"
            :key="route.path + isActive + index"
            :item="route"
            :base-path="route.path"
          />
        <!-- 正常菜单 end -->
        </el-menu>
      </el-scrollbar>
    </div>

    <!-- 左侧两栏 start -->
    <div v-else class="sidebar-main" @mouseleave.native="isHover = false">
      <div class="main-sidebar-container">
        <ul class="s-navs">
          <li
            v-for="(item, index) in LeftMenu"
            v-show="item.type == menuType"
            :key="index"
            class="s-navs-item"
            :class="{ active: subLeftIsActive == item.id }"
            @mouseover="hoverTargetMenu(item.id)"
            @click="targetMenu(item.id)"
          >
            <h2 class="padding-top-xs">
              <!-- <i
                class="icon sub-el-icon"
                :class="item.meta ? item.meta.icon : 'bug'"
              /> -->
              <svg-icon
                class="icon sub-el-icon"
                :icon-class="(item.meta && item.meta.icon) ? item.meta.icon : 'bug'"
                :size="22"
              />
              <div class="sub-el-title padding-top-xs">{{ item.meta ? item.meta.title : "" }}</div>
            </h2>
          </li>
        </ul>
      </div>
      <div class="s-navs-item-child ">
        <!-- 正常开始 -->
        <el-scrollbar
          class="text-center subfield-sidebar-container"
          :class="{'subfield-sidebar-hide': !isHover }"
          wrap-class="scrollbar-wrapper"
          @mouseleave.native="isHover = false"
        >
          <el-menu
            ref="menu"
            :default-active="activeMenu"
            :collapse="isCollapse"
            :background-color="variables.leftmenuBg"
            :text-color="variables.leftmenuText"
            :unique-opened="true"
            :active-text-color="variables.leftmenuActiveText"
            :collapse-transition="false"
            mode="vertical"
            @open="handleOpen"
            @close="handleClose"
          >
            <!-- 正常菜单 start -->
            <sidebar-item
              v-for="(route, index) in leftSubMenu"
              :key="route.path + isActive + index"
              :item="route"
              :base-path="route.path"
            />
            <!-- 正常菜单 end -->
          </el-menu>
        </el-scrollbar>
        <!-- 正常结束 -->
      </div>
    </div>
    <!-- 左侧两栏 end -->
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import SidebarItem from './SidebarItem'
import variables from '@projectName/styles/variables.scss'
import { isExternal } from '@/utils/validate'
import path from 'path'
import { config } from '@projectName/utils/publicUtil'
import { fetchAuth } from '@projectName/api/addons.js'
export default {
  components: {
    SidebarItem
  },
  data() {
    return {
      isActive: 0,
      isCollapse: false,
      menuEnums: 0,
      isHover: false,
      // 分栏数据
      leftSubMenu: [],
      addons: [],
      siteName: config.siteName
    }
  },
  computed: {
    ...mapGetters([
      'permission_routes',
      'device',
      'sidebar',
      'LeftMenu',
      'menuType',
      'webSite',
      'Layout',
      'subLeftIsActive',
      'storeName'
    ]),
    // subLeftIsActive() {
    //   const that = this
    //   let id = 0
    //   if (this.$store.state.permission.subLeftIsActive) {
    //     id = this.$store.state.permission.subLeftIsActive
    //     that.initLeftSubMenu(id)
    //   }
    //   return id
    // },
    activeMenu() {
      const route = this.$route
      console.log('activeMenu', route)
      if (route.meta.type !== this.menuType) {
        console.log('activeMenu-change', route)
      }
      return route.meta.tag
    },
    showLogo() {
      return this.$store.state.settings.sidebarLogo
    },
    variables() {
      return variables
    },
    resolvePath(routePath) {
      if (isExternal(routePath)) {
        return routePath
      }
      if (isExternal(this.basePath)) {
        return this.basePath
      }
      return path.resolve(this.basePath, routePath)
    }
  },
  watch: {
    menuEnums(newval) {
      console.log('menuEnums', newval, this.menuEnums)
    },
    menuType(val) {
      console.log('menuType', val)
      this.targetMenuAddons(val) // 跳转首页
    },
    Layout(val) {
      this.initPage()
    }
  },
  created() {
    this.initPage()
  },
  methods: {
    initPage() {
      const that = this
      const menus = that.LeftMenu.find((item) => item.type === that.menuType)
      console.log('menuType--menuType', that.LeftMenu, menus, this.menuType)
      const id = menus?.id || 0
      console.log('menuType--leftStyle', id, this.Layout)

      that.isActive = id
      // 初始化分栏数据
      // 初始化分栏数据
      if (this.Layout === 'unSubfield') {
        that.initLeftSubMenuAll(id)
      } else if (this.Layout === 'subfield') {
        that.initLeftSubMenu(id)
      } else if (this.Layout === 'left') {
        this.getAddons()
        that.initLeftSubMenuLeft(id)
      }
    },
    getAddons() {
      fetchAuth().then((res) => {
        console.log('已授权', res)
        this.addons = res.data
      })
    },
    hoverMainClass(route) {
      let cl = 'el-menu'
      switch (route.level_type) {
        case 3:
          // 二级菜单
          // cl = 'el-menu'
          cl = 'el-menu-item-child'

          break
        case 4:
          // 二级菜单(无三级菜单)
          cl = 'el-menu'
          break
        case 5:
          // 三级菜单
          cl = 'el-menu'

          break

        default:
          cl = 'el-menu'

          break
      }
      return cl
    },
    handleOpen() {
      console.log('2')
    },
    handleClose() {
      console.log('3')
    },
    initLeftSubMenu(id) {
      const that = this
      const leftSubMenu = that.permission_routes.find((item) => item.id === id)
      // console.log('initLeftSubMenu', leftSubMenu.children, that.permission_routes, id)
      if (leftSubMenu) {
        that.leftSubMenu = leftSubMenu.children
      }
    },
    initLeftSubMenuAll(id) {
      const that = this
      that.leftSubMenu = that.permission_routes
    },
    initLeftSubMenuLeft(id) {
      const that = this
      const leftSubMenu = that.permission_routes.find((item) => item.id === id)
      // console.log('initLeftSubMenu', leftSubMenu.children, that.permission_routes, id)
      if (leftSubMenu) {
        that.leftSubMenu = leftSubMenu.children
      }
    },
    hoverSpanColor(index) {
      const clor = ['#9694FF', '#57CAEB', '#FFA754', '#FF7976', '#5DDAB4']
      console.log('颜色')
      const i = index % 5
      return { 'background': clor[i] }
    },
    async hoverTargetMenu(id) {
      const that = this
      that.isActive = id
      this.menuEnums = 0
      that.initLeftSubMenu(id)

      if (that.leftSubMenu.length > 1) {
        that.isHover = true
      } else {
        that.isHover = false
      }
      this.$store.commit('permission/SET_LEFTACTIVE', id)
    },
    async targetMenu(id) {
      const that = this
      that.isActive = id
      this.menuEnums = 0
      that.initLeftSubMenu(id)
      if (that.device !== 'mobile') {
        that.activeFirst(that.leftSubMenu)
      }
      this.$store.commit('permission/SET_LEFTACTIVE', id)
    },
    initLeftSubMenuAddons(identifie) {
      const that = this
      if (identifie) {
        console.log('initLeftSubMenuAddons', identifie, that.permission_routes)
        const addons = this.addons.find((item) => item.identifie === identifie)
        console.log('initLeftSubMenuAddons-initaddonstitle', addons.title)
        this.$store.dispatch('settings/setMenuType', identifie)
        this.$store.dispatch('settings/setAddonsTitle', addons.title)
        // this.$store.dispatch('settings/setPlugins', item)
        console.log('initLeftSubMenuAddons', identifie, that.permission_routes)
        const leftSubMenu = that.permission_routes.filter((item) => item.type === identifie)
        console.log('initLeftSubMenuAddons', leftSubMenu)
        if (leftSubMenu) {
          that.leftSubMenu = leftSubMenu
        }
      }
    },
    async targetMenuAddons(identifie) {
      const that = this
      that.isActive = identifie
      this.menuEnums = 0
      console.log('targetMenuAddons', identifie)
      // this.$store.dispatch('app/toggleSideBar')
      that.initLeftSubMenuAddons(identifie)
      if (that.device !== 'mobile') {
        that.activeFirst(that.leftSubMenu)
      }
      this.$store.commit('permission/SET_LEFTACTIVE', identifie)
    },
    activeFirst(menus) {
      let name = ''
      console.log('activeFirst-menus', menus)
      if (menus.length > 0) {
        if (menus[0].children.length > 0 && menus[0].children[0].name !== 'main-index') {
          name = menus[0].children[0].name
        } else {
          name = menus[0].name
        }
        console.log('activeFirst-menus', menus, name)

        this.$router.push({ name: name })
      }
    }
  }
}
</script>

<style lang="scss" scoped>
::v-deep .el-menu-item {
  margin: 10px !important;
  font-size: 14px !important;
}
</style>
