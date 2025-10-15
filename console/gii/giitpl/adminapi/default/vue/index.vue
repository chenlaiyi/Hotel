<template>
  <div class="app-container">
    <!-- 公共操作 star -->
    <fire-oper-menu
        :show-excel-export="false"
        :show-excel-import="false"
        @handleCreate="handleCreate"
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

    <!-- 数据列表 start -->
    <dd-table
        ref="table"
        :list="list"
        :total="total"
        :list-loading="listLoading"
        :columns="tableColumns"
        :list-query="filterInfo.data"
        @getList="getList"
        @handleSelectionChange="handleSelectionChange"
    >
      <template slot="action" slot-scope="{ row, index }">
        <el-button
            type="primary"
            size="mini"
            style="margin-right: 10px"
            @click="editRow(row, index)"
        >修改</el-button>

        <el-popconfirm
            confirm-button-text="确认"
            cancel-button-text="取消"
            icon="el-icon-info"
            icon-color="red"
            title="确认删除吗?"
            @confirm="deleteRow(row, index)"
        >
          <el-button slot="reference" type="danger" size="mini">删除</el-button>
        </el-popconfirm>
      </template>
    </dd-table>
    <!-- 数据列表 end -->

    <list-form :params="formObj.params" :type="formObj.type" :title="formObj.title"
               :dialogVisible="formObj.dialogVisible" @formClose="listFormClose" />
  </div>
</template>

<script>
import {
  initList, itemDelete
} from "./api";
import {
  tableColumns,
  filterInfo,
  path
} from "./init";
import { auth } from './auth'
export default {
  components: {},
  data() {
    return {
      auth: auth,
      formObj: {
        type: null,
        title: '客户列表',
        dialogVisible: false,
        params: {}
      },
      dialogTableVisible: false,
      // 表格数据 start
      tableColumns: tableColumns,
      // 表格数据end
      // 检索 start
      filterInfo: {
        data: {
          page: 1,
          pageSize: 10
        },
        ...filterInfo
      },
      listLoading:true,
      total: 0,
      list: [],
      excelList: []
    }
  },
  created() {
    this.getList()
  },
  methods: {
    handleSelectionChange(val) {
      console.log('传递来的', val)
    },
    handleRequestSuccess() {
    },
    getList() {
      const that = this
      that.listLoading = true
      initList(that.filterInfo.data).then((response) => {
        that.total = response.data.dataProvider.total
        that.list = response.data.dataProvider.allModels
        console.log('列表数据层级测试', that.list)
        that.listLoading = false
      })
    },
    //  搜索
    handleFilter(row) {
      const that = this
      console.log(row)
      that.$set(that.filterInfo, 'data', row)
      console.log('检索前', that.filterInfo.data)
      that.getList()
    },
    handleReset(row) {
      this.getList()
    },
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
    // 修改
    editRow(row) {
      this.formObj = {
        type: "update",
        title: "编辑",
        dialogVisible: true,
        row: row,
        params: row,
      };
    },
    handleCreate() {
      this.formObj = {
        type: 'create',
        title: '新增',
        dialogVisible: true
      }
      this.$formEvent.$emit('form-event', true);
    },
    // 删除
    deleteRow(row) {
      const that = this
      itemDelete(row.id).then((response) => {
        if (response.code === 200) {
          this.$message.success(response.message)
          that.getList()
        } else {
          this.$message.error(response.message)
        }
      })
    },
    handleChange(value) {
      console.log(value)
    }
  }
}
</script>
<style scoped>
.active {
  color: #fff;
  background-color: #409eff;
}
.isActive {
  color: #fff;
  background-color: #9ca1a1;
}
</style>
s
