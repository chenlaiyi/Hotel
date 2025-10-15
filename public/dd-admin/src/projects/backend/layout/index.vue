<template>
  <div :class="classObj" class="app-wrapper">
    <el-container>
      <el-header>
        <navbar-top />
      </el-header>
      <el-main>
        <div v-if="device==='mobile' && sidebar.opened" class="drawer-bg" @click="handleClickOutside" />
        <sidebar v-show="!isIndex" class="subfield-container sidebar-container" />
        <div class="main-container subfield-main-container" :class="{'main-container-unSubfield': Layout === 'unSubfield','subfield-main-container':!isIndex}">
          <div :class="{'fixed-header':fixedHeader}">
            <tags-view v-if="device != 'mobile' && needTagsView" />
          </div>
          <app-main />
        </div>
      </el-main>
    </el-container>

  </div>
</template>

<script>
import { AppMain, NavbarTop, Sidebar, TagsView } from './components'
import ResizeMixin from './mixin/ResizeHandler'
import { mapState } from 'vuex'

export default {
  name: 'Layout',
  components: {
    AppMain,
    TagsView,
    NavbarTop,
    Sidebar
  },
  mixins: [ResizeMixin],
  watch: {
    $route(val) {
      console.log('$router-0009', val)
      this.$store.dispatch('settings/setIsIndex', val.path === '/dashboard')
    }
  },
  computed: {
    ...mapState({
      sidebar: state => state.app.sidebar,
      device: state => state.app.device,
      showSettings: state => state.settings.showSettings,
      needTagsView: state => state.settings.tagsView,
      fixedHeader: state => state.settings.fixedHeader,
      Layout: state => state.settings.Layout,
      isIndex: state => state.settings.isIndex
    }),
    classObj() {
      return {
        hideSidebar: (!this.sidebar.opened && this.device === 'mobile') || this.isIndex,
        openSidebar: true,
        withoutAnimation: this.sidebar.withoutAnimation,
        mobile: this.device === 'mobile',
        subfield: this.Layout === 'subfield' || this.Layout === 'left',
        unSubfield: this.Layout === 'unSubfield'
      }
    },
    isDefault() {
      if (this.Layout === 'default') {
        return true
      } else {
        return false
      }
    }
  },
  created() {
    this.showSidebar = this.$router.history.current.fullPath !== '/dashboard'
    console.log('this.$router', this.$router.history.current.fullPath, this.showSidebar)
  },
  methods: {
    handleClickOutside() {
      this.$store.dispatch('app/closeSideBar', { withoutAnimation: false })
    },
    hidePanel() {
      this.$refs['settings'].hidePanel()
    }
  }
}
</script>

<style lang="scss" scoped>
  @import "~@/styles/mixin.scss";
  @import "~@/styles/variables.scss";

  .app-wrapper {
    @include clearfix;
    position: relative;
    height: 100%;
    width: 100%;

    &.mobile.openSidebar {
      // position: fixed;
      top: 0;
    }
  }

  .drawer-bg {
    background: #000;
    opacity: 0.3;
    width: 100%;
    top: 0;
    height: 100%;
    position: absolute;
    z-index: 999;
  }

  .fixed-header {
    position: fixed;
    top: 0;
    right: 0;
    z-index: 9;
    width: calc(100% - #{$sideBarWidth});
    transition: width 0.28s;
  }

  .hideSidebar .fixed-header {
    width: calc(100% - 54px)
  }

  .mobile .fixed-header {
    width: 100%;
  }
</style>
