<template>
  <div class="success-container">
    <div class="order-success">
      <i class="el-icon-success" />
      <span class="text-bold title text-sm">恭喜您的订单支付成功</span>
    </div>
    <div class="order-det">
      <div class="title-detail text-bold padding-bottom-sm">订单详情：</div>
      <div class="pay-detal">
        支付金额：<span style="color: #ff0000">￥{{ detail.pay_price }}</span>元
      </div>
      <div class="pay-detal">
        订单编号:<span style="color: #5357cb">{{ detail.order_no }}</span>
      </div>
      <div class="pay-detal">
        支付平台:<span style="color: #5357cb">微信支付</span>
      </div>
      <div class="pay-detal">下单时间:{{ detail.pay_time }}</div>
    </div>
    <div class="shop-btn text-center text-white" @click="goshop">继续逛逛</div>
  </div>
</template>
<script>
import { getorderdetail } from '@projectName/api/service/goods.js'
export default {
  data() {
    return {
      order_id: 0,
      detail: ''
    }
  },
  created() {
    this.order_id = this.$route.params.order_id
    this.getDetail()
  },
  methods: {
    getDetail() {
      const that = this
      const data = {
        order_id: that.order_id
      }
      getorderdetail(data).then((response) => {
        that.detail = response.data
      })
    },
    goshop() {
      const that = this
      that.$router.push({
        name: 'service-subscription'
      })
    }
  }
}
</script>
<style type="text/css">
.success-container {
  margin: 84px 300px;
  color: #212121;
}
.order-success {
  font-size: 56px;
  color: #ff0000;
  display: flex;
  margin: 0 auto 113px;
  width: 316px;
}
.title {
  color: #212121;
  line-height: 56px;
  margin-left: 16px;
}
.order-det {
  width: 100%;
  height: 451px;
  background: #ffffff;
  border-radius: 4px;
  padding: 40px 22px;
}
.title-detail {
  font-size: 17px;
  width: 100%;
  border-bottom: 1px solid rgb(112, 112, 112, 0.3);
}
.pay-detal {
  padding: 54px 38px 0;
  font-size: 15px;
}
.shop-btn {
  width: 90px;
  height: 46px;
  line-height: 46px;
  background: #191b5c;
  border-radius: 4px;
  font-size: 13px;
  margin: 91px auto;
}
</style>
