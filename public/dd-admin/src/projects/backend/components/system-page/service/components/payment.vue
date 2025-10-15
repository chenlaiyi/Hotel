<template>
  <div class="main">
    <div class="order-payment">
      <div class="flexs">
        <div class="pay-success">
          <i class="el-icon-success" />
        </div>
        <div>订单提交成功，请尽快付款！</div>
      </div>
      <div class="pay-tips">请您在7天内完成支付，否则订单会被自动取消</div>
    </div>
    <div class="margin-sm store-detail">
      <div />
      <div class="margin-top-xs">
        应付金额：<span style="color: #f91010">￥{{ price }}</span>
      </div>
    </div>
    <el-row :gutter="20">
      <el-col
        v-for="(i, num) in payList"
        :key="i"
        :span="3"
        class="pay-type margin-right-sm"
        :class="num === typenum ? 'typeactive' : ''"
      >
        <div class="itm-type" @click="changetype(num)">
          <i class="el-dd-wechat-fill" />
          <!-- <svg-icon icon-class="user" fill="#fff" /> -->
          <span class="type-titles margin-left-xs">{{ i.name }}</span>
        </div>
      </el-col>
    </el-row>
    <div v-if="typenum === 0" class="wx-pay">
      <!-- <el-image
        style="width: 203px; height: 199px"
        :src="require('@/static/img/store1.png')"
      ></el-image> -->
      <vue-qr :text="imgUrl" :size="250" :logo-scale="0.2" />
      <div class="margin-left-sm">
        <div>请使用微信扫码，支付成功后自动开通服务</div>
        <el-button
          v-if="detail.pay_status===1"
          type="primary"
          size="small"
          class="margin-top-sm"
          @click="handlecreatePay"
        >我已完成支付</el-button>
      </div>
    </div>
    <div v-if="typenum === 1">
      <div class="pay-bg">
        <div>
          您需要转账<span class="colors text-bold">988.00</span>元至以下帐户，转账成功后请及时提交汇款凭证
        </div>
        <div class="margin-top-xs colors text-bold">(请勿使用支付宝汇款)</div>
        <div class="margin-top-xs">收款户名： 深圳小鹅网络技术有限公司</div>
        <div class="margin-top-xs">收款银行：招商银行深圳科技园支行</div>
        <div class="margin-top-xs">银行账号： 755925097410902</div>
      </div>
      <el-button
        type="primary"
        size="small"
        class="margin-top-sm"
        @click="dialogFormVisible = true"
      >已转账汇款，提交汇款凭证</el-button>
    </div>

    <el-dialog title="提交汇款凭证" :visible.sync="dialogFormVisible">
      <div class="margin-lr-sm" style="font-size: 11px">
        确保信息的准确性，请参考提示填写付款人户名，付款人账号信息
      </div>
      <el-form :model="form" class="margin-top-sm">
        <el-form-item label="付款人姓名：" :label-width="formLabelWidth">
          <el-input v-model="form.name" autocomplete="off" />
        </el-form-item>
        <el-form-item label="付款人账号：" :label-width="formLabelWidth">
          <el-input v-model="form.name" autocomplete="off" />
        </el-form-item>
      </el-form>
      <div class="margin-sm" style="font-size: 11px">应付金额:{{ price }}</div>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="handlecreatePay">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import VueQr from 'vue-qr'
import { createPayparameters } from '@projectName/api/service/goods.js'
export default {
  name: 'Payment',
  components: {
    VueQr
  },
  props: {
    detail: {
      type: Object,
      default() {
        return {}
      }
    },
    price: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      typenum: 0,
      current: 2,
      payList: [
        {
          name: '微信支付'
        }
        // {
        //   name: "线下转账",
        // },
      ],
      dialogFormVisible: false,
      form: {
        name: '',
        region: '',
        date1: '',
        date2: '',
        delivery: false,
        type: [],
        resource: '',
        desc: ''
      },
      formLabelWidth: '120px',
      imgUrl: ''
    }
  },
  mounted() {
    this.getcreatePay()
  },
  methods: {
    handlepay() {
      this.$emit('handlepay', this.current)
    },
    changetype(num) {
      this.typenum = num
    },
    getcreatePay() {
      const that = this
      const data = {
        openid: '',
        trade_type: 'NATIVE',
        body: '商品',
        out_trade_no: that.detail.order_no,
        total_fee: that.price
      }
      createPayparameters(data).then((res) => {
        if (res.code === 200) {
          that.imgUrl = res.data.code_url
          // that.$router.push({
          //   name: "service-success",
          // });
        }
      })
    },
    handlecreatePay() {
      const that = this
      that.$router.push({
        name: 'service-success',
        params: {
          order_id: that.detail.order_id
        }
      })
    }
  }
}
</script>
<style type="text/css">
.order-payment {
  background-color: #f5f5f5;
  margin: 40px 0 25px;
  height: 91px;
  font-size: 13px;
  align-items: center;
  display: grid;
}
.pay-success {
  color: #1ab97e;
  margin: 0 10px 0 32px;
}
.flexs {
  display: flex;
}
.wx-pay {
  display: flex;
  align-items: center;
  margin-top: 48px;
}
.pay-tips {
  margin-left: 32px;
  color: #c8c8c8;
  font-size: 11px;
}
.store-detail {
  font-size: 13px;
  color: #4d4d4d;
}
.pay-type {
  width: 236px;
  height: 57px;
  border-radius: 4px;
  color: #676767;
  font-size: 13px;
  border: 1px solid #c7c7c7;
}
.itm-type {
  width: 236px;
  height: 57px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #09bb07;
}
.typeactive {
  border: 1px solid #5357cb;
  box-shadow: 0px 8px 11px 1px rgba(0, 0, 0, 0.09);
}
.type-titles {
  color: #676767;
  font-size: 13px;
}
.pay-bg {
  width: 507px;
  height: 173px;
  background: #f5f5f5;
  margin-top: 48px;
  font-size: 13px;
  color: #676767;
  padding: 14px 32px;
}
.colors {
  color: #f91010;
}
</style>
