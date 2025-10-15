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
      @handleSelectionChange="handleSelectionChange"
    >
      <template slot="routeItemName" slot-scope="{ row }">
        <span
          v-clipboard:copy="row.routeItem.route_name"
          v-clipboard:success="onCopy"
          style="cursor: pointer;"
        >{{ row.routeItem.route_name }}</span>
      </template>

      <template slot="" />
    </fire-table>
    <!-- 数据列表 end -->

    <el-dialog
      :close-on-click-modal="false"
      style="text-align:center;font-weight:bold;border-radius:17px"
      width="60%"
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
  createMenu,
  updateMenu,
  deleteMenu,
  fetchAvailable,
  fetchView,
  fetchRoute
} from '@projectName/views/system/api/admin/menu.js'
import { fetchAddons } from '@projectName/views/system/api/admin/permission.js'
// import waves from '@projectName/directive/waves' // waves directive
import { parseTime } from '@projectName/utils'

export default {
  data() {
    return {
      // 表格数据 start
      tableColumns: [
        {
          label: '名称',
          prop: 'name',
          width: 230
        },
        {
          label: '菜单地址',
          prop: 'route'
        },
        {
          label: '路由名称(点击复制)',
          prop: 'routeItem.route_name',
          slot: 'routeItemName'
        },
        {
          label: '菜单排序',
          prop: 'order'
        },
        {
          label: '是否显示',
          prop: 'is_show',
          render: (h, { row }) => {
            return h(
              'el-tag',
              row.is_show === 0 ? '显示' : '隐藏'
            )
          }
        },
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
                  type: row.is_sys === 'system' ? '系统' : '模块',
                  size: 'small'
                }
              },
              row.is_sys === 'system' ? '系统' : '模块'
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
          {
            label: '菜单名称',
            type: 'input',
            value: 'Menu[name]'
          },
          {
            label: '页面路径',
            type: 'input',
            value: 'Menu[route]',
            disabled: true
          },
          {
            label: '模块类型',
            type: 'select',
            value: 'Menu[module_name]',
            list: 'addonsList'
          },
          {
            label: '菜单描述',
            type: 'input',
            value: 'Menu[description]',
            min: 1,
            max: 10
          }
        ]
      },
      listTypeInfo: {
        addonsList: this.initAddonsFitter()
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
            label: '菜单名称'
          },
          module_name: {
            type: 'select',
            label: '模块名称',
            isOptions: true,
            options: this.initAddons()
          },
          is_show: {
            type: 'radio',
            label: '是否隐藏',
            isOptions: true,
            options: [
              {
                text: '不隐藏',
                value: 0
              },
              {
                text: '隐藏',
                value: 1
              }
            ],
            default: 0
          },
          parent: {
            label: '父级菜单',
            // 只需要在这里指定为 tree-select 即可
            type: 'tree-select',
            // 属性参考: https://vue-treeselect.js.org/
            attrs: {
              multiple: false,
              clearable: true
            },
            vif: (data) => data.module_name,
            // 这里必须制定 optionsLinkageFields 做为关联字段，当次字段值发生变化时，会重新出发请求
            optionsLinkageFields: ['module_name', 'name'],
            options: async(data) => {
              if (data.name || data.module_name) {
                const obj = {}
                this.$set(obj, 'Menu[module_name]', data.module_name)
                const res = await fetchList(obj)
                const arr = [
                  {
                    id: 0,
                    label: '一级菜单'
                  }
                ]
                return arr.concat(res.data.list)
              }
            }
          },
          level_type: {
            type: 'radio',
            label: '菜单等级类型',
            isOptions: true,
            options: this.initMenType()
          },
          route_id: {
            type: 'select',
            label: '页面路径',
            isOptions: true,
            tip: '当菜单为二级且有子菜单时，请选择/main/index',
            vif: (data) => data.module_name,
            // 这里必须制定 optionsLinkageFields 做为关联字段，当字段值发生变化时，会重新出发请求
            optionsLinkageFields: ['module_name', 'name', 'level_type'],
            options: async(data) => {
              const arr = [
                {
                  text: '选择菜单地址',
                  value: ''
                }
              ]
              if (data.name || data.module_name || data.level_type) {
                const list = await fetchRoute({
                  module_name: data.level_type !== 3 ? data.module_name : 'system',
                  route_type: [0, 1, 2]
                })
                list.data.forEach((item, index) => {
                  if (item.label.indexOf('*') === -1) {
                    arr.push({
                      text: item.label,
                      value: item.id
                    })
                  }
                })

                if (data.level_type === 3) {
                  return arr.filter((itm) => itm.text === '/main/index.vue')
                } else {
                  return arr
                }
              }
            },
            on: {
              change(val) {
                console.log(val)
              }
            },
            attrs: {
              filterable: true
            }
          },
          icon: {
            type: 'FireIcon',
            label: '菜单图标'
          },
          order: {
            type: 'input',
            label: '菜单排序'
          },
          data: {
            type: 'textarea',
            label: '菜单数据',
            attrs: {
              autosizeType: 'switch',
              autosize: false
            }
          }
        },
        order: [
          'level_type',
          'name',
          'module_name',
          'route_id',
          'icon',
          'order',
          'parent',
          'data'
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
        update: '编辑菜单',
        create: '添加菜单'
      },
      dialogPvVisible: false,
      pvData: [],
      downloadLoading: false
    }
  },
  watch: {
    dialogPvVisible() {
      // this.$forceUpdate()
    }
  },
  created() {
    this.getList()
  },
  methods: {
    initAddons() {
      const arr = []
      arr.push({
        text: '选择模块',
        value: ''
      })
      fetchAddons().then((res) => {
        const keys = Object.keys(res.data)
        const values = Object.values(res.data)
        values.forEach((item, index) => {
          arr.push({
            text: item,
            value: keys[index]
          })
        })
      })
      return arr
    },
    onCopy() {
      this.$message.success('复制成功')
    },
    initMenType() {
      return [
        {
          text: '一级菜单',
          value: 1
        },
        {
          text: '一级菜单(无二级菜单)',
          value: 2
        },
        {
          text: '二级菜单',
          value: 3
        },
        {
          text: '二级菜单(无三级菜单)',
          value: 4
        },
        {
          text: '三级菜单',
          value: 5
        },
        {
          text: '非菜单页面',
          value: 6
        }
      ]
    },
    initAddonsFitter() {
      const arr = []
      arr.push({
        id: '选择模块',
        name: ''
      })
      fetchAddons().then((res) => {
        const keys = Object.keys(res.data)
        const values = Object.values(res.data)
        values.forEach((item, index) => {
          arr.push({
            id: keys[index],
            name: item
          })
        })
      })

      return arr
    },
    // 触发请求
    async resetForm() {
      await this.$refs.form.resetForm()
    },
    handleSelectionChange(val) {
      console.log('传递来的', val)
    },
    // 更新，和创建
    handleRequest(data) {
      const that = this
      console.log('handleRequest', data)
      if (that.dialogStatus === 'create') {
        createMenu(data).then((response) => {
          that.getList()
          that.dialogFormVisible = false
        })
      } else if (that.dialogStatus === 'update') {
        that.$delete(data, 'children')
        updateMenu(data).then((res) => {
          that.getList()
          that.dialogFormVisible = false
        })
      }

      return Promise.resolve()
    },
    // 单行数据删除
    deleteItem(row) {
      const that = this
      deleteMenu(row.id).then((res) => {
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
        that.total = response.data.dataProvider.total
        that.list = response.data.list
        that.listLoading = false
      })
    },
    // 获取系统新的菜单
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
    handleUpdate(row) {
      this.temp = Object.assign({}, row) // copy obj
      this.temp.timestamp = new Date(this.temp.timestamp)
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
    },
    updateData() { },
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
      that.formData = {
        ...row
      }
      that.dialogFormVisible = true
      this.dialogStatus = 'update'
      fetchView(row.id).then((res) => {
        console.log('view', res)
      })
    },
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then((excel) => {
        const tHeader = ['title', 'create_time']
        const filterVal = [
          {
            title: '12',
            create_time: '23435345353'
          }
        ]
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
<style lang="scss" scoped>
::v-deep .el-dialog {
  border-radius: 4px !important;
}

::v-deep .el-form-item--medium .el-form-item__content {
  text-align: initial;
}
</style>
