<template>
  <div class="app-container">
    <div class="top-search">
      <div class="bg-search">
        <!-- 公共操作 star -->
        <fire-oper-menu
          :show-excel-export="true"
          :show-excel-import="true"
          @ExcelExport="handleDownload"
          @handleCreate="handleCreate"
          @handleDeleteAll="handleDeleteAll"
        />
        <!-- 公共操作 end -->

        <!-- 检索 start -->
        <el-filter
          size="medium"
          :data="filterInfo.data"
          :field-list="filterInfo.fieldList"
          :list-type-info="listTypeInfo"
          :show-selection="false"
          @handleFilter="handleFilter"
          @handleReset="handleReset"
          @handleEvent="handleEvent"
        />
        <!-- 检索 end -->

      </div>
    </div>

    <!-- 数据列表 start -->
    <fire-table
      ref="table"
      :list="list"
      :total="total"
      :list-loading="listLoading"
      :list-query="filterInfo.data"
      :columns="tableColumns"
      row-key="id"
      @handleSelectionChange="handleSelectionChange"
    />
    <!-- 数据列表 end -->

    <el-dialog
      :close-on-click-modal="false"
      width="80%"
      :title="textMap[dialogStatus]"
      :visible.sync="dialogFormVisible"
    >
      <ele-form
        ref="form"
        v-model="formData"
        v-bind="formConfig"
        :request-fn="handleRequest"
        :is-show-back-btn="false"
        @request-success="handleRequestSuccess"
      />
    </el-dialog>
  </div>
</template>

<script>
import {
  fetchList,
  createItem,
  updateItem,
  deleteItem,
  getRules,
  fetchView
} from '@projectName/views/system/api/admin/rule.js'
// import waves from '@projectName/directive/waves' // waves directive
import { parseTime } from '@projectName/utils'
import { fetchAddons } from '../../api/admin/permission'

export default {
  data() {
    return {
      // 表格数据 start
      tableColumns: [
        { label: '名称', prop: 'name' },
        { label: '规则类', prop: 'className' }
      ],
      // 表格数据end
      // 检索 start
      filterInfo: {
        data: {
          page: 1,
          pageSize: 20
        },
        fieldList: [
          { label: '规则名称', type: 'input', value: 'BizRule[name]' }
        ]
      },
      listTypeInfo: {
        addonsList: this.initAddons()
      },
      // 检索 end
      tableKey: 0,
      formInline: {
        user: '',
        region: ''
      },
      // 表单数据 start
      formData: {},
      formConfig: {
        formDesc: {
          name: {
            type: 'input',
            label: '名称'
          },
          className: {
            type: 'input',
            label: '规则类'
          },
          data: {
            type: 'textarea',
            label: '数据',
            attrs: {
              autosizeType: 'switch',
              autosize: false
            }
          }
        },
        order: ['name', 'className', 'data']
      },
      // 表单数据 end
      total: 0,
      list: [],
      demo: {
        title: ''
      },
      temp: {
        id: undefined,
        importance: 1,
        remark: '',
        timestamp: new Date(),
        title: '',
        type: '',
        status: 'published'
      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: '编辑权限',
        create: '添加权限'
      },
      dialogPvVisible: false,
      pvData: [],
      downloadLoading: false
    }
  },
  created() {
    this.getList()
  },
  methods: {
    initAddons() {
      const arr = []
      arr.push({ id: ' ', name: '选择模块' })
      fetchAddons().then((res) => {
        const keys = Object.keys(res.data)
        const values = Object.values(res.data)
        values.forEach((item, index) => {
          arr.push({ id: keys[index], name: item })
        })
      })

      return arr
    },
    // 触发请求
    async resetForm() {
      console.log(this.$refs.form.resetForm())
      await this.$refs.form.resetForm()
    },
    handleSelectionChange(val) {
      console.log('传递来的', val)
    },
    // 更新，和创建
    handleRequest(data) {
      console.log(data)
      const that = this

      if (that.dialogStatus === 'create') {
        createItem(data).then((response) => {
          if (response.code === 200) {
            console.log(response)
            that.getList()
            that.dialogFormVisible = false
            that.$message.success(response.message)
          }
        })
      } else if (that.dialogStatus === 'update') {
        updateItem(data).then((res) => {
          if (res.code === 200) {
            console.log('更新', res)
            that.getList()
            that.dialogFormVisible = false
            that.$message.success(res.message)
          }
        })
      }

      return Promise.resolve()
    },
    // 单行数据删除
    deleteItem(row) {
      const that = this
      deleteItem(row.id).then((res) => {
        console.log('更新', res)
        that.getList()
        that.dialogFormVisible = false
      })
    },
    handleRequestSuccess() {},
    getList() {
      const that = this
      that.listLoading = true
      fetchList(that.filterInfo.data).then((response) => {
        console.log('response', response.data.dataProvider.allModels)
        that.total = response.data.dataProvider.total
        that.list = response.data.dataProvider.allModels
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
    editItem(row) {
      const that = this
      that.formData = { ...row }
      that.dialogFormVisible = true
      console.log('formData', row, that.formData)
      this.dialogStatus = 'update'

      fetchView(row.id).then((res) => {
        console.log('view', res)
      })
    },
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then((excel) => {
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
      return this.list.map((v) =>
        filterVal.map((j) => {
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
<style  lang="scss" scoped>
.el-transfer-panel {
  width: 300px;
}
::v-deep .el-dialog {
  border-radius: 4px !important;
  text-align: center;
  font-weight: bold;
}
::v-deep .el-form-item--medium .el-form-item__content {
  text-align: initial;
}
</style>
