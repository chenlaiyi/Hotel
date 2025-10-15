<template>
  <div class="app-container">
    <el-row type="flex" class="row-bg" justify="center">
      <el-transfer
        v-model="assigned"
        :data="available"
        :titles="['未添加', '已添加']"
        filterable
        :filter-method="filterMethod"
        :button-texts="['移除', '添加']"
        @change="handleChange"
      />
    </el-row>
  </div>
</template>

<script>
import {
  fetchAssign,
  fetchRemove,
  fetchRefresh
} from '@projectName/views/system/api/admin/route.js'
export default {
  data() {
    return {
      available: [],
      assigned: []
    }
  },
  created() {
    const that = this
    that.initPage()
  },
  methods: {
    async initPage() {
      const available = []
      const res = await fetchRefresh()
      console.log(res)
      const list = res.data.availables
      list.forEach((item, index) => {
        available.push({
          key: index,
          label: item
        })
      })
      console.log('available', available)
      this.assigned = res.data.assigneds
      this.available = available
    },
    handleChange(value, direction, movedKeys) {
      const move = []
      movedKeys.forEach((k) => {
        move.push(this.available.find((item) => item.key === k).label)
      })
      if (direction === 'right') {
        fetchAssign({ routes: move }).then((res) => {
          console.log(res)
        })
        console.log(move)
      } else {
        fetchRemove({ routes: move }).then((res) => {
          console.log(res)
        })
        console.log(move)
      }
    },
    filterMethod(query, item) {
      return item.label.indexOf(query) > -1
    }
  }
}
</script>

<style lang="less" scoped>
::v-deep .el-transfer-panel {
  width: max-content;
  min-width: 300px;
  .table-custom-drawer {
    height: 100%;
  }
  .drawer {
    height: 100%;
  }
  .drawer .el-drawer__body {
    height: 100%;
  }
  .el-transfer-panel__header {
    height: 40px;
  }
   .el-transfer-panel__body {
    height: calc(100vh - 240px);
  }
  .el-transfer-panel__list {
    height: 100%;
  }
  .el-transfer__buttons {
    margin: 10px;
    padding: 0px;
  }
  .el-transfer__button {
    padding: 10px;
    width: 35px;
    height: 35px;
  }
}
</style>
