<template>
  <div v-if="(item.type == menuType || item.plugin == menuType) || item.path == '/' || item.type == 'service'">
    <template
      v-if="
        hasOneShowingChild(item.children, item) &&
          (!onlyOneChild.children.length || onlyOneChild.noShowingChildren) &&
          !item.alwaysShow
      "
    >
      <app-link v-if="onlyOneChild.meta" class="app-link" :to="resolvePath(onlyOneChild.path)" :class="{'app-link3':item.level_type === 3,'app-link1':item.level_type === 1,'app-link5':item.level_type === 5}">
        <el-menu-item
          v-show="!item.hidden && item.level_type !== 6"
          :index="onlyOneChild.meta.tag"
          :class="{ 'submenu-title-noDropdown': !isNest,'el-menu-item5':item.level_type === 5 }"
        >
          <!-- :icon="onlyOneChild.meta.icon || (item.meta && item.meta.icon)" -->
          <item
            :title="onlyOneChild.meta.title"
          />
        </el-menu-item>
      </app-link>
    </template>
    <el-submenu v-else ref="subMenu" :index="item.meta?item.meta.tag:resolvePathIndex(item.type,item.level_type,item.id)" popper-append-to-body>
      <template slot="title">
        <item
          v-if="item.meta"
          :icon="item.meta && item.meta.icon"
          :title="item.meta.title"
        />
      </template>
      <sidebar-item
        v-for="(child,index) in item.children"
        v-show="!child.hidden && item.level_type !== 6"
        :key="child.path+index"
        :is-nest="true"
        :item="child"
        :base-path="resolvePath(child.path)"
        class="nest-menu"
      />
    </el-submenu>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import path from 'path'
import { isExternal } from '@/utils/validate'
import Item from './Item'
import AppLink from './Link'
import FixiOSBug from './FixiOSBug'
export default {
  name: 'SidebarItem',
  components: {
    Item,
    AppLink
  },
  mixins: [FixiOSBug],
  props: {
    // route object
    item: {
      type: Object,
      required: {},
      default() {
        return {}
      }
    },
    isNest: {
      type: Boolean,
      default: false
    },
    isPlugins: {
      type: Boolean,
      default: false
    },
    isServerMenu: {
      type: Boolean,
      default: false
    },
    basePath: {
      type: String,
      default: ''
    }
  },
  data() {
    // To fix https://github.com/PanJiaChen/vue-admin-template/issues/237
    // TODO: refactor with render function
    this.onlyOneChild = null
    return {
      // menuType: 'sys'
    }
  },
  computed: {
    ...mapGetters(['menuType', 'plugins', 'LeftMenu', 'subLeftIsActive'])
  },
  created() {
    console.log('item', this.item, this.isServerMenu)
    console.log('isPlugins', this.isPlugins)
  },
  methods: {
    hasOneShowingChild(children = [], parent) {
      console.log('hasOneShowingChild1')
      const showingChildren = children.filter((item) => {
        if (item.hidden) {
          return false
        } else {
          // Temp set(will be used if only has one showing child)
          this.onlyOneChild = item
          return true
        }
      })
      // When there is only one child router, the child router is displayed by default
      if (showingChildren.length === 1) {
        return true
      }

      // Show parent if there are no child router to display
      if (showingChildren.length === 0) {
        this.onlyOneChild = {
          ...parent,
          path: '',
          noShowingChildren: true
        }

        return true
      }

      return false
    },
    hasChild(item) {
      const activeMenu = this.LeftMenu.find((item) => item.id === this.subLeftIsActive)
      if (activeMenu.path.includes('plugins/default/index.vue')) {
        return true
      } else {
        return false
      }
    },
    resolvePath(routePath) {
      if (isExternal(routePath)) {
        return routePath
      }
      if (isExternal(this.basePath)) {
        return this.basePath
      }
      return path.resolve(this.basePath, routePath)
    },
    resolvePathIndex(type, level, id) {
      console.log('resolvePathIndex', type, level, id, type + '-' + level + '-' + id)
      return type + '-' + level + '-' + id
    }
  }
}
</script>
