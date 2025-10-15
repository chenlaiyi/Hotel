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
    />
    <!-- 数据列表 end -->

    <!-- 编辑 start -->
    <el-dialog
      :close-on-click-modal="false"
      width="80%"
      :title="textMap"
      :visible.sync="dialogFormVisible"
    >
      <el-tabs v-model="actives" @tab-click="cutClick">
        <el-tab-pane label="资料" name="second">
          <ele-form
            v-model="formData"
            v-bind="formConfig"
            :request-fn="handleRequest"
            @request-success="handleRequestSuccess"
          />
        </el-tab-pane>
        <el-tab-pane label="财务" name="first">
          <ele-form
            v-model="formDataAccount"
            v-bind="formConfigAccount"
            :request-fn="handleRequestAccount"
            @request-success="handleRequestSuccessAccount"
          />
        </el-tab-pane>
        <el-tab-pane label="密码" name="password">
          <ele-form
            v-model="formDataPassword"
            v-bind="formConfigPassword"
            :request-fn="handleRequestPassword"
            @request-success="handleRequestSuccessPassword"
          />
        </el-tab-pane>
      </el-tabs>
    </el-dialog>
    <!-- 编辑 end -->
  </div>
</template>

<script>
import { form, order, formAccount, orderAccount, formPassword, orderPassword } from './init.js'
import {
  fetchList,
  getView,
  createItem,
  updateItem,
  deleteItem,
  fetchView
} from '@projectName/views/system/api/system/MemberList.js'
// import waves from '@projectName/directive/waves' // waves directive
import { parseTime } from '@projectName/utils'
import EleUploadImage from 'diandi-ele-upload-image'
export default {
  data() {
    return {
      labelPosition: 'top',
      accounts: {
        appid: '',
        token: '',
        aeskey: '',
        secret: '',
        url: '',
        imag: ''
      },
      uploudUrl: '',
      headers: {},
      password: '',
      actives: 'first',
      // 表格数据 start
      tableColumns: [
        { label: '会员ID', prop: 'member_id' },
        { label: '用户名', prop: 'username' },
        { label: '手机号', prop: 'mobile' },
        { label: '状态', prop: 'status' },
        { label: '时间', prop: 'create_time' }
      ],
      tableHandle: [
        {
          label: '会员信息',
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
          { label: '会员ID', type: 'input', value: 'AuthItemSearch[ID]' },
          { label: '会员编码', type: 'input', value: 'AuthItemSearch[num]' },
          { label: 'openid', type: 'input', value: 'AuthItemSearch[openid]' },
          { label: '手机号', type: 'input', value: 'AuthItemSearch[mobile]' },
          { label: '用户名', type: 'input', value: 'AuthItemSearch[name]' },
          { label: '状态', type: 'input', value: 'AuthItemSearch[state]' },
          {
            label: '会员组',
            type: 'select',
            value: 'AuthItemSearch[member_group]'
          }
        ]
      },
      listTypeInfo: {},
      // 检索 end
      // 表单数据 start
      formData: {
        status: 1
      },
      formConfig: {
        formDesc: form,
        order: order
      },
      formDataAccount: {
        status: 1
      },
      formConfigAccount: {
        formDesc: formAccount,
        order: orderAccount
      },
      formDataPassword: {
        status: 1
      },
      formConfigPassword: {
        formDesc: formPassword,
        order: orderPassword
      },
      // 表单数据 end
      total: 0,
      list: [],
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
      textMap: '编辑',
      downloadLoading: false,
      // 权限项管理
      permissionId: 0,
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
      permissionTitle: '修改密码'
    }
  },
  created() {
    this.getList()
  },
  methods: {
    cutClick(tab, event) {
      console.log(tab, event)
    },
    handleSelectionChange(val) {
      console.log('传递来的', val)
    },
    // 单行数据删除
    deleteItem(row) {
      const that = this
      deletePermission(row.id).then((res) => {
        console.log('更新', res)
        that.getList()
        that.dialogFormVisible = false
      })
    },
    authItem(row) {
      const that = this
      that.dialogAuthVisible = true
      that.permissionId = row.id

      fetchView(row.id).then((res) => {
        console.log('assigneds', res.data.assignedKey)
        that.permissionData = res.data.availables
        that.permissionValue = res.data.assignedKey
      })
    },
    getList() {
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
    handleCreate() {
      const that = this
      that.dialogFormVisible = true
    },
    handleDeleteAll() {
      console.log('删除')
    },
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
    handleResponse(response, file, fileList) {
      console.log('response', response)
      // 根据响应结果, 设置 URL
      this.accounts.url = response.attachment
      this.accounts.imag = response.url
      return response.url
    },
    upLoad() {
      // 触发上传图片按钮
      console.log('点击上传按钮')
      this.$refs['uploadImage'].$refs['upload'].$refs[
        'upload-inner'
      ].handleClick()
    },
    beforeRemove(index) {
      console.log(index, this.$refs.uploadImage)
      this.accounts.url = ''
    }
  }
}
</script>
<style>
.el-transfer-panel {
  width: 300px;
}
</style>
