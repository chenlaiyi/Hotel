<template>
  <div class="app-container">
    <el-row type="flex" :gutter="10">
      <el-col :xs="4" :sm="6" :md="4" :lg="3" :xl="6" justify="start">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span>所有的公司与商户</span>
          </div>
          <div>
            <el-tree :data="listtree" :props="defaultProps" @node-click="handleNodeClick" />
          </div>
        </el-card>
      </el-col>
      <el-col :xs="16" :sm="12" :md="2" :lg="12" :xl="18">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span>可授权的应用</span>
          </div>
          <div style="text-align: center">
            <el-transfer
              v-model="value"
              style="text-align: left; display: inline-block"
              filterable
              :left-default-checked="[2, 3]"
              :right-default-checked="[1]"
              :render-content="renderFunc"
              :titles="['未授权应用', '已授权应用']"
              :button-texts="['取消', '授权']"
              :format="{
                noChecked: '${total}',
                hasChecked: '${checked}/${total}'
              }"
              :data="data"
              @change="handleChange"
            />
          </div>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { fetchList } from '@projectName/views/system/api/addons/addons.js'
import { parseTime } from '@projectName/utils'
import FireMediaBox from '@/components/FireMediaBox'
// 穿梭框
const generateData = _ => {
  const data = []
  for (let i = 1; i <= 15; i++) {
    data.push({
      key: i,
      label: `备选项 ${i}`,
      disabled: i % 4 === 0
    })
  }
  return data
}

export default {
  components: {
    FireMediaBox
  },
  data() {
    return {
      listtree: [{
        label: '一级 1',
        children: [{
          label: '二级 1-1',
          children: [{
            label: '三级 1-1-1'
          }]
        }]
      }, {
        label: '一级 2',
        children: [{
          label: '二级 2-1',
          children: [{
            label: '三级 2-1-1'
          }]
        }, {
          label: '二级 2-2',
          children: [{
            label: '三级 2-2-1'
          }]
        }]
      }, {
        label: '一级 3',
        children: [{
          label: '二级 3-1',
          children: [{
            label: '三级 3-1-1'
          }]
        }, {
          label: '二级 3-2',
          children: [{
            label: '三级 3-2-1'
          }]
        }]
      }],
      defaultProps: {
        children: 'children',
        label: 'label'
      },
      data: generateData(),
      value: [1],
      value4: [1],
      renderFunc(h, option) {
        return <span>{ option.key } - { option.label }</span>
      }

    }
  },
  computed: {
    ...mapGetters(['accessToken'])
  },
  created() {
    this.getList()
  },
  methods: {
    handleNodeClick(data) {
      console.log(data)
    },
    // 触发请求
    async resetForm() {
      console.log(this.$refs.form.resetForm())
      await this.$refs.form.resetForm()
    },
    handleSelectionChange(val) {
      console.log('传递来的', val)
    },
    handleRequestSuccess() {
      this.$message.success('发送成功')
    },
    getList() {
      const that = this
      that.listLoading = true
      fetchList(that.filterInfo.data).then(response => {
        console.log('response', response)
        that.total = response.data.dataProvider.total
        const list = response.data.dataProvider.allModels
        that.list = [...list]
        console.log('列表数据层级测试', that.list)
        that.listLoading = false
      })
    },
    /** 搜索 */
    handleFilter(row) {
      const that = this
      console.log(row)
      that.$set(that.filterInfo, 'data', row)
      console.log('检索前', that.filterInfo.data)
      that.getList()
    },
    /** 重置 */
    handleReset(row) {
      console.log(row)
    },
    /** 焦点失去事件 */
    handleEvent(row) {
      console.log(row)
    },
    handleModifyStatus(row, status) {
      this.$message({
        message: '操作Success',
        type: 'success'
      })
      row.status = status
    },
    sortChange(data) {
      const { prop, order } = data
      if (prop === 'id') {
        this.sortByID(order)
      }
    },
    sortByID(order) {
      if (order === 'ascending') {
        this.listQuery.sort = '+id'
      } else {
        this.listQuery.sort = '-id'
      }
      this.handleFilter()
    },
    handleCreate() {
      const that = this
      this.resetForm()
      console.log(this.list)
      that.dialogStatus = 'create'
      that.dialogFormVisible = true
    },
    handleDeleteAll() {
      console.log('删除')
    },
    handleUpdate(row) {
      this.temp = Object.assign({}, row) // copy obj
      this.temp.timestamp = new Date(this.temp.timestamp)
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
    },
    updateData() {},
    handleDelete(row, index) {
      this.$notify({
        title: 'Success',
        message: 'Delete Successfully',
        type: 'success',
        duration: 2000
      })
      this.list.splice(index, 1)
    },
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['title', 'create_time']
        const filterVal = [{ title: '12', create_time: '23435345353' }]
        const data = this.formatJson(filterVal)
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'table-list'
        })
        this.downloadLoading = false
      })
    },
    formatJson(filterVal) {
      return this.list.map(v =>
        filterVal.map(j => {
          if (j === 'timestamp') {
            return parseTime(v[j])
          } else {
            return v[j]
          }
        })
      )
    },
    getSortClass: function(key) {
      const sort = this.listQuery.sort
      return sort === `+${key}` ? 'ascending' : 'descending'
    }
  }
}
</script>
<style type="text/css">
  .main-content{
    display: flow-root;
  }
</style>
