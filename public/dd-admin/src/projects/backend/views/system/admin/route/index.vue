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
      :handle="tableHandle"
      @getList="getList"
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
  createRoute,
  updateRoute,
  deleteRoute,
  fetchAvailable,
  fetchView
} from '@projectName/views/system/api/admin/route.js'
import { fetchAddons } from '@projectName/views/system/api/admin/permission.js'
// import waves from '@projectName/directive/waves' // waves directive
import { parseTime } from '@projectName/utils'

export default {
  data() {
    return {
      // 表格数据 start
      tableColumns: [
        { label: '名称', prop: 'title' },
        { label: '路由别名', prop: 'route_name' },
        { label: '路由地址', prop: 'name' },
        { label: '描述', prop: 'description' },
        {
          label: '权限类型',
          prop: 'is_sys',
          width: 80,
          align: 'center',
          render: (h, { row }) => {
            return h(
              'el-tag',
              {
                attrs: {
                  is_sys: row.is_sys === 1 ? '系统' : '模块',
                  size: 'small'
                }
              },
              row.is_sys === 1 ? '系统' : row.addons.title
            )
          }
        }
      ],
      tableHandle: [
        {
          label: '修改',
          type: 'primary',
          isPop: false,
          method: (row) => {
            this.editItem(row)
          }
        },
        {
          label: '删除',
          type: 'danger',
          isPop: true,
          method: (row) => {
            this.deleteItem(row)
          }
        }
      ],
      // 表格数据end
      // 检索 start
      filterInfo: {
        data: {
          page: 1,
          pageSize: 20
        },
        fieldList: [
          { label: '路由名称', type: 'input', value: 'AuthRoute[title]' },
          { label: '路由地址', type: 'input', value: 'AuthRoute[name]' },
          {
            label: '插件名称',
            type: 'select',
            value: 'AuthRoute[module_name]',
            list: 'addonsList'
          },
          {
            label: '路由类型',
            type: 'select',
            value: 'AuthRoute[route_type]',
            list: 'routeList'
          },
          {
            label: '路由描述',
            type: 'input',
            value: 'AuthRoute[description]',
            min: 1,
            max: 10
          }
        ]
      },
      listTypeInfo: {
        addonsList: this.initAddons(2),
        routeList: [
          { name: '选择路由类型', id: '' },
          { name: '目录', id: 0 },
          { name: '页面', id: 1 },
          { name: '按钮', id: 2 },
          { name: '接口', id: 3 }
        ]
      },
      // 检索 end
      tableKey: 0,
      formInline: {
        user: '',
        region: ''
      },
      // 表单数据 start
      formData: {
        route_type: 0,
        module_name: 'system'
      },
      formConfig: {
        isShowBackBtn: false,
        formDesc: {
          route_type: {
            type: 'radio',
            label: '路由类型',
            options: [
              { text: '目录', value: 0 },
              { text: '页面', value: 1 },
              { text: '按钮', value: 2 },
              { text: '接口', value: 3 }
            ],
            default: 1
          },
          name: {
            type: 'input',
            label: '地址',
            rules: [
              {
                type: 'url',
                trigger: 'blur',
                validator: (rule, value, callback) => {
                  if (
                    this.formData.route_type === 1 &&
                    value.indexOf('.vue') === -1
                  ) {
                    return callback(new Error('必须以.vue结尾'))
                  } else if (this.formData.route_type === 0 &&
                    value.indexOf('*') === -1) {
                    return callback(new Error('必须以*结尾'))
                  } else {
                    return callback()
                  }
                }
              }
            ]
          },
          title: {
            type: 'input',
            label: '中文名称'
          },
          route_name: {
            type: 'input',
            label: '英文名称',
            isOptions: true,
            vif: (data) => data.name,
            // 这里必须制定 optionsLinkageFields 做为关联字段，当次字段值发生变化时，会重新触发请求
            optionsLinkageFields: ['name'],
            options: (data) => {
              console.log('路由名称', data)
              data.name = data.name.replaceAll('\\', '/').replaceAll('src', '').replaceAll('views', '').replaceAll('//', '/')
              const str = data.name.replaceAll('_', '-').replaceAll('/', '-').replaceAll('.vue', '')
              data.route_name = str.replace('-', '')
            }
          },
          module_name: {
            type: 'select',
            label: '插件名称',
            isOptions: true,
            options: this.initAddons(1)
          },
          description: {
            type: 'textarea',
            label: '描述',
            attrs: {
              autosizeType: 'switch',
              autosize: false
            }
          }
        },
        order: [
          'route_type',
          'module_name',
          'name',
          'title',
          'route_name',
          'description'
        ]
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
        update: '编辑路由',
        create: '添加路由'
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
    initAddons(type) {
      const arr = []
      const list = []
      arr.push({ text: '选择模块', value: '' })
      list.push({ name: '选择模块', id: '' })
      fetchAddons().then((res) => {
        const keys = Object.keys(res.data)
        const values = Object.values(res.data)
        values.forEach((item, index) => {
          arr.push({ text: item, value: keys[index] })
          list.push({ name: item, id: keys[index] })
        })
      })
      return type === 1 ? arr : list
    },
    // 触发请求
    async resetForm() {
      if (this.$refs.form) {
        await this.$refs.form.resetForm()
      }
    },
    handleSelectionChange(val) {
      console.log('传递来的', val)
    },
    // 更新，和创建
    handleRequest(data) {
      const that = this
      console.log('提交数据', that.dialogStatus)
      if (that.dialogStatus === 'create') {
        createRoute(data).then((response) => {
          that.getList()
          that.dialogFormVisible = false
        })
      } else if (that.dialogStatus === 'update') {
        updateRoute(data).then((res) => {
          that.getList()
          that.dialogFormVisible = false
        })
      }

      return Promise.resolve()
    },
    // 单行数据删除
    deleteItem(row) {
      const that = this
      deleteRoute(row.id).then((res) => {
        that.getList()
        that.dialogFormVisible = false
      })
    },
    handleRequestSuccess() {
      this.$message.success('发送成功')
    },
    getList(res) {
      const that = this
      that.listLoading = true
      fetchList(that.filterInfo.data).then((response) => {
        that.total = response.data.dataProvider.total
        that.list = response.data.dataProvider.allModels
        that.listLoading = false
      })
    },
    // 获取系统新的路由
    getAvailable() {
      fetchAvailable().then((res) => {
        console.log('fetchAvailable', res)
      })
    },
    /** 搜索 */
    handleFilter(row) {
      const that = this
      that.$set(that.filterInfo, 'data', row)
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
        this.listQuery.sort = '+d'
      } else {
        this.listQuery.sort = '-d'
      }
      this.handleFilte()
    },
    handleCreate() {
      const that = this
      that.dialogStatus = 'create'
      that.dialogFormVisible = true
      this.resetForm()
    },
    handleDeleteAll() {
      console.log('删')
    },
    handleUpdate(row) {
      this.temp = Object.assign({}, row) // copy obj
      this.temp.timestamp = new Date(this.temp.timestap)
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
      this.dialogStatus = 'update'

      fetchView(row.id).then((res) => {
        console.log('view', res)
      })
    },
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then((excel) => {
        const tHeader = ['title', 'create_tim']
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
            return parseTime(v[filterVal])
          } else {
            return v[j]
          }
        })
      )
    },
    getSortClass: function(key) {
      const sort = this.listQuery.sort
      return sort === `+${key}` ? 'ascending' : 'descendig'
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
