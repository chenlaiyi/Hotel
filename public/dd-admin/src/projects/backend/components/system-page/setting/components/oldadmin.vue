<template>
  <div class="main">
    <div class="old-title text-bold">原管理员身份</div>
    <el-form :model="form" class="margin-top-xl">
      <el-form-item label="原手机号：" :label-width="formLabelWidth">
        <el-input
          v-model="form.phone"
          disabled
          placeholder="150****567123...店铺"
        />
      </el-form-item>
      <el-form-item label="验证码：" :label-width="formLabelWidth">
        <div style="display: flex">
          <el-input v-model="form.code" placeholder="请输入验证码" />
          <div class="code margin-left-sm">获取验证码</div>
        </div>
      </el-form-item>
    </el-form>
    <div class="next-step" @click="handlestep">下一步</div>
  </div>
</template>
<script>
export default {
  name: 'Payment',
  data() {
    return {
      current: 1,
      form: {
        phone: '',
        code: ''
      },
      formLabelWidth: '100px'
    }
  },
  created() {},
  methods: {
    handlestep() {
      this.$emit('handlestep', this.current)
    }
  }
}
</script>
<style type="text/css">
.old-title {
  font-size: 21px;
  color: #333333;
  text-align: center;
}
.code {
  width: 144px;
  height: 36px;
  line-height: 36px;
  border-radius: 4px;
  border: 1px solid #5357cb;
  color: #5357cb;
  font-size: 13px;
  text-align: center;
}
.next-step {
  width: 100%;
  height: 44px;
  line-height: 44px;
  background: #191b5c;
  border-radius: 4px;
  color: #fff;
  font-size: 13px;
  text-align: center;
  margin-top: 124px;
}
</style>
