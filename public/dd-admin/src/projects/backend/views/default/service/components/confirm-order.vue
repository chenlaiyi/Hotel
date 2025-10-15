<template>
  <div class="main">
    <!-- <div class="store-title">订购店铺：150****567123</div> -->
    <el-table :data="tableData" style="width: 100%">
      <el-table-column align="center" label="服务名称">
        <template>
          <div class="flex">
            <el-image
              slot="title"
              class="margin-right-sm"
              style="width: 61px; height: 61px"
              :src="detail.logo"
            />
            <div>{{ detail.title }}</div>
          </div>
        </template>
      </el-table-column>
      <!-- <el-table-column
        align="center"
        prop="time"
        label="服务时长"
        width="1000"
      ></el-table-column> -->
      <el-table-column align="center" label="小计">
        <div class="flex">
          <div>{{ price }}</div>
        </div></el-table-column>
    </el-table>
    <div class="total-price padding-top-xl">
      <span style="color: #a8a8a8">合计：</span>￥{{ price }}
    </div>
    <div class="pay-price-detail text-right">
      <div class="total-price">
        <div class="margin-top">
          应付金额：<span
            class="margin-left-xs text-bold text-red"
          >￥{{ price }}</span>
        </div>
        <el-button
          type="danger"
          size="mini"
          class="margin-top-sm"
          @click="handlecreateOrder"
        >立即支付</el-button>
      </div>
    </div>
    <div class="total-price font">
      <el-checkbox
        v-model="checked"
      >阅读并同意<span
        style="color: #5357cb"
      >《店滴云店铺SAAS服务协议》</span></el-checkbox>
    </div>
  </div>
</template>
<script>
import {
  getgoodsdetail,
  createOrder
} from '@projectName/api/service/goods.js'
export default {
  name: 'Confirm',
  props: {
    price: {
      type: String,
      default: ''
    },
    addonsId: {
      type: String,
      default: ''
    },
    goodsId: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      current: 2,
      checked: true,
      detail: '',
      tableData: [
        {
          title: ''
        }
      ],
      goods_type: 0
    }
  },
  created() {
    this.getDetail()
    console.log('addons_id', this.price)
  },
  methods: {
    getDetail() {
      const that = this
      const data = {
        goods_id: this.goods_id
      }
      getgoodsdetail(data).then((res) => {
        that.detail = res.data.dis.addons
        that.goods_type = res.data.dis.goods_type
      })
    },
    handlecreateOrder() {
      const that = this
      const data = {
        goods_id: that.goods_id,
        total_price: that.price,
        goods_number: 1,
        express_price: 0,
        express_type: 0
      }
      createOrder(data).then((res) => {
        if (res.code === 200) {
          this.$emit('handlepay', this.current, res.data)
        }
      })
    }
  }
}
</script>
<style type="text/css">
.store-title {
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
}
.pay-price-detail {
  background-color: #f5f5f5;
  margin: 100px 0 25px;
  height: 128px;
}
.font {
  transform: scale(0.8);
}
</style>
