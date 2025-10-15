<template>
  <div class="store-attest">
    <div class="attest-title">店滴云店铺认证</div>
    <div class="padding-xl">
      <div class="store-type text-bold padding-left-xs">主体类型</div>
      <div class="main-types">个体工商户</div>
      <div class="attset-tip margin-top-sm">
        如需更改主体类型，请在
        <span style="color: #5357cb">认证起始页</span>进行操作
      </div>
      <el-form :model="form" class="margin-top-xl">
        <div class="store-type text-bold padding-left-xs margin-tb-xl">
          个人信息
        </div>
        <el-form-item label="姓名" :label-width="formLabelWidth">
          <el-input v-model="form.phone" />
        </el-form-item>
        <el-form-item label="证件类型" :label-width="formLabelWidth">
          <el-input v-model="form.code" />
        </el-form-item>
        <el-form-item label="身份证号码" :label-width="formLabelWidth">
          <el-input v-model="form.code" />
        </el-form-item>
        <div class="store-type text-bold padding-left-xs margin-tb-xl">
          经营信息
        </div>
        <el-form-item label="联系人姓名" :label-width="formLabelWidth">
          <el-input v-model="form.phone" />
        </el-form-item>
        <el-form-item label="联系方式" :label-width="formLabelWidth">
          <el-input v-model="form.code" />
        </el-form-item>
        <el-form-item label="经营地址" :label-width="formLabelWidth">
          <el-input v-model="form.code" />
        </el-form-item>
        <el-form-item label="选择经营范围" :label-width="formLabelWidth">
          <el-input v-model="form.code" />
        </el-form-item>
        <div class="store-type text-bold padding-left-xs margin-tb-xl">
          验证管理员身份
        </div>
        <el-form-item label="短信验证码" :label-width="formLabelWidth">
          <el-input v-model="form.phone" />
          <div>验证码将发送到管理员手机号：+86-18119440935 ，请注意查收</div>
        </el-form-item>
      </el-form>
    </div>
    <el-button class="order-btn" type="primary">提交</el-button>
  </div>
</template>
<script>
export default {
  components: {},
  data() {
    return {
      form: {
        phone: '',
        code: ''
      }
    }
  },
  methods: {}
}
</script>
<style type="text/css">
.store-attest {
  background-color: #fff;
  margin: 22px 338px;
}
.attest-title {
  width: 100%;
  height: 76px;
  line-height: 76px;
  background: #191b5c;
  border-radius: 4px 4px 0px 0px;
  color: #fff;
  font-size: 17px;
  text-align: center;
}
.store-type {
  color: #040404;
  height: 20px;
  font-size: 20px;
  border-left: 3px solid #5357cb;
}
.attset-tip {
  color: #8a8a8b;
  font-size: 15px;
}
.order-btn {
  margin: 0 291px 48px;
}
</style>
