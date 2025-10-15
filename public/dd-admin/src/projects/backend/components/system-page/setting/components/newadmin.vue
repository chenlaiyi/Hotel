<template>
  <div class="main">
    <div class="old-title text-bold text-xs text-center">新店铺管理员</div>
    <el-form :model="form" class="margin-top-xl">
      <el-form-item label="新手机号:" :label-width="formLabelWidth">
        <el-input v-model="form.phone" placeholder="150****567123...店铺">
          <el-select slot="prepend" v-model="select">
            <el-option label="86" value="1" />
          </el-select>
        </el-input>
      </el-form-item>
      <el-form-item label="验证码：" :label-width="formLabelWidth">
        <div style="display: flex">
          <el-input v-model="form.code" placeholder="请输入验证码" />
          <div class="code margin-left-sm">获取验证码</div>
        </div>
      </el-form-item>
    </el-form>
    <div class="next-step" @click="handleconfirm">确定</div>
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
      select: '',
      formLabelWidth: '100px'
    }
  },
  created() {},
  methods: {
    handleconfirm() {
      const that = this
      that.$router.push({
        name: 'seting-index'
      })
    }
  }
}
</script>
<style type="text/css">
.old-title {
  color: #333333;
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
