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
      row-key="bloc_id"
      :list-loading="listLoading"
      :list-query="filterInfo.data"
      :columns="tableColumns"
      :handle="tableHandle"
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
import { mapGetters } from 'vuex'
import {
  fetchList,
  createItem,
  updateItem,
  deleteItem,
  fetchView
} from '@projectName/views/system/api/addons/storelabel.js'
// import waves from '@projectName/directive/waves' // waves directive
import { parseTime } from '@projectName/utils'
import { getToken } from '@projectName/utils/auth'
export default {
  data() {
    return {
      // 表格数据 start
      tableColumns: [
        { label: '标签名称', prop: 'name',
          width: 100 },
        { label: '排序', prop: 'displayorder' },
        {
          label: '标签图片',
          prop: 'thumb',
          align: 'center',
          width: 120,
          render: (h, { row }) => {
            return h('el-image', { attrs: { src: row.thumb }}, row.thumb)
          }
        },
        {
          label: '是否显示',
          prop: 'is_show',
          width: 110,
          align: 'center',
          render: (h, { row }) => {
            return h('el-tag', {}, row.is_show === 1 ? '显示' : '隐藏')
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
          limit: 20,
          name: '',
          parent_id: '',
          description: ''
          // sex: 1,
          // date: null,
          // dateTime: null,
          // range: null
        },
        fieldList: [
          { label: '标签名称', type: 'input', value: 'StoreLabelSearch[name]' }
        ]
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
            label: '标签名称'
          },
          displayorder: {
            type: 'number',
            label: '排序'
          },
          is_show: {
            type: 'radio',
            label: '是否显示',
            options: async(data) => {
              return [
                {
                  text: '不显示',
                  value: 0
                },
                {
                  text: '显示',
                  value: 1
                }
              ]
            },
            default: 0
          },
          thumb: {
            label: '标签图片',
            type: 'image-uploader' // 只需要在这里指定为 image-uploader 即可
          }
        },
        order: ['name', 'thumb', 'displayorder', 'is_show']
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
        update: '编辑商户分类',
        create: '添加商户分类'
      },
      dialogPvVisible: false,
      pvData: [],
      downloadLoading: false
    }
  },
  computed: {
    ...mapGetters(['accessToken'])
  },
  created() {
    this.getList()
  },
  methods: {
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
          console.log(response)
          that.getList()
          that.dialogFormVisible = false
        })
      } else if (that.dialogStatus === 'update') {
        updateItem(data).then((res) => {
          console.log('更新', res)
          that.getList()
          that.dialogFormVisible = false
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
    handleRequestSuccess() {
      this.$message.success('发送成功')
    },
    getList() {
      const that = this
      that.listLoading = true
      fetchList(that.filterInfo.data).then((response) => {
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
    editItem(row) {
      const that = this
      that.dialogFormVisible = true
      console.log('formData', row, that.formData)
      this.dialogStatus = 'update'

      fetchView(row.id).then((res) => {
        console.log('view', res)
        that.formData = { ...res.data }
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
<style scoped>
</style>
