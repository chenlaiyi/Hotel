<template>
  <div class="app-container">
    <!-- 检索 start -->
    <el-filter
      size="medium"
      :data="filterInfo.data"
      :field-list="filterInfo.fieldList"
      :show-selection="false"
      @handleFilter="handleFilter"
      @handleReset="handleReset"
      @handleEvent="handleEvent"
    />
    <!-- 检索 end -->

    <!-- 公共操作 star -->
    <fire-oper-menu
      :show-excel-export="true"
      :show-excel-import="true"
      @ExcelExport="handleDownload"
      @handleCreate="handleCreate"
      @handleDeleteAll="handleDeleteAll"
    />
    <!-- 公共操作 end -->

    <!-- 数据列表 start -->
    <fire-table
      ref="table"
      :list-query="filterInfo.data"
      :columns="tableColumns"
      :handle="tableHandle"
      :api="api"
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
  createad,
  updatead,
  deletead
} from '@projectName/views/system/api/system/website.js'
// import waves from '@projectName/directive/waves' // waves directive
import { parseTime } from '@projectName/utils'

export default {
  data() {
    return {
      // 表格数据 start
      api: fetchList,
      tableColumns: [
        { label: '名称', prop: 'title' },
        {
          label: '图片',
          prop: 'image',
          align: 'center',
          width: 120,
          render: (h, { row }) => {
            return h('el-image', { attrs: { src: row.images }}, row.images)
          }
        },
        // {
        //   label: '状态',
        //   prop: 'status',
        //   width: 80,
        //   align: 'center',
        //   render: (h, { row }) => {
        //     return h('el-tag', { attrs: { type: row.status === '正常' ? 'success' : 'info', size: 'small' }}, row.status)
        //   }
        // },
        { label: '创建时间', prop: 'createtime', align: 'center' }
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
          limit: 20,
          title: null
          // age: null,
          // count: 1,
          // sex: 1,
          // date: null,
          // dateTime: null,
          // range: null
        },
        fieldList: [
          { label: '名称', type: 'input', value: 'title' },
          { label: '年龄', type: 'input', value: 'age', disabled: true },
          {
            label: '计数',
            type: 'inputNumber',
            value: 'count',
            min: 1,
            max: 10
          }
          // // { label: '性别', type: 'select', value: 'sex', list: 'sexList' },
          // { label: '日期', type: 'date', value: 'date' },
          // { label: '创建时间', type: 'date', value: 'dateTime', dateType: 'datetime', clearable: true },
          // { label: '时间区间', type: 'date', value: 'range', dateType: 'daterange' }
        ]
      },
      // listTypeInfo: {
      //   sexList: [{ id: 1, name: '男' }, { id: 2, name: '女' }, { id: 3, name: '保密' }]
      // },
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
          images: {
            label: '图片',
            type: 'image-uploader' // 只需要在这里指定为 image-uploader 即可
          },
          title: {
            type: 'input',
            label: '标题'
          },
          description: {
            type: 'textarea',
            label: '描述'
          },
          menuname: {
            type: 'input',
            label: '按钮名称'
          },
          menuurl: {
            type: 'input',
            label: '按钮链接地址'
          }
        },
        order: ['images', 'title', 'description', 'menuname', 'menuurl']
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
        update: '编辑幻灯片',
        create: '添加幻灯片'
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
    // 触发请求
    async resetForm() {
      this.$refs.form.resetForm()
    },
    handleSelectionChange(val) {
      console.log('传递来的', val)
    },
    // 更新，和创建
    handleRequest(data) {
      console.log(data)
      const that = this

      if (that.dialogStatus === 'create') {
        createad(data).then((response) => {
          if (response.code === 200) {
            console.log(response)
            that.getList()
            that.dialogFormVisible = false
            that.$message.success(response.message)
          }
        })
      } else if (that.dialogStatus === 'update') {
        updatead(data).then((res) => {
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
      deletead(row).then((res) => {
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
        that.total = response.data.dataProvider.total
        that.list = response.data.dataProvider.allModels
        console.log(that.list)
        // Just to simulate the time of the request
        setTimeout(() => {
          that.listLoading = false
        }, 1.5 * 1000)
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
      that.dialogStatus = 'create'
      that.dialogFormVisible = true
    },
    handleDeleteAll() {
      console.log('删除')
    },
    createData() {},
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
      that.formData = row
      that.dialogFormVisible = true
      console.log(row)
      this.dialogStatus = 'update'
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
