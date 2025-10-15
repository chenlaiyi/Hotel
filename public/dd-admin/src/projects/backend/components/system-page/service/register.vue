<template>
  <div class="app-container">
    <div class="top-search">
      <div class="bg-search">
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
      <template slot="pay_status" slot-scope="{ row }">
        <div v-if="row.store">{{ row.store.name }}</div>
      </template>
      <template slot="action" slot-scope="{ row, index }">
        <el-button
          class="btn-rig"
          size="mini"
          @click="gopay(row)"
        >去支付</el-button>
        <el-button class="btn-rig margin-right-xs" size="mini" @click="amend(row)">查看</el-button>
        <el-popconfirm
          confirm-button-text="确认"
          cancel-button-text="取消"
          icon="el-icon-info"
          icon-color="red"
          title="确定要取消吗？"
          @confirm="delectRow(row)"
        >
          <el-button slot="reference" class="btn-rig" size="mini">取消</el-button>
        </el-popconfirm>
      </template>
    </fire-table>
    <!-- 数据列表 end -->
  </div>
</template>

<script>
import { getgoodsOrderList, cancelorder } from '@projectName/api/service/goods.js'
export default {
  components: {},
  data() {
    return {
      dialogFormVisible: false,
      // 表格数据 start
      tableColumns: [
        { label: '创建时间', prop: 'create_time' },
        { label: '服务内容', prop: 'order_body' },
        // { label: "组合服务", prop: "title" },
        { label: '费用（元）', prop: 'pay_price' },
        // { label: "发票", prop: "title" },
        { label: '订单状态', prop: 'pay_status', slot: 'pay_status' },
        {
          label: '操作',
          slot: 'action',
          align: 'center',
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
          // { label: "服务类型：", type: "input", value: "PartyAnswer[title]" },
          { label: '订购状态：', type: 'select', value: 'order_status', list: 'statusList' }
          // { label: "创建时间：", type: "input", value: "PartyAnswer[title]" },
        ]
      },
      listTypeInfo: {
        statusList: [{
          id: 1,
          name: '已付款'
        }, {
          id: 0,
          name: '未付款'
        },
        {
          id: 2,
          name: '已发货'
        },
        {
          id: 3,
          name: '已收货'
        },
        {
          id: 4,
          name: '已完成'
        },
        {
          id: 5,
          name: '申请售后'
        }]
      },
      total: 0,
      list: [
        {
          title: 'title'
        }
      ]
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
      that.listLoading = false
      getgoodsOrderList(that.filterInfo.data).then((response) => {
        that.total = response.data.length
        that.list = response.data
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
    // 查看
    amend(row) {
      const that = this
      that.$router.push({
        name: 'service-details',
        params: {
          order_id: row.order_id,
          store: row.store
        }
      })
    },
    // 删除
    delectRow(row) {
      const that = this
      cancelorder({ order_id: row.order_id }).then((response) => {
        if (response.code === 200) {
          that.getList()
        }
      })
    },
    // 去支付
    gopay(row) {
      const that = this
      that.$router.push({
        name: 'service-buy',
        params: {
          current: 2,
          row: row
        }
      })
    }
  }
}
</script>
<style>
.btn-rig {
  border: none !important;
  color: #5659de;
}
</style>
