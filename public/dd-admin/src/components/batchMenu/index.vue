<template>
  <div class="batch-main">
    <div :class="device=== 'mobile'?'oper-center': 'oper-right'">
      <el-button
        v-show="showAdd"
        :type="device=== 'mobile'?'add-mini': 'add-small'"
        size="small"
        icon="el-icon-plus"
        @click="handleCreate"
      >添加</el-button>
      <el-button
        v-show="showDelete"
        :type="device=== 'mobile'?'del-mini': 'del-small'"
        size="small"
        icon="el-icon-delete"
        @click="handleDeleteAll"
      >删除</el-button>
      <el-button
        v-show="showExcelImport"
        :type="device=== 'mobile'?'import-mini': 'import-small'"
        size="small"
        icon="el-icon-s-fold"
        @click="ExcelImport"
      >批量导入</el-button>
      <el-button
        v-show="showExcelExport"
        :type="device=== 'mobile'?'export-mini': 'export-small'"
        size="small"
        icon="el-icon-s-unfold"
        @click="ExcelExport"
      >批量导出</el-button>
      <el-button
        v-show="showOperation"
        :type="device=== 'mobile'?'operation-mini': 'operation-small'"
        size="small"
        icon="el-icon-delete"
        @click="allOperation"
      >批量操作</el-button>

      <el-button
        v-for="(btn,index) in btns"
        :key="index"
        :type="device=== 'mobile'?'operation-mini': 'operation-small'"
        size="small"
        :icon="btn.icon"
        @click="btn.handleClick"
      >{{ btn.name }}</el-button>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: 'BatchMenu',
  props: {
    btns: {
      type: Array,
      default: () => {
        return []
      }
    },
    showAdd: {
      type: Boolean,
      default: true
    },
    showDelete: {
      type: Boolean,
      default: false
    },
    showExcelImport: {
      type: Boolean,
      default: false
    },
    showExcelExport: {
      type: Boolean,
      default: false
    },
    showOperation: {
      type: Boolean,
      default: false
    },
    items: {
      type: Array,
      default: () => {
        return [
          {
            label: '列表',
            url: 'index',
            active: true
          },
          {
            label: '添加',
            url: 'create',
            active: false
          }
        ]
      }
    },
    activeIndex: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      opTitle: ''
    }
  },
  computed: {
    ...mapState({
      device: state => state.app.device
    })
  },
  created: function() {
    this.opTitle = this.$route.meta.title
    console.log('this.$route', this.$route.meta.title)
  },
  methods: {
    // 创建
    handleCreate() {
      // console.log('add')
      this.$emit('handleCreate')
    },
    // 批量删除
    handleDeleteAll() {
      // console.log('delete')
      this.$emit('handleDeleteAll')
    },
    // 批量操作，用户可扩展
    allOperation() {
      this.$emit('allOperation')
    },
    // 导入
    ExcelImport() {
      this.$emit('ExcelImport')
    },
    // 导出
    ExcelExport() {
      this.$emit('ExcelExport')
    },
    handleSelect(key, keyPath) {
      const that = this
      that.items.forEach((item, k) => {
        if ('menu' + k === key) {
          window.location.href = item.url
        }
      })
    },
    reloadpage() {
      window.location.reload()
    },
    historypage() {
      history.go(-1)
    }
  }
}
</script>

<style lang="scss" scoped>

</style>
