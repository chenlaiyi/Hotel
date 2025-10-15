<template>
  <div class="app-container">
    <div class="margin-lg">
      <div class="order-detail">
        <div>
          <div style="color: #ef1313">
            <i class="el-icon-warning margin-right-xs" />订单状态：{{
              orderdetail.status_label
            }}
          </div>
          <div class="store-titles">请尽快支付，以免订单超时取消</div>
        </div>
        <div>
          <div>
            应付金额：<span
              style="color: #ef1313"
            >￥{{ orderdetail.pay_price }}</span>
          </div>
          <div class="flex margin-top-sm">
            <el-button
              size="small"
              @click="iscancelorder = true"
            >取消支付</el-button>
            <el-button
              type="danger"
              size="small"
              @click="gopay"
            >立即支付</el-button>
          </div>
        </div>
      </div>
      <div class="store-titles">订购店铺：店滴云</div>
      <el-table :data="tableData" style="width: 100%">
        <el-table-column align="center" label="服务名称">
          <template slot-scope="{ row }">
            <div class="flex">
              <el-image
                slot="title"
                class="margin-right-sm"
                style="width: 61px; height: 61px"
                :src="store.logo"
              />
              <div>{{ store.name }}</div>
            </div>
          </template>
        </el-table-column>
        <!-- <el-table-column
        align="center"
        label="服务时长"
        width="1000"
      ></el-table-column> -->
        <el-table-column align="center" label="小计">
          <template
            slot-scope="{ row }"
          ><div>{{ orderdetail.pay_price }}</div></template></el-table-column>
      </el-table>
      <div class="total-price padding-top-xl">
        <span style="color: #a8a8a8">合计：</span>￥{{ orderdetail.pay_price }}
      </div>
      <div class="pay-btn">
        <div>
          应付金额：<span
            style="color: #ef1313"
          >￥{{ orderdetail.pay_price }}</span>
        </div>
      </div>
    </div>

    <el-dialog :visible.sync="iscancelorder" width="30%" center>
      <div class="text-center">
        <div style="color: #191b5c; font-size: 79px">
          <i class="el-icon-warning" />
        </div>
        <div class="text-bold padding-tb-sm text-black font17">取消订单</div>
        <div class="font15 padding-bottom-sm">
          此订单尚未支付，确定要支付吗？
        </div>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button size="small" @click="handlecancelorder">取消订单</el-button>
        <el-button size="small" type="primary" @click="gopay">去支付</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
import {
  getorderdetail,
  cancelorder
} from '@projectName/api/service/goods.js'
export default {
  name: 'Confirm',
  data() {
    return {
      order_id: 0,
      current: 2,
      checked: true,
      tableData: [{}],
      iscancelorder: false,
      orderdetail: {},
      store: {}
    }
  },
  created() {
    this.order_id = this.$route.params.order_id
    this.store = this.$route.params.store
    console.log(this.store)
    this.getOrderDetail()
  },
  methods: {
    getOrderDetail() {
      const that = this
      const data = {
        order_id: this.order_id
      }
      getorderdetail(data).then((res) => {
        that.orderdetail = res.data
      })
    },
    handlepay() {
      this.$emit('handlepay', this.current)
    },
    // 去支付
    gopay() {
      const that = this
      that.$router.push({
        name: 'service-buy',
        params: {
          current: 2,
          row: that.orderdetail
        }
      })
    },
    // 取消
    handlecancelorder() {
      const that = this
      cancelorder({ order_id: this.order_id }).then((response) => {
        if (response.code === 200) {
          that.$router.back()
        }
        that.iscancelorder = false
      })
    }
  }
}
</script>
<style type="text/css">
.app-container {
  background-color: #fff;
  border-radius: 4px;
}
.order-detail {
  display: flex;
  justify-content: space-between;
  border-bottom: 1px solid rgb(112, 112, 112, 0.18);
  padding-bottom: 40px;
}
.store-titles {
  color: #a8a8a8;
  font-size: 13px;
  margin: 27px 0;
}
.flex {
  display: flex;
  align-items: center;
  justify-content: center;
}
.total-price {
  float: right;
  right: 0;
}
.pay-btn {
  background-color: #f5f5f5;
  margin: 100px 0 25px;
  height: 97px;
  text-align: right;
  padding: 38px 24px 0;
}
.font {
  transform: scale(0.8);
}
.font15 {
  font-size: 15px;
}
.font17 {
  font-size: 17px;
}
</style>
