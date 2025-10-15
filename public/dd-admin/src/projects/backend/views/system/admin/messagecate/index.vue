<template>
  <div class="app-container">
    <div class="top-search">
      <div class="bg-search">
        <!-- 公共操作 star -->
        <fire-oper-menu
          :show-excel-export="true"
          :show-excel-import="true"
          @handleCreate="handleCreate"
        />
        <!-- 公共操作 end -->

        <!-- 检索 start -->
        <el-filter
          size="medium"
          :data="filterInfo.data"
          :field-list="filterInfo.fieldList"
          :show-selection="false"
          :list-type-info="listTypeInfo"
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
      :columns="tableColumns"
      :list-query="filterInfo.data"
      @getList="getList"
      @handleSelectionChange="handleSelectionChange"
    >
      <template slot="action" slot-scope="{ row, index }">
        <el-button
          type="white"
          size="mini"
          style="margin-right: 10px"
          @click="amend(row, index)"
        >修改</el-button>

        <el-popconfirm
          confirm-button-text="确认"
          cancel-button-text="取消"
          icon="el-icon-info"
          icon-color="red"
          title="确认删除吗?"
          @confirm="delectRow(row, index)"
        >
          <el-button slot="reference" type="white" size="mini">删除</el-button>
        </el-popconfirm>
      </template>
    </fire-table>
    <!-- 数据列表 end -->
  </div>
</template>

<script>
import {
  messageIndex,
  messageDelete
} from '@projectName/views/system/api/admin/message.js'
export default {
  components: {},
  data() {
    return {
      dialogFormVisible: false,
      // 表格数据 start
      tableColumns: [
        { label: '分类名称', prop: 'name' },
        { label: '创建时间', prop: 'created_at' },
        { label: '更新时间', prop: 'updated_at' },
        {
          label: '操作',
          slot: 'action',
          fixed: 'right',
          width: 300
        }
      ],
      // 表格数据end
      // 检索 start
      filterInfo: {
        data: {
          page: 1,
          pageSize: 10
        },
        fieldList: [
          { label: '分类名称', type: 'input', value: 'HubMessagesCategorySearch[name]' }
        ]
      },
      listTypeInfo: {},
      total: 0,
      list: []
    }
  },
  created() {
    this.getList()
  },
  methods: {
    handleSelectionChange(val) {
      console.log('传递来的', val)
    },
    getList() {
      const that = this
      that.listLoading = true
      messageIndex(that.filterInfo.data).then((response) => {
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
      console.log(row)
    },
    handleEvent(row) {
      console.log(row)
    },
    // 修改
    amend(row) {
      this.$router.push({
        name: 'admin-messagecate-update',
        params: {
          id: row.id,
          rowData: row
        }
      })
    },
    handleCreate() {
      this.$router.push({
        name: 'admin-messagecate-create'
      })
    },
    // 删除
    delectRow(row) {
      const that = this
      messageDelete(row.id).then((response) => {
        that.dialogFormVisible = false
        if (response.code === 200) {
          this.$message.success(response.message)
          that.getList()
        } else {
          this.$message.error(response.message)
        }
      })
    }
  }
}
</script>
<style scoped>
</style>

