<template>
  <div>
    <div class="edition-title">版本服务</div>
    <el-row :gutter="20">
      <el-col :span="6" class="sub-itm2 padding-lr-sm">
        <el-row>
          <el-col :span="5">
            <el-image
              style="width: 56px; height: 56px; margin-top: 20px"
              :src="detail.logo"
            />
          </el-col>
          <el-col :span="19">
            <div class="addons-title">{{ detail.title }}</div>
            <div class="addons-desc" style="color: #afafaf">
              {{ detail.ability }}
            </div>
            <div class="ty-btns margin-top-sm text-center">体验</div>
          </el-col>
        </el-row>
      </el-col>
      <el-col
        v-for="(i, num) in goodstype"
        :key="i"
        :span="3"
        class="price-type margin-right-sm"
        :class="num === typenum ? 'typeactive' : ''"
      >
        <div @click="changetype(num, i[0].name)">
          <div class="text-sm text-bold">{{ num }}</div>
          <div class="type-pri text-sm text-bold">￥{{ i[0].name }}</div>
          <div class="addons-desc" style="color: #afafaf">
            <!-- 有效期至:{{i[0].name}} -->
          </div>
        </div>
      </el-col>
    </el-row>
    <div class="bottom-sumbent">
      <div style="float: right; display: flex">
        <div class="total-price">
          合计：<span style="color: #ee0e0e">￥{{ price }}</span>
        </div>
        <div class="bg-color text-center text-white" @click="handlesumbit">
          提交订单
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { getgoodsdetail } from '@projectName/api/service/goods.js'
export default {
  name: 'Selece',
  data() {
    return {
      typenum: 0,
      current: 1,
      detail: '',
      goods_id: 0,
      goodstype: [],
      price: 0
    }
  },
  mounted() {
    this.goods_id = this.$route.params.id
    this.getDetail()
  },
  methods: {
    getDetail() {
      const that = this
      const data = {
        goods_id: this.goods_id
      }
      getgoodsdetail(data).then((res) => {
        that.detail = res.data.dis.addons
        that.goodstype = res.data.goods.specs.list
        const key = Object.keys(res.data.goods.specs.list)
        that.typenum = key[0]
        that.price = res.data.goods.specs.list[that.typenum][0].name
      })
    },
    changetype(num, price) {
      this.typenum = num
      this.price = price
    },
    handlesumbit() {
      this.$emit('changecurrent', this.current, this.price, this.detail.id, this.goods_id)
    }
  }
}
</script>
<style type="text/css">
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
  line-height: 20px;
}
.price-type {
  width: 223px;
  height: 111px;
  padding: 13px 25px;
  border-radius: 8px;
  border: 1px solid #d2d2d2;
}
.typeactive {
  border: 1px solid #5357cb;
  box-shadow: 0px 8px 11px 1px rgba(0, 0, 0, 0.09);
}
.type-pri {
  color: #ff0000;
  margin: 9px 0 5px !important;
}
.bottom-sumbent {
  position: fixed;
  bottom: 26px;
  width: 87% !important;
  padding: 16px 33px;
  background-color: #fff;
}
.total-price {
  font-size: 13px;
  margin: auto 28px;
}
.bg-color {
  background-color: #5357cb;
  height: 24px;
  line-height: 24px;
  width: 72px;
  border-radius: 4px;
  font-size: 13px;
}
</style>
