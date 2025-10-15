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
      :total="0"
      :list-loading="listLoading"
      :list-query="filterInfo.data"
      :columns="tableColumns"
      :handle="tableHandle"
      @handleSelectionChange="handleSelectionChange"
    >
      <template slot="type" slot-scope="{ row }">
        <div style="color: #4043a8">
          {{ row.is_sys === 1 ? "系统" : "模块" }}
        </div>
      </template>
      <template slot="permission_type" slot-scope="{ row }">
        <div style="color: #4043a8">
          {{ permission_typeslist[row.permission_level] }}
        </div>
      </template>
    </fire-table>
    <!-- 数据列表 end -->

    <!-- 提交表单 start -->
    <ele-form-dialog
      ref="form"
      v-model="formData"
      v-bind="formConfig"
      :request-fn="handleRequest"
      :rules="rules"
      :title="textMap[dialogStatus]"
      :visible.sync="dialogFormVisible"
      @request-success="handleSuccess"
    />

    <!-- <el-dialog  :close-on-click-modal="false"
 width="80%" :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <ele-form ref="form" v-model="formData" v-bind="formConfig" :request-fn="handleRequest"   :isShowBackBtn="false"
 @request-success="handleRequestSuccess" />
    </el-dialog> -->
    <!-- 提交表单 end -->

    <!-- 授权表单 start -->
    <el-dialog
      :close-on-click-modal="false"
      width="80%"
      :title="permissionTitle"
      :visible.sync="dialogAuthVisible"
    >
      <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="权限" name="permission">
          <div style="text-align: center">
            <el-input
              v-model="filterTextPermission"
              style="margin-bottom: 15px"
              placeholder="输入关键字进行过滤"
            />

            <el-tree
              ref="permission"
              class="auth-permission"
              :data="DataPermission"
              show-checkbox
              :default-checked-keys="ValuePermission"
              node-key="id"
              highlight-current
              :filter-node-method="filterNode"
              :props="defaultProps"
              @current-change="currentChange"
            />
          </div>
        </el-tab-pane>
        <el-tab-pane label="页面" name="route">
          <div style="text-align: center">
            <el-input
              v-model="filterTextRoute"
              style="margin-bottom: 15px"
              placeholder="输入关键字进行过滤"
            />

            <el-tree
              ref="route"
              class="auth-permission"
              :data="DataRoute"
              show-checkbox
              :default-checked-keys="ValueRoute"
              node-key="item_id"
              highlight-current
              :filter-node-method="filterNode"
              :props="defaultProps"
              @current-change="currentChange"
            />
          </div>
        </el-tab-pane>
        <el-tab-pane label="按钮" name="menu">
          <div style="text-align: center">
            <el-input
              v-model="filterTextMenu"
              style="margin-bottom: 15px"
              placeholder="输入关键字进行过滤"
            />

            <el-tree
              ref="menu"
              class="auth-permission"
              :data="DataMenu"
              show-checkbox
              :default-checked-keys="ValueRoute"
              node-key="item_id"
              highlight-current
              :filter-node-method="filterNode"
              :props="defaultProps"
              @current-change="currentChange"
            />
          </div>
        </el-tab-pane>
        <el-tab-pane label="接口" name="api">
          <div style="text-align: center">
            <el-input
              v-model="filterTextApi"
              style="margin-bottom: 15px"
              placeholder="输入关键字进行过滤"
            />

            <el-tree
              ref="api"
              class="auth-permission"
              :data="DataApi"
              show-checkbox
              :default-checked-keys="ValueRoute"
              node-key="item_id"
              highlight-current
              :filter-node-method="filterNode"
              :props="defaultProps"
              @current-change="currentChange"
            />
          </div>
        </el-tab-pane>
        <el-tab-pane label="规则" name="role">规则</el-tab-pane>
      </el-tabs>
      <span slot="footer" class="dialog-footer">
        <el-button type="default" @click="removeSelectAll">全不选</el-button>
        <el-button type="primary" @click="selectAll">全选</el-button>
        <el-button @click="dialogAuthVisible = false">取 消</el-button>
        <el-button type="primary" @click="handleChange">确 定</el-button>
      </span>
    </el-dialog>
    <!-- 授权表单 end -->
  </div>
</template>

<script>
import {
  fetchList,
  fetchChange,
  createPermission,
  updatePermission,
  deletePermission,
  getRules,
  fetchLevels,
  fetchAddons,
  fetchView
} from '@projectName/views/system/api/admin/permission.js'
// import waves from '@projectName/directive/waves' // waves directive
import { parseTime } from '@projectName/utils'
export default {
  data() {
    return {
      permission_typeslist: ['目录', '页面', '按钮', '接口'],
      module_name: 'system',
      rules: {},
      // 权限操作行数据
      rowItem: {},
      defaultProps: {
        children: 'children',
        label: 'name'
      },
      filterTextPermission: '',
      filterTextRole: '',
      filterTextRoute: '',
      filterTextMenu: '',
      filterTextApi: '',
      // 表格数据 start
      tableColumns: [
        { label: '名称', prop: 'name', width: 330 },
        {
          label: '插件',
          prop: 'addons'
        },
        {
          label: '权限种类',
          prop: 'type',
          width: 250,
          slot: 'type',
          align: 'center'
        },
        {
          label: '权限类型',
          prop: 'permission_type',
          width: 250,
          slot: 'permission_type',
          align: 'center'
        },
        // {
        //   label: "权限种类",
        //   prop: "type",
        //   width: 450,
        //   align: "center",
        //   render: (h, { row }) => {
        //     return h(
        //       "el-tag",
        //       {
        //         attrs: {
        //           type: row.type === 0 ? "系统" : "模块",
        //           size: "small",
        //         },
        //       },
        //       row.type === 0 ? "系统" : row.addons
        //     );
        //   },
        // },
        //   {
        //     label: "权限类型",
        //     prop: "permission_type",
        //     width: 100,
        //     align: "center",
        //     render: (h, { row }) => {
        //       const permission_types = ["目录", "页面", "按钮", "接口"];
        //       return h("el-tag", {}, permission_types[row.permission_type]);
        //     },
        //   },
        { label: '规则名称', prop: 'rule_name', align: 'center' },
        { label: '描述', prop: 'description', align: 'center' }
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
        },
        {
          label: '权限',
          type: 'warning',
          isPop: false,
          method: (row) => {
            console.log('setAuthItem')
            this.setAuthItem(row)
          }
        }
      ],
      // 表格数据end
      // 检索 start
      filterInfo: {
        data: {
          page: 1,
          pageSize: 20,
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
            label: '模块类型',
            type: 'select',
            value: 'AuthItemSearch[module_name]',
            list: 'addonsList'
          },
          { label: '权限名称', type: 'input', value: 'AuthItemSearch[name]' },
          {
            label: '父级名称',
            type: 'input',
            value: 'AuthItemSearch[parent_id]',
            disabled: true
          },
          {
            label: '权限描述',
            type: 'input',
            value: 'AuthItemSearch[description]',
            min: 1,
            max: 10
          }
        ]
      },
      listTypeInfo: {
        addonsList: this.initListTypeInfo()
      },
      // 检索 end
      tableKey: 0,
      formInline: {
        user: '',
        region: ''
      },
      // 表单数据 start
      formData: {
        permission_type: 1,
        permission_level: 0,
        is_sys: 1,
        rule_name: 0,
        parent_id: 0,
        module_name: 'system'
      },
      formConfig: {
        formDesc: {
          permission_level: {
            type: 'radio',
            label: '权限类型',
            options: [
              { text: '目录', value: 0 },
              { text: '页面', value: 1 },
              { text: '按钮', value: 2 },
              { text: '接口', value: 3 }
            ]
          },
          name: {
            type: 'input',
            label: '名称'
          },
          is_sys: {
            type: 'radio',
            label: '权限所属',
            isOptions: true,
            options: [
              { text: '插件', value: 0 },
              { text: '系统', value: 1 }
            ]
          },
          module_name: {
            type: 'select',
            label: '模块名称',
            isOptions: true,
            // 这里必须制定 optionsLinkageFields 做为关联字段，当次字段值发生变化时，会重新出发请求
            optionsLinkageFields: ['is_sys'],
            options: this.initAddons()
          },
          rule_name: {
            type: 'select',
            label: '规则名称',
            isOptions: true,
            attrs: {
              placeholder: '请选择规则'
            },
            options: getRules().then((res) => {
              res.data.unshift({
                text: '请选择规则',
                value: 0
              })
              return res.data
            })
          },
          parent_id: {
            type: 'tree-select',
            label: '父级权限',
            isOptions: true,
            attrs: {
              placeholder: '请选择父级权限',
              showAllLevels: false
            },
            optionsLinkageFields: ['is_sys'],
            options: async(data) => {
              console.log(data.is_sys)
              const arr = [{ id: 0, label: '选择父级权限' }]
              const res = await fetchLevels({ is_sys: data.is_sys })
              console.log('选择父级权限', arr.concat(res.data))
              return arr.concat(res.data)
            }
          },
          // routes: {
          //   type: 'tree-select',
          //   label: '菜单路由',
          //   isOptions: true,
          //   options: fetchRoute().then(res => {
          //     return res.data;
          //   }),
          //   attrs: {
          //     multiple: true,
          //     clearable: true
          //   }
          // },
          description: {
            type: 'textarea',
            label: '描述',
            attrs: {
              autosizeType: 'switch',
              autosize: false
            }
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
        order: [
          'permission_level',
          'is_sys',
          'module_name',
          'name',
          'rule_name',
          'parent_id',
          'description',
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
        update: '编辑权限',
        create: '添加权限'
      },
      dialogPvVisible: false,
      pvData: [],
      downloadLoading: false,
      // 权限项管理
      permissionId: 0,
      permission_types: {
        route: 0,
        permission: 1,
        role: 2
      },
      activeName: 'route', // 0:route,1:permission,2:role
      dialogAuthVisible: false,
      permissionValue: {
        route: [],
        permission: [],
        role: []
      },
      permissionData: {
        route: [],
        menu: [],
        api: [],
        permission: [],
        role: []
      },
      permissionTitle: '',
      ValueRoute: [],
      DataRoute: [],
      DataMenu: [],
      DataApi: [],
      ValuePermission: [],
      DataPermission: [],
      ValueRole: [],
      DataRole: []
    }
  },
  watch: {
    permissionData: {
      handler(newVal, oldVal) {
        if (newVal) {
          this.DataRoute = newVal.route || []
          this.DataMenu = newVal.menu || []
          this.DataApi = newVal.api || []
          this.DataPermission = newVal.permission || []
          this.DataRole = newVal.role || []
          console.log('newVal-data', newVal, this.DataPermission)
        }
      },
      immediate: true
    },
    permissionValue: {
      handler(newVal, oldVal) {
        if (newVal) {
          this.ValueRoute = newVal.route || []
          this.ValuePermission = newVal.permission || []
          this.ValueRole = newVal.role || []
          console.log('newVal-value', this.ValuePermission)
        }
      },
      immediate: true
    },
    filterTextRole(val) {
      this.$refs.role.filter(val)
    },
    filterTextRoute(val) {
      this.$refs.route.filter(val)
    },
    filterTextMenu(val) {
      this.$refs.menu.filter(val)
    },
    filterTextApi(val) {
      this.$refs.api.filter(val)
    },
    filterTextPermission(val) {
      this.$refs.permission.filter(val)
    }
  },
  created() {
    this.getList()
  },
  methods: {
    filterNode(value, data) {
      if (!value) return true
      return data.name.indexOf(value) !== -1
    },
    currentChange(res, res1, res2) {
      console.log(res, res1, res2)
    },
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
        console.log('全新', arr)
      })
      console.log('全新', arr)
      return arr
    },
    handleSuccess() {},
    selectAll() {
      const arr = []
      this.permissionData[this.activeName].forEach((item, index) => {
        arr.push(item.item_id)
      })
      this.$refs[this.activeName].setCheckedKeys(arr)
    },
    removeSelectAll() {
      this.$refs[this.activeName].setCheckedKeys([])
    },
    handleChange() {
      const that = this
      let arr = []
      let activeName = that.activeName
      if (that.activeName === 'permission') {
        arr = that.$refs.permission.getCheckedKeys()
      } else if (that.activeName === 'route') {
        arr = that.$refs.route.getCheckedKeys()
      } else if (that.activeName === 'menu') {
        activeName = 'route'
        arr = that.$refs.menu.getCheckedKeys()
      } else if (that.activeName === 'api') {
        activeName = 'route'

        arr = that.$refs.api.getCheckedKeys()
      } else if (that.activeName === 'role') {
        arr = that.$refs.role.getCheckedKeys()
      }
      const ob = {}

      that.$set(ob, activeName, arr)

      fetchChange({
        id: that.permissionId,
        items: ob
      }).then((res) => {
        console.log(res)
        if (res.code === 200) {
          this.$message({
            message: res.message,
            type: 'success'
          })

          that.authItem(that.permissionId)
        }
      })
    },
    initListTypeInfo() {
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
      await this.$refs['form'].resetForm()
    },
    handleSelectionChange(val) {
      console.log('传递来的', val)
    },
    // 更新，和创建
    handleRequest(data) {
      const that = this

      if (that.dialogStatus === 'create') {
        if (data.type === 0) {
          data.module_name = 'system'
        }

        createPermission(data).then((response) => {
          console.log(response)
          that.getList()
          that.dialogFormVisible = false
        })
      } else if (that.dialogStatus === 'update') {
        updatePermission(data).then((res) => {
          that.getList()
          that.dialogFormVisible = false
        })
      }

      return Promise.resolve()
    },
    // 单行数据删除
    deleteItem(row) {
      const that = this
      deletePermission(row.id).then((res) => {
        that.getList()
        that.dialogFormVisible = false
      })
    },
    handleClick(tab, event) {
      const that = this
      that.activeName = tab.name
    },
    setAuthItem(row) {
      const that = this
      that.permissionId = row.id
      that.permissionTitle = row.name
      that.module_name = row.module_name

      this.authItem(row.id)
    },
    authItem(id) {
      const that = this
      that.dialogAuthVisible = true
      fetchView(id, {
        permission_type: that.permission_types[that.activeName],
        module_name: that.module_name
      }).then((res) => {
        that.permissionData = res.data.all
        that.permissionValue = res.data.assignedKey
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
        that.list = response.data.list
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
      that.dialogStatus = 'create'
      that.dialogFormVisible = true
      console.log('显示表格')
      this.resetForm()
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
.auth-permission {
  height: 300px;
  overflow-y: auto;
}
.el-transfer-panel {
  width: 300px;
}
::v-deep .el-dialog {
  border-radius: 4px !important;
  font-weight: bold;
}
::v-deep .el-form-item--medium .el-form-item__content {
  text-align: initial;
}
</style>
