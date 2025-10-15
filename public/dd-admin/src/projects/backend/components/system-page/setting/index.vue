<template>
  <div class="setting-container padding-bottom-xxl">
    <div class="store-title text-bold">店铺设置</div>
    <div class="store-info text-bold padding-left-xs">店铺信息</div>
    <el-row :gutter="20" class="store-cont">
      <el-col :span="2">
        <div style="color: #8d8d8d">店铺名称</div>
      </el-col>
      <el-col :span="22">
        <div class="store-detail">
          <div class="margin-left-lg">150****567123...店铺</div>
          <div class="set-color">设置</div>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="20" class="store-cont">
      <el-col :span="2">
        <div style="color: #8d8d8d">店铺地址</div>
      </el-col>
      <el-col :span="22">
        <div class="store-detail">
          <div class="margin-left-lg">
            https://app5ystro2q8355.h5.xiaoeknow.com
          </div>
          <div class="set-color">复制链接</div>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="20" class="store-cont">
      <el-col :span="2">
        <div style="color: #8d8d8d">店铺管理员</div>
      </el-col>
      <el-col :span="22">
        <div class="store-detail">
          <div class="margin-left-lg">181****0935</div>
          <div class="set-color" @click="changeadmin">更换</div>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="20" class="store-cont">
      <el-col :span="2">
        <div style="color: #8d8d8d">店铺appid</div>
      </el-col>
      <el-col :span="22">
        <div class="store-detail">
          <div class="margin-left-lg">app5ystro2q8355</div>
          <div class="set-color">复制</div>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="20" class="store-cont">
      <el-col :span="2">
        <div style="color: #8d8d8d">店铺认证</div>
      </el-col>
      <el-col :span="22">
        <div class="store-detail">
          <div class="margin-left-lg">未认证</div>
          <div class="set-color" @click="goattestation">立即认证</div>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="20" class="store-cont">
      <el-col :span="2">
        <div style="color: #8d8d8d">主营类目</div>
      </el-col>
      <el-col :span="22">
        <div class="store-detail set-color">
          <div class="margin-left-lg" />
          <div @click="selectstoretype = true">设置</div>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="20" class="store-cont">
      <el-col :span="2">
        <div style="color: #8d8d8d">商家隐私</div>
      </el-col>
      <el-col :span="22">
        <div class="store-detail set-color">
          <div class="margin-left-lg">查看《商家隐私保护指引》</div>
          <div>设置</div>
        </div>
      </el-col>
    </el-row>

    <!-- 选择主营类目 -->
    <el-dialog :visible.sync="selectstoretype" width="50%" center>
      <p slot="title" style="text-align: left">选择主营类目</p>
      <el-row :gutter="20">
        <el-col v-for="(item, index) in 6" :key="index" :md="4" :xs="24">
          <div class="category-list margin-bottom-sm">智能房控</div>
        </el-col>
      </el-row>
      <div class="margin-top-sm text-center" style="font-size: 13px">
        <div>
          以上都不是？
          <span class="set-color" @click="handletitle">提交您的主营信息</span><i class="el-icon-arrow-right" />
        </div>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button @click="selectstoretype = false">取 消</el-button>
        <el-button
          type="primary"
          @click="selectstoretype = false"
        >确 定</el-button>
      </span>
    </el-dialog>

    <!-- 设置主营类目 -->
    <el-dialog :visible.sync="setstoretype" width="50%" center>
      <p slot="title" style="text-align: left">
        请填写您的主营类目信息，以便为您提供更优质的服务
      </p>
      <el-form :model="form" class="margin-lg" label-width="80px">
        <el-form-item label="主营类目">
          <el-input v-model="form.type" />
        </el-form-item>
        <el-form-item label="类目描述">
          <el-input v-model="form.type" type="textarea" />
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="setstoretype = false">返回</el-button>
        <el-button type="primary" @click="setstoretype = false">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
export default {
  data() {
    return {
      selectstoretype: false,
      setstoretype: false,
      form: {
        type: ''
      }
    }
  },
  created() {},
  methods: {
    // 更换管理员
    changeadmin() {
      const that = this
      that.$router.push({
        name: 'setting-administrator'
      })
    },
    // 店铺认证
    goattestation() {
      const that = this
      that.$router.push({
        name: 'setting-attestation'
      })
    },
    // 设置主营类目
    handletitle() {
      this.selectstoretype = false
      this.setstoretype = true
    }
  }
}
</script>
<style type="text/css">
.setting-container{
  background-color: #fff;
  margin: 20px;
  border-radius: 4px;
}
.store-info {
  color: #212121;
  margin: 38px 34px 0;
  height: 15px;
  border-left: 3px solid #5357cb;
}
.store-title {
  font-size: 15px;
  padding: 24px;
  margin: 0 20px;
  color: #4d4d4d;
  border-bottom: 1px solid rgb(112, 112, 112, 0.1);
}
.store-cont {
  padding: 46px 34px 0;
  font-size: 13px;
}
.store-detail {
  display: flex;
  justify-content: space-between;
  border-bottom: 1px solid rgb(112, 112, 112, 0.2);
  padding-bottom: 24px;
}
.set-color {
  color: #5357cb;
}
.padding-bottom-xxl {
  padding-bottom: 80px;
}
.category-list {
  font-size: 15px;
  color: #040404;
  width: 103px;
  height: 37px;
  line-height: 37px;
  border-radius: 23px;
  text-align: center;
  border: 1px solid #e2e2e2;
}
</style>
