<template>
  <div class="app-container">
    <div class="sub-store-order">
      <el-row :gutter="20" style="background: #f5f5f5">
        <el-col v-for="(itm, idx) in step" :key="idx" :span="6">
          <div :class="idx === current ? 'avtive' : 'buy-step'">
            {{ itm }}
          </div>
        </el-col>
      </el-row>
      <selece v-if="current===0" @changecurrent="changecurrent" />
      <confirm-order v-if="current===1" :price="price" :addons_id="addons_id" :goods_id="goods_id" @handlepay="handlepay" />
      <payment v-if="current===2":price="price" :detail="detail" />
    </div>
  </div>
</template>
<script>
import Selece from './components/Selece'
import confirmOrder from './components/confirm-order'
import payment from './components/payment'
export default {
  components: {
    Selece,
    confirmOrder,
    payment
  },
  data() {
    return {
      value: 5,
      step: ['1.选择服务', '2.确认订单', '3.确认付款', '4.完成订购'],
      current: 0,
      typenum: 0,
      price: 0,
      addons_id: 0,
      goods_id: 0,
      detail: 0
    }
  },
  created() {
    if (this.$route.params.current) {
      this.current = this.$route.params.current
      this.price = this.$route.params.row.total_price
      this.detail = this.$route.params.row
    }
  },
  methods: {
    changecurrent(data, price, addons_id, goods_id) {
      console.log('data', data, price, addons_id)
      this.price = price
      this.current = data
      this.addons_id = addons_id
      this.goods_id = goods_id
    },
    handlepay(data, detail) {
      this.current = data
      this.detail = detail
    }
  }
}
</script>
<style type="text/css">
.sub-store-order {
  background-color: #fff;
  padding: 23px 23px 100px;
}
.buy-step {
  background: #f5f5f5;
  padding: 20px 0;
  color: #333;
  font-size: 15px;
  text-align: center;
}
.avtive {
  background-color: #191b5c;
  color: #fff;
  padding: 20px 0;
  text-align: center;
  position: relative;
}
.avtive::after {
  content: "";
  display: block;
  position: absolute;
  right: -30px; /* 箭头位置 */
  top: 0; /* 箭头位置 */
  border-top: 30px solid transparent; /* 箭头高低 */
  border-bottom: 30px solid transparent; /* 箭头高低 */
  border-left: 30px solid #191b5c; /* 箭头长度*/
}
.edition-title {
  color: #4d4d4d;
  font-size: 15px;
  margin: 27px 0;
  padding: 0 16px;
  border-left: 1px solid #5357cb;
}
.ty-btns {
  width: 34px;
  height: 20px;
  border-radius: 4px;
  border: 1px solid #aaadec;
  color: #aaadec;
  font-size: 11px;
  text-align: center;
  line-height: 20px;
  margin-top: 20px;
}
.price-type {
  padding: 13px 25px;
  border-radius: 8px 8px 8px 8px;
  border: 1px solid #d2d2d2;
}
.typeactive {
  border: 1px solid #5357cb;
  box-shadow: 0px 8px 11px 1px rgba(0, 0, 0, 0.09);
}
.type-pri {
  color: #ff0000;
  margin: 16px 0 9px;
}
.bot-sumb {
  position: fixed;
  bottom: 26px;
  width: 100%;
  padding: 22px 0;
  background-color: #fff;
}
.total-price {
  font-size: 13px;
  margin: auto 28px;
}
</style>
