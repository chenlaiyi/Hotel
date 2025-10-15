<template>
  <div class="app-container">
    <el-row :gutter="10">
      <el-col :xs="24" :sm="6" :md="6" :lg="6" :xl="6" justify="start">
        <el-card class="card">
          <div slot="header" class="clearfix">
            <span>公司与商户</span>
            <span class="fr text-small" @click="clearSearch">系统管理员</span>
          </div>
          <div>
            <el-tree
              :data="storeList"
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
            <!-- <div slot="header" class="clearfix">
                <span>管理员</span>
              </div> -->
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
          :show-index="false"
          :list-loading="listLoading"
          :list-query="filterInfo.data"
          :columns="tableColumns"
          :handle="tableHandle"
          @getList="getList"
          @handleSelectionChange="handleSelectionChange"
        >

          <template slot="status" slot-scope="{ row }">
            {{ statusList[row.status] }}
          </template>
          <template slot="action" slot-scope="{ row, index }">
            <el-button
              type="white"
              size="mini"
              @click="selectRow(row, index)"
            >权限<i class="el-icon el-icon--right" /></el-button>
            <el-button
              type="white"
              size="mini"
              @click="setUserRow(row, index)"
            >默认配置<i class="el-icon el-icon--right" /></el-button>
            <el-popconfirm
              confirm-button-text="确认"
              cancel-button-text="取消"
              icon="el-icon-info"
              icon-color="red"
              title="确定删除吗？"
              @confirm="deleteRow(row, index)"
            >
              <el-button
                slot="reference"
                class="margin-left-sm"
                type="white"
                size="mini"
              >删除</el-button>
            </el-popconfirm>

            <!-- 未审核 start -->
            <el-popconfirm
              v-if="row.status === 0"
              confirm-button-text="确认"
              cancel-button-text="取消"
              icon="el-icon-info"
              icon-color="red"
              title="确定审核吗？"
              @confirm="upStatusRow('APPROVE', row, index)"
            >
              <el-button
                slot="reference"
                class="margin-left-sm"
                type="white"
                size="mini"
              >审核</el-button>
            </el-popconfirm>
            <!-- 未审核 end -->

            <!-- 正常 start -->
            <el-popconfirm
              v-if="row.status === 1"
              confirm-button-text="确认"
              cancel-button-text="取消"
              icon="el-icon-info"
              icon-color="red"
              title="确定拉黑吗？"
              @confirm="upStatusRow('BLOCK', row, index)"
            >
              <el-button
                slot="reference"
                class="margin-left-sm"
                type="white"
                size="mini"
              >拉黑</el-button>
            </el-popconfirm>

            <el-popconfirm
              v-if="row.status === 1"
              confirm-button-text="确认"
              cancel-button-text="取消"
              icon="el-icon-info"
              icon-color="red"
              title="确定到期吗？"
              @confirm="upStatusRow('EXPIRE', row, index)"
            >
              <el-button
                slot="reference"
                class="margin-left-sm"
                type="white"
                size="mini"
              >权限到期</el-button>
            </el-popconfirm>
            <!-- 正常 end -->

            <!-- 非正常 start  -->
            <el-popconfirm
              v-if="row.status !== 1 && row.status !== 0"
              confirm-button-text="确认"
              cancel-button-text="取消"
              icon="el-icon-info"
              icon-color="red"
              title="确定恢复吗？"
              @confirm="upStatusRow('APPROVE', row, index)"
            >
              <el-button
                slot="reference"
                class="margin-left-sm"
                type="white"
                size="mini"
              >恢复</el-button>
            </el-popconfirm>
            <!-- 非正常 end  -->
          </template>
        </fire-table>
        <!-- 数据列表 end -->
      </el-col>
    </el-row>

    <!-- 授权表单 start -->
    <el-dialog
      :close-on-click-modal="false"
      width="80%"
      :title="permissionTitle"
      :visible.sync="dialogAuthVisible"
    >
      <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="公司" name="bloc">
          <div class="auth-form" style="text-align: center">
            <el-input
              v-model="filterTextBloc"
              style="margin-bottom: 15px"
              placeholder="输入关键字进行过滤"
            />
            <el-tree
              ref="blocTree"
              :data="BlocList"
              show-checkbox
              :default-checked-keys="ValueBloc"
              node-key="bloc_id"
              highlight-current
              :filter-node-method="filterNode"
              :props="defaultProps"
            />
          </div>
        </el-tab-pane>
        <el-tab-pane label="商户" name="store">
          <div class="auth-form" style="text-align: center">
            <el-input
              v-model="filterTextStore"
              style="margin-bottom: 15px"
              placeholder="输入关键字进行过滤"
            />
            <el-tree
              ref="storeTree"
              :data="DataStore"
              show-checkbox
              :default-checked-keys="ValueStore"
              node-key="store_id"
              highlight-current
              :filter-node-method="filterNode"
              :props="defaultProps"
            />
          </div>
        </el-tab-pane>
        <el-tab-pane label="用户组" name="role">
          <div class="auth-form" style="text-align: center">
            <el-input
              v-model="filterTextRole"
              style="margin-bottom: 15px"
              placeholder="输入关键字进行过滤"
            />
            <el-tree
              ref="roleTree"
              :data="DataRole"
              show-checkbox
              :default-checked-keys="ValueRole"
              node-key="item_id"
              highlight-current
              :filter-node-method="filterNameNode"
              :props="nameProps"
            />
          </div>
        </el-tab-pane>
        <el-tab-pane label="应用" name="addons">
          <div class="auth-form" style="text-align: center">
            <el-input
              v-model="filterTextAddons"
              style="margin-bottom: 15px"
              placeholder="输入关键字进行过滤"
            />

            <el-tree
              ref="addonsTree"
              :data="DataAddons"
              show-checkbox
              :default-checked-keys="ValueAddons"
              node-key="mid"
              highlight-current
              :filter-node-method="filterTitleNode"
              :props="titleProps"
            />
          </div>
        </el-tab-pane>
        <el-tab-pane label="权限" name="permission">
          <div class="auth-form" style="text-align: center">
            <el-input
              v-model="filterTextPermission"
              style="margin-bottom: 15px"
              placeholder="输入关键字进行过滤"
            />

            <el-tree
              ref="permissionTree"
              :data="DataPermission"
              show-checkbox
              :default-checked-keys="ValuePermission"
              node-key="item_id"
              highlight-current
              :filter-node-method="filterNameNode"
              :props="nameProps"
            />
          </div>
        </el-tab-pane>
        <el-tab-pane label="规则" name="rule">规则</el-tab-pane>
      </el-tabs>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogAuthVisible = false">取 消</el-button>
        <el-button type="primary" @click="submitAuth">确 定</el-button>
      </span>
    </el-dialog>
    <!-- 授权表单 end -->

    <!-- 数据表单 start -->
    <ele-form-dialog
      v-model="formData"
      :form-desc="formDesc"
      :request-fn="handleSubmit"
      :visible.sync="dialogFormVisible"
      :is-show-back-btn="false"
      title="用户信息"
      @request-success="handleRequestSuccess"
    />
    <!-- 数据表单 end -->

    <!-- 默认配置 start -->
    <el-dialog v-if="userSet" title="用户默认配置" :visible.sync="userSet">
      <ele-form
        v-model="userSetForm"
        v-bind="userSetformConfig"
        :request-fn="subUserDefault"
        @request-success="userSethandleRequestSuccess"
      />
    </el-dialog>
    <!-- 默认配置 end -->
  </div>
</template>
<script>
import {
  getAddons,
  getUserList,
  userDelete,
  userActivate,
  userUpstatus,
  createUser,
  updateUser,
  getUserSet,
  setDefault,
  getDefault
} from '@projectName/views/system/api/admin/user'
import {
  AssignmentFetchView,
  AssignmentFetchChange
} from '@projectName/views/system/api/admin/assignment.js'
import { parseTime } from '@projectName/utils'
import { fetchList as fetchGroup } from '@projectName/views/system/api/admin/group.js'
import { getStoreGroup, getParentbloc, getStorelist, getBlocStore } from '@projectName/views/system/api/addons/bloc.js'
import {
  fetchAddons
} from '@projectName/views/system/api/admin/permission.js'
import { mapState } from 'vuex'

export default {
  data() {
    return {
      statusList: ['待审核', '正常', '权限到期', '已拉黑'],
      BlocList: [],
      userSet: false,
      UserBloc: [],
      addons: [],
      userSetForm: {
        user_id: 0,
        addons_user_id: '',
        store_user_id: '',
        source_type: 1
      },
      // 默认配置数据
      userSetformConfig: {
        formDesc: {
          addons_user_id: {
            type: 'select',
            label: '默认业务',
            isOptions: true,
            options: async() => {
              const that = this
              const { data } = await getUserSet({
                user_id: that.userSetForm.user_id
              })
              return data.addons
            }
          },
          bloc_user_id: {
            type: 'select',
            label: '默认公司',
            isOptions: true,
            options: async() => {
              const that = this
              const { data } = await getUserSet({
                user_id: that.userSetForm.user_id
              })
              return data.UserBloc
            }
          },
          store_user_id: {
            type: 'select',
            label: '默认商户',
            isOptions: true,
            options: async() => {
              const that = this
              const { data } = await getUserSet({
                user_id: that.userSetForm.user_id
              })
              return data.UserStore
            }
          }
        }
      },
      // 默认配置数据
      user_id: 0,
      //  授权表单
      dialogLoadingInstance: null,
      filterTextAddons: '',
      filterTextStore: '',
      filterTextBloc: '',
      filterTextRole: '',
      filterTextRoute: '',
      filterTextPermission: '',
      dialogAuthVisible: false,
      permissionTitle: '用户授权',
      // 权限项管理
      permissionId: 0,
      permission_types: {
        route: 0,
        permission: 1,
        role: 2
      },
      activeName: 'bloc', // 0:route,1:permission,2:role
      // 已授权start
      ValueRoute: [],
      ValuePermission: [],
      ValueRole: [],
      ValueStore: [],
      ValueBloc: [],
      ValueRule: [],
      ValueAddons: [],
      // 已授权end
      // 所有数据start
      DataRoute: [],
      DataPermission: [],
      DataRole: [],
      DataStore: [],
      DataRule: [],
      DataAddons: [],
      // 所有数据end
      // 授权表单end
      listtree: [],
      // 商户列表
      storeList: [],
      defaultProps: {
        children: 'children',
        label: 'label'
      },
      nameProps: {
        children: 'children',
        label: 'name'
      },
      titleProps: {
        children: 'children',
        label: 'title'
      },
      filterInfo: {
        data: {
          page: 1,
          pageSize: 20
        },
        fieldList: [
          {
            label: '用户名称',
            type: 'input',
            value: 'adminUser[username]'
          },
          {
            label: '手机号',
            type: 'input',
            value: 'adminUser[mobile]'
          },
          {
            label: '邮箱',
            type: 'input',
            value: 'adminUser[email]'
          },
          {
            label: '用户组',
            type: 'select',
            value: 'adminUser[group_id]',
            list: 'groupList'
          }
        ]
      },
      // 表格数据 start
      tableColumns: [
        {
          label: 'ID',
          prop: 'id',
          width: 50
        },
        {
          label: '用户名称',
          prop: 'username',
          width: 120
        },
        {
          label: '手机号',
          prop: 'mobile',
          width: 120
        },
        {
          label: '公司名称',
          prop: 'company',
          width: 120
        },
        // {
        //   label: '邮箱',
        //   prop: 'email',
        //   width: 160
        // },
        {
          label: '状态',
          prop: 'status',
          slot: 'status'
        },
        {
          label: '最后登录时间',
          prop: 'last_time',
          width: 180
        },
        {
          label: '注册时间',
          prop: 'created_at',
          width: 180
        },
        // {
        //   label: "用户组",
        //   prop: "store_id",
        // },
        {
          label: '操作',
          type: 'slot',
          slot: 'action',
          align: 'center',
          width: 450
        }
      ],
      // 表单数据
      // 控制是否显示
      dialogFormVisible: false,
      formData: {
        source_type: 1,
        bloc_id: 0
      },
      formDesc: {
        bloc_id: {
          label: '选择公司',
          // 只需要在这里指定为 tree-select 即可
          type: 'tree-select',
          // 属性参考: https://vue-treeselect.js.org/
          attrs: {
            multiple: false,
            clearable: true
          },
          options: getParentbloc().then((res) => {
            console.log('父级公司', res.data)
            const arr = [
              {
                id: 0,
                label: '请选择父级公司'
              }
            ]
            console.log('父级公司', arr.concat(res.data))
            return arr.concat(res.data)
          })
        },
        avatar: {
          type: 'image-uploader',
          label: '头像'
        },
        username: {
          type: 'input',
          label: '用户名'
        },
        email: {
          type: 'input',
          label: '邮箱'
        },
        mobile: {
          type: 'input',
          label: '手机号'
        },
        password: {
          type: 'input',
          label: '密码'
        },
        status: {
          type: 'radio',
          label: '审核状态',
          isOptions: true,
          default: 0,
          options: [
            {
              text: '待审核',
              value: 0
            },
            {
              text: '审核通过',
              value: 1
            }
          ]
        }
      },
      listTypeInfo: {
        groupList: this.initGroup()
      },
      total: 0,
      list: [],
      tableHandle: [],
      listLoading: true
    }
  },
  computed: {
    ...mapState({
      device: state => state.app.device
    })
  },
  watch: {
    filterTextAddons(val) {
      this.$refs.addonsTree.filter(val)
    },
    filterTextStore(val) {
      this.$refs.storeTree.filter(val)
    },
    filterTextBloc(val) {
      this.$refs.blocTree.filter(val)
    },
    filterTextRole(val) {
      this.$refs.roleTree.filter(val)
    },
    filterTextRoute(val) {
      this.$refs.routeTree.filter(val)
    },
    filterTextPermission(val) {
      this.$refs.permissionTree.filter(val)
    }
  },
  created() {
    const that = this
    that.getList()
    // that.getStoreLists();
    that.getAddonsList()
    that.getParentblocList()
    that.getBlocStoreS()
  },
  methods: {
    getBlocStoreS() {
      getBlocStore().then((res) => {
        console.log('getBlocStore', res)
        this.storeList = res.data
      })
    },
    cateChange(store_id) {
      getStorelist().then((res) => {
        res.data.forEach((item, index) => {
          if (item.store_id === store_id) {
            this.formData.bloc_id = item.bloc_id
          }
        })
      })
    },
    handleSubmit(data) {
      console.log(data)
      const that = this
      if (that.dialogStatus === 'create') {
        createUser(data).then((response) => {
          console.log(response)
          that.getList()
          that.dialogFormVisible = false
        })
      } else if (that.dialogStatus === 'update') {
        updateUser(data).then((res) => {
          console.log('更新', res)
          that.getList()
          that.dialogFormVisible = false
        })
      }

      return Promise.resolve()
    },
    filterShow(val) {
      if (!this.filterText) return true
      return val.indexOf(this.filterText) !== -1
    },
    filterNode(value, data) {
      if (!value) return true
      return data.label.indexOf(value) !== -1
    },
    filterNameNode(value, data) {
      if (!value) return true
      return data.name.indexOf(value) !== -1
    },
    filterTitleNode(value, data) {
      if (!value) return true
      return data.title.indexOf(value) !== -1
    },
    // 应用列表
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
    // 用户组列表
    initGroup(bloc_id = 0, store_id = 0) {
      const that = this
      that.listLoading = true
      const arr = []
      arr.push({ id: ' ', name: '选择用户组' })
      fetchGroup({
        'UserGroupSearch[bloc_id]': bloc_id,
        'UserGroupSearch[store_id]': store_id
      }).then((response) => {
        const list = response.data.dataProvider.allModels
        console.log('列表数据层级测试', that.list)
        that.listLoading = false
        list.forEach((item, index) => {
          arr.push({ id: item.id, name: item.name })
        })
      })
      return arr
    },
    // 切换授权项
    handleClick(tab, event) {
      const that = this
      that.filterText = ''
      that.activeName = tab.name
    },
    deleteRow(row, index) {
      const that = this
      console.log('row', row)
      userDelete(row).then((res) => {
        that.getList()
        that.dialogFormVisible = false
        that.$message.success(res.message)
      })
    },
    activateRow(row, index) {
      const that = this
      console.log(row)
      userActivate(row.id).then((res) => {
        that.getList()
      })
    },
    upStatusRow(type, row, index) {
      const that = this
      userUpstatus({
        user_id: row.id,
        type: type
      }).then((res) => {
        that.getList()
        that.$message.success(res.message)
      })
    },
    async getUserSetData(type) {
      const that = this

      const { res } = await getUserSet({
        user_id: that.userSetForm.user_id
      })

      console.log(that.addons)
      let addons_user_id = 0
      let store_user_id = 0

      res.data.UserBloc.forEach((item) => {
        if (item.is_default === 1) {
          addons_user_id = item.value
        }
      })

      res.data.addons.forEach((item) => {
        if (item.is_default === 1) {
          store_user_id = item.value
        }
      })
      console.log('默认值', addons_user_id, store_user_id)
      that.$set(that.userSetForm, 'addons_user_id', addons_user_id)
      that.$set(that.userSetForm, 'store_user_id', store_user_id)

      if (type === 1) {
        return res.data.UserBloc
      } else {
        return res.data.addons
      }
    },
    setUserRow(row, index) {
      const that = this
      that.$set(that.userSetForm, 'user_id', row.id)

      getDefault({
        user_id: that.userSetForm.user_id
      }).then((res) => {
        that.$set(that.userSetForm, 'addons_user_id', res.data.addons_user_id)
        that.$set(that.userSetForm, 'store_user_id', res.data.store_user_id)
        that.userSet = true
      })
    },
    userSethandleRequest() {},
    userSethandleRequestSuccess() {},
    subUserDefault() {
      const that = this
      setDefault(that.userSetForm).then((res) => {
        console.log(res)
        this.$message.success('设置成功')
        that.userSet = false
      })
    },
    selectRow(row, index) {
      const that = this
      that.dialogAuthVisible = true
      this.dialogLoadingInstance = this.$loading({
        visible: true,
        text: '拼命加载中',
        spinner: 'el-icon-loading',
        target: document.querySelector('.el-dialog')
      })

      that.user_id = row.id
      AssignmentFetchView(row.id).then((res) => {
        if (res.code === 200) {
          this.dialogLoadingInstance.close()
          that.ValuePermission = res.data.assigned.permission
          that.ValueRole = res.data.assigned.role
          that.ValueStore = res.data.assigned.store
          that.ValueBloc = res.data.assigned.bloc
          that.ValueRule = res.data.assigned.rule
          that.ValueAddons = res.data.assigned.addons
          that.DataPermission = res.data.all.permission
          that.DataRole = res.data.all.role
          that.DataStore = res.data.all.store
          that.DataRule = res.data.all.rule
          that.DataAddons = res.data.all.addons
        }
      })
    },
    getAddonsList() {
      const data = {
        id: this.user_id
      }
      getAddons(data).then((res) => {
        if (res.code === 200) {
          this.listtree = res.data.list
        }
      })
    },
    handleNodeClick(val) {
      const that = this
      console.log('val', val)

      const data = {
        'adminUser[bloc_id]': val.bloc_id,
        'adminUser[store_id]': val.store_id
      }

      that.$delete(that.filterInfo.data, 'adminUser[group_id]')

      that.$set(
        that.listTypeInfo,
        'groupList',
        this.initGroup(val.bloc_id, val.store_id)
      )
      Object.assign(that.filterInfo.data, data)
      that.getList()
    },
    clearSearch() {
      const that = this
      that.$delete(that.filterInfo.data, 'adminUser[bloc_id]')
      that.$delete(that.filterInfo.data, 'adminUser[store_id]')
      that.$delete(that.filterInfo.data, 'adminUser[group_id]')
      that.$set(that.listTypeInfo, 'groupList', this.initGroup())

      that.getList()
    },
    submitAuth() {
      const that = this
      console.log(
        '提交授权activeName数据',
        that.activeName,
        that.$refs.storeTree.getCheckedKeys()
      )
      let arr = []
      const ob = {}
      switch (that.activeName) {
        case 'permission':
          arr = that.$refs.permissionTree.getCheckedKeys() || []
          break
        case 'role':
          arr = that.$refs.roleTree.getCheckedKeys() || []
          break
        case 'addons':
          arr = that.$refs.addonsTree.getCheckedKeys() || []
          break
        case 'store':
          arr = that.$refs.storeTree.getCheckedKeys() || []
          break
        case 'bloc':
          // eslint-disable-next-line no-case-declarations
          const parend_id = that.$refs.blocTree.getHalfCheckedKeys()
          // eslint-disable-next-line no-case-declarations
          const arrInit = that.$refs.blocTree.getCheckedKeys() || []
          arr = arrInit.filter(item => parend_id.indexOf(item) === -1)
          break
        default:
          break
      }
      console.log('提交授权arr数据', arr)
      that.$set(ob, that.activeName, arr)
      console.log('提交授权ob数据', ob)
      AssignmentFetchChange({
        id: that.user_id,
        items: ob,
        type: that.activeName
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
    uniques(arr) {
      return arr.filter(function(item, index, arr) {
        // 当前元素，在原始数组中的第一个索引==当前索引值，否则返回当前元素
        return arr.indexOf(item, 0) === index
      })
    },
    getStoreLists() {
      getStoreGroup().then((res) => {
        if (res.code === 200) {
          this.storeList = res.data.list
        }
      })
    },
    getParentblocList() {
      getParentbloc().then((res) => {
        if (res.code === 200) {
          this.BlocList = res.data
        }
      })
    },
    handleChange() {},
    // 触发请求
    async resetForm() {
      console.log(this.$refs.form.resetForm())
      await this.$refs.form.resetForm()
    },
    handleSelectionChange(val) {
      console.log('传递来的', val)
    },
    handleRequestSuccess() {},
    getList() {
      const that = this
      that.listLoading = true
      getUserList(that.filterInfo.data).then((response) => {
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
      // this.resetForm();
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
<style type="text/css">
.main-content {
  display: flow-root;
}
.auth-form {
  height: 300px;
  overflow-y: auto;
}
.card {
  height: 600px !important;
}
.el-button--white {
  border: none;
  color: #5659de;
}
</style>
