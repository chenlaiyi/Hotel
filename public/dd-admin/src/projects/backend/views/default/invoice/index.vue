<template>
  <div class="container">
    <div class="invoice-title">
      <div class="text-bold">发票管理</div>
      <div class="invoice-declera margin-top-sm">
        <p>1.发票一经开出,拒不退换,税率为6%</p>
        <p>
          2.可勾选的多笔订单合并开成一张发票,若需按订单开票请分开勾选后申请开票
        </p>
        <p>3.专票要求店铺 证主体名称、付款方户名、发票抬头需保持一致</p>
        <p>4.单张专票最低开票金额为388元,普票无限制</p>
        <p>5.若信息审核无误,我们将在5个工作日内完成开具与邮寄</p>
        <p>
          6.电子发票查验网址：<span class="is-active">点击查看</span>
          增值税专用发票查验网址：<span class="is-active">点击查看</span>
        </p>
        <p class="is-active" @click="invoiceInform = true">查看开票规则</p>
      </div>
    </div>
    <div class="margin-top-sm invoice-title">
      <el-tabs type="border-card">
        <el-tab-pane label="代开票">
          <el-row :gutter="20" class="margin-top-xs">
            <el-col :sm="12" :xl="6" :md="6" :lg="6" class="invpice-type">
              <div class="invoice-mold">开电子普通发票</div>
              <div class="invoice-mold margin-left-sm">开增值税专用发票</div>
            </el-col>
            <el-col :sm="12" :xl="12" :lg="12">
              创建时间：
              <el-date-picker
                v-model="value1"
                type="datetimerange"
                range-separator="至"
                start-placeholder="开始日期"
                end-placeholder="结束日期"
              />
            </el-col>
          </el-row>
          <!-- 数据列表 start -->
          <fire-table
            ref="table"
            :list="list"
            :total="total"
            :list-loading="listLoading"
            :columns="tableColumns"
            @getList="getList"
            @handleSelectionChange="handleSelectionChange"
          />
        </el-tab-pane>
        <el-tab-pane label="开票记录">
          <div class="invpice-record margin-top-xs">
            <el-select v-model="value" placeholder="发票类型">
              <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
            <el-select
              v-model="value"
              placeholder="发票状态"
              class="margin-left-sm"
            >
              <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </div>
          <!-- 数据列表 start -->
          <fire-table
            ref="table"
            :list="list2"
            :total="total"
            :list-loading="listLoading"
            :columns="recordColumns"
            @getList="getList"
            @handleSelectionChange="handleSelectionChange"
          />
        </el-tab-pane>
      </el-tabs>
    </div>
    <el-dialog :visible.sync="invoiceInform" width="30%" center>
      <div class="invoice-rule">
        <div class="padding-tb-sm text-black cancel-order">发票须知</div>
        <div class="padding-tb-sm">
          <p>
            1.根据国家税务规定,不能向小微（个人）和个体工商户开具增值税专用发票
          </p>
          <p>2.发票金额不含小鹅点、优惠券支付部分</p>
          <p>3.开具不同开票主体的电子普通发票,需要分别提交开票申请</p>
          <p>
            4.开具增值税专用发票,发票抬头需与付款方户名、企业认证主体名称一致
          </p>
          <p>
            5.电子普通发票是税局认可的有效付款凭证,其发票效力、基本用途及使用规定同纸质发票
          </p>
          <p>6.请您按照税法规定使用发票 7.有任何疑问,请您及时咨询服务管家</p>
          <p>7.有任何疑问,请您及时咨询服务管家</p>
        </div>
      </div>

      <span slot="footer">
        <el-button
          size="small"
          type="primary"
          @click="invoiceInform = false"
        >提交</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
export default {
  data() {
    return {
      value1: '',
      tableColumns: [
        { label: '服务时长', prop: 'title', width: 300, align: 'center' },
        { label: '时间', prop: 'title' },
        { label: '支付方式', prop: 'title' },
        { label: '消费金额（元）', prop: 'title' },
        { label: '实际开票金额（元）', prop: 'title' },
        { label: '付款方户名', prop: 'title' }
      ],
      recordColumns: [
        { label: '申请时间', prop: 'title', width: 300, align: 'center' },
        { label: '发票抬头', prop: 'title' },
        { label: '发票类型', prop: 'title' },
        { label: '金额（元）', prop: 'title' },
        { label: '数量', prop: 'title' },
        { label: '状态', prop: 'title' },
        {
          label: '操作',
          slot: 'action',
          align: 'center',
          fixed: 'right',
          width: 100
        }
      ],
      total: 0,
      list: [],
      list2: [],
      listLoading: false,
      invoiceInform: false,
      options: [
        {
          value: '选项1',
          label: '选项1'
        }
      ],
      value: ''
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
      companyIndex().then((response) => {
        // that.total = response.data.dataProvider.total;
        // that.list = response.data.dataProvider.allModels;
        that.listLoading = false
      })
    }
  }
}
</script>
<style type="text/css">
.container {
  margin: 20px;
}
.invoice-title {
  color: #4d4d4d;
  font-size: 15px;
  background-color: #fff;
  border-radius: 4px;
  padding: 24px;
}
.invoice-declera {
  font-size: 13px;
  line-height: 13px;
}
.el-tabs--border-card {
  border: none;
  -webkit-box-shadow: none;
  box-shadow: none;
  background-color: none;
}
.is-active {
  color: #5357cb !important;
}
.invpice-type {
  display: flex;
  justify-content: space-between;
}
.invoice-mold {
  font-size: 15px;
  color: #a6a6aa;
  width: 162px;
  height: 42px;
  line-height: 42px;
  background: #f5f5f5;
  border: 1px solid #eeeeee;
  text-align: center;
}
.invoice-rule {
  font-size: 13px;
  color: #232323;
  padding: 0 40px;
  line-height: 17px;
}
.invpice-record {
  display: flex;
}
</style>
