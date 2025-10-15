<template>
  <div class="app-container">
    <el-row :gutter="10">
      <el-col :xs="24" :sm="6" :md="6" :lg="6" :xl="6" justify="start">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span>业务中心</span>
            <span class="fr text-small" @click="clearSearch">系统用户组</span>
          </div>
          <div>
            <el-tree
              :data="listtree"
              :props="defaultProps"
              @node-click="handleNodeClick"
            />
          </div>
        </el-card>
      </el-col>
      <el-col :xs="24" :sm="18" :md="18" :lg="18" :xl="18">
        <div class="top-search" :class="{'margin-top':device === 'mobile'}">
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
      </el-col>
    </el-row>

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

    <!-- 授权表单 start -->
    <el-dialog
      :close-on-click-modal="false"
      width="80%"
      :title="permissionTitle"
      :visible.sync="dialogAuthVisible"
    >
      <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="用户组" name="role">
          <div style="text-align: center">
            <el-input
              v-model="filterTextRole"
              style="margin-bottom: 15px"
              placeholder="输入关键字进行过滤"
            />

            <el-tree
              ref="role"
              :data="permissionData[activeName]"
              show-checkbox
              :default-checked-keys="permissionValue[activeName]"
              node-key="item_id"
              highlight-current
              :filter-node-method="filterNode"
              :props="defaultProps"
            />
          </div>
        </el-tab-pane>
        <el-tab-pane label="权限" name="permission">
          <div style="text-align: center">
            <el-input
              v-model="filterTextPermission"
              style="margin-bottom: 15px"
              placeholder="输入关键字进行过滤"
            />

            <el-tree
              ref="permission"
              :data="permissionData[activeName]"
              show-checkbox
              :default-checked-keys="permissionValue[activeName]"
              node-key="item_id"
              highlight-current
              :filter-node-method="filterNode"
              :props="defaultProps"
              @current-change="currentChange"
            />
          </div>
        </el-tab-pane>
        <el-tab-pane label="路由" name="route">
          <div style="text-align: center">
            <el-input
              v-model="filterTextRoute"
              style="margin-bottom: 15px"
              placeholder="输入关键字进行过滤"
            />

            <el-tree
              ref="route"
              :data="permissionData[activeName]"
              show-checkbox
              :default-checked-keys="permissionValue[activeName]"
              node-key="item_id"
              highlight-current
              :filter-node-method="filterNode"
              :props="defaultProps"
              @current-change="currentChange"
            />
          </div>
        </el-tab-pane>
      </el-tabs>

      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogAuthVisible = false">取 消</el-button>
        <el-button type="primary" @click="submitAuth">确 定</el-button>
      </span>
    </el-dialog>
    <!-- 授权表单 end -->
  </div>
</template>

<script>
import {
  fetchList,
  createGroup,
  updateGroup,
  deleteGroup,
  fetchView,
  fetchAvailable,
  fetchChange
} from '@projectName/views/system/api/admin/group.js'
// import waves from '@projectName/directive/waves' // waves directive
import { parseTime } from '@projectName/utils'
import { getStoreGroup, getStorelist, getBlocStore as getBlocStoreList } from '@projectName/views/system/api/addons/bloc.js'
import { mapState } from 'vuex'

export default {
  data() {
    return {
      // 公司与商户
      listtree: [],
      // 表格数据 start
      tableColumns: [
        {
          label: 'ID',
          prop: 'id'
        },
        {
          label: '名称',
          prop: 'name'
        },
        {
          label: '用户组说明',
          prop: 'description'
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
        },
        {
          label: '权限项',
          type: 'warning',
          isPop: false,
          method: (row) => {
            this.authItem(row)
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
          {
            label: '用户组名称',
            type: 'input',
            value: 'UserGroupSearch[name]'
          }
        ]
      },
      listTypeInfo: {
      },
      // 检索 end
      tableKey: 0,
      formInline: {
        user: '',
        region: ''
      },
      // 表单数据 start
      formData: {
        is_sys: 1,
        is_default: 0,
        store_id: 0
      },
      formConfig: {
        formDesc: {
          name: {
            type: 'input',
            label: '用户组名称'
          },
          is_sys: {
            type: 'radio',
            label: '是否集团权限',
            isOptions: true,
            options: [
              {
                text: '集团权限',
                value: 1
              },
              {
                text: '业务中心权限',
                value: 0
              }
            ],
            default: 1
          },
          store_id: {
            type: 'select',
            label: '选择业务中心',
            isOptions: true,
            vif(data) {
              return data.is_sys === 0
            },
            options: getStorelist().then((res) => {
              const arr = [
                {
                  text: '选择商户',
                  value: 0
                }
              ]
              res.data.forEach((item, index) => {
                arr.push({
                  text: item.name,
                  value: item.store_id
                })
              })
              return arr
            }),
            attrs: {
              filterable: true
            }
          },
          is_default: {
            type: 'radio',
            label: '是否默认',
            isOptions: true,
            options: [
              {
                text: '是',
                value: 1
              },
              {
                text: '否',
                value: 0
              }
            ]
          },
          description: {
            type: 'textarea',
            label: '用户组说明',
            attrs: {
              autosizeType: 'switch',
              autosize: false
            }
          }
        },
        order: ['is_sys', 'store_id', 'name', 'description', 'is_default']
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
        update: '编辑用户组',
        create: '添加用户组'
      },
      dialogPvVisible: false,
      pvData: [],
      downloadLoading: false,
      // 权限项管理
      permissionTitle: '角色授权',
      permissionId: 0,
      activeName: 'role', // 0:route,1:permission,2:role
      dialogAuthVisible: false,
      permissionValue: {
        route: [],
        permission: [],
        role: []
      },
      permissionData: {
        route: [],
        permission: [],
        role: []
      },
      treeData: [],
      defaultProps: {
        children: 'children',
        label: 'label'
      },
      filterText: '',
      filterTextPermission: '',
      filterTextRole: '',
      filterTextRoute: ''
    }
  },
  computed: {
    ...mapState({
      device: state => state.app.device
    })
  },
  watch: {
    filterText(val) {
      this.$refs.tree.filter(val)
    },
    filterTextRole(val) {
      this.$refs.role.filter(val)
    },
    filterTextRoute(val) {
      this.$refs.route.filter(val)
    },
    filterTextPermission(val) {
      this.$refs.permission.filter(val)
    }
  },
  created() {
    this.getList()
    this.getBlocStoreS()
  },
  methods: {
    getBlocStoreS() {
      getBlocStoreList().then((res) => {
        console.log('getBlocStore', res)
        this.listtree = res.data
      })
    },
    handleNodeClick(val) {
      const that = this
      console.log('val', val)
      const data = {
        'UserGroupSearch[bloc_id]': val.bloc_id,
        'UserGroupSearch[store_id]': val.store_id
      }
      Object.assign(that.filterInfo.data, data)
      that.getList()
    },
    clearSearch() {
      const that = this
      that.$delete(that.filterInfo.data, 'UserGroupSearch[bloc_id]')
      that.$delete(that.filterInfo.data, 'UserGroupSearch[store_id]')
      that.getList()
    },
    filterNode(value, data) {
      if (!value) return true
      return data.label.indexOf(value) !== -1
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
        createGroup(data).then((response) => {
          if (response.code === 200) {
            console.log(response)
            that.getList()
            that.dialogFormVisible = false
            this.$message.success(response.message)
          }
        })
      } else if (that.dialogStatus === 'update') {
        updateGroup(data).then((res) => {
          if (res.code === 200) {
            console.log('更新', res)
            that.getList()
            that.dialogFormVisible = false
            this.$message.success(res.message)
          }
        })
      }

      return Promise.resolve()
    },
    // 单行数据删除
    deleteItem(row) {
      const that = this
      deleteGroup(row.id).then((res) => {
        console.log('更新', res)
        that.getList()
        that.dialogFormVisible = false
      })
    },
    authItem(row) {
      const that = this
      that.permissionId = row.id
      that.permissionTitle = row.name
      this.dialogAuthVisible = true
      fetchView(row.id).then((res) => {
        console.log('assigneds', res.data.assignedKey)
        that.permissionData = res.data.all
        that.permissionValue = res.data.assignedKey
      })
    },
    handleClick(tab, event) {
      const that = this
      console.log(tab, that.activeName)
      // console.log('permissionData', that.permissionData, that.activeName)
      // console.log('handleClick', tab, event, that.activeName, that.permissionData[that.activeName])
      // that.activeName = tab.name
    },
    handleRequestSuccess() {
    },
    getList(res) {
      console.log('239', res)
      const that = this
      that.listLoading = true
      fetchList(that.filterInfo.data).then((response) => {
        console.log('response', response)
        that.total = response.data.dataProvider.total
        that.list = response.data.dataProvider.allModels
        console.log('列表数据层级测试', that.list)
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
    currentChange(res, res1, res2) {
      console.log(res, res1, res2)
    },
    submitAuth() {
      const that = this
      console.log('提交授权ALL数据', that.activeName)
      console.log('提交授权权限数据', that.$refs.permission.getCheckedNodes())
      console.log('提交授权路由数据', that.$refs.route.getCheckedNodes())
      console.log('提交授权用户组数据', that.$refs.role.getCheckedNodes())
      let arr = []
      if (that.activeName === 'permission') {
        arr = that.$refs.permission.getCheckedKeys()
      } else if (that.activeName === 'route') {
        arr = that.$refs.route.getCheckedKeys()
      } else if (that.activeName === 'role') {
        arr = that.$refs.role.getCheckedKeys()
      }
      const ob = {}
      that.$set(ob, that.activeName, arr)
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

          that.authItem({
            id: that.permissionId,
            name: that.permissionTitle
          })
        }
      })
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
      that.formData = {
        ...row
      }
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
    },
    // 授权
    getCheckedNodes() {
      console.log(this.$refs.tree.getCheckedNodes())
    },
    getCheckedKeys() {
      console.log(this.$refs.tree.getCheckedKeys())
    },
    getStoreLists() {
      getStoreGroup().then((res) => {
        if (res.code === 200) {
          this.listtree = res.data.list
        }
      })
    },
    setCheckedNodes() {
      this.$refs.tree.setCheckedNodes([
        {
          id: 5,
          label: '二级 2-1'
        },
        {
          id: 9,
          label: '三级 1-1-1'
        }
      ])
    },
    setCheckedKeys() {
      this.$refs.tree.setCheckedKeys([3])
    },
    resetChecked() {
      this.$refs.tree.setCheckedKeys([])
    }
  }
}
</script>
<style>
.el-transfer-panel {
  width: 300px;
}

.el-tree {
  height: 400px;
  overflow: hidden;
  overflow-y: auto;
}

/* 弹出框滚动条 */
/* 设置滚动条的样式 */
/**解决了滚动条之间发生错位的现象 */
::-webkit-scrollbar {
  width: 10px !important;
  height: 10px !important;
  border-radius: 5px;
}

::-webkit-scrollbar-thumb {
  border-radius: 5px;
  box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.2);
  /* 滚动条的颜色 */
  background-color: #e4e4e4;
}
.box-card {
  height: 100% !important;
}
.usertitle {
  padding-bottom: 21px;
  margin-bottom: 29px;
  border-bottom: 1px solid rgb(112, 112, 112, 0.2);
}
</style>
