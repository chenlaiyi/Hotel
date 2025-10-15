<template>
  <div class="app-container">
    <div class="padd">
      <div class="text-bold text-sm">
        店滴云将根据《中华人民共和国电子商务法》要求，收集入驻商家信息，请选择您的店铺店铺信息并准备相应认证材料：
      </div>
      <div class="store-type text-bold padding-left-xs margin-top-xl">
        主体类型
      </div>
      <div class="main-types">个体工商户</div>
      <div class="att-tip margin-top-lg">
        注：实物商品售卖，不同主体有对应经营类目范围，经营类目范围及相关资质要求请查看
        <span style="color: #5357cb">《商品类目资质须知》</span>
      </div>
      <div class="store-type text-bold padding-left-xs margin-top-xl">
        所需材料
      </div>
      <div class="materials">
        <p>
          1.
          证明店铺归属人的材料：个人店铺，个人手持证件照；非个人店铺：法定代表人身份证正反面照片；
        </p>

        <p>
          2.
          提供真实合法的经营信息：营业执照，如有线下门店则需提供店铺门头，店铺内景，收银台照片；
        </p>

        <p>
          3.
          照片规则：照片需四角完整，清晰可辨，若加水印需保证照片重要信息清晰可辨；可提交复印件，每张复印件均需加盖完整红色公章；
        </p>

        <p>
          4.
          如有涉及实物售卖则需要提供相关经营类目的资质，详情请看《商品类目资质须知》，请事先检查是否包含自己店铺主体可售卖的实物范围。
        </p>
      </div>
      <div class="start-attest" @click="handleattset">开始认证</div>
    </div>
    <div class="text-bold text-sm">
      店滴云将根据《中华人民共和国电子商务法》要求，收集入驻商家信息，请选择您的店铺店铺信息并准备相应认证材料：
    </div>
    <div class="store-type text-bold padding-left-xs margin-top-xl">
      主体类型
    </div>
    <div class="main-types">个体工商户</div>
    <div class="att-tip margin-top-lg">
      注：实物商品售卖，不同主体有对应经营类目范围，经营类目范围及相关资质要求请查看
      <span style="color: #5357cb">《商品类目资质须知》</span>
    </div>
    <div class="store-type text-bold padding-left-xs margin-top-xl">
      所需材料
    </div>
    <div class="materials">
      <p>
        1.
        证明店铺归属人的材料：个人店铺，个人手持证件照；非个人店铺：法定代表人身份证正反面照片；
      </p>

      <p>
        2.
        提供真实合法的经营信息：营业执照，如有线下门店则需提供店铺门头，店铺内景，收银台照片；
      </p>

      <p>
        3.
        照片规则：照片需四角完整，清晰可辨，若加水印需保证照片重要信息清晰可辨；可提交复印件，每张复印件均需加盖完整红色公章；
      </p>

      <p>
        4.
        如有涉及实物售卖则需要提供相关经营类目的资质，详情请看《商品类目资质须知》，请事先检查是否包含自己店铺主体可售卖的实物范围。
      </p>
    </div>
    <div class="start-attest" @click="handleattset">开始认证</div>
  </div>
</template>
<script>
export default {
  components: {},
  data() {
    return {}
  },
  methods: {
    handleattset(data) {
      const that = this
      that.$router.push({
        name: 'setting-storeattest'
      })
    }
  }
}
</script>
<style type="text/css">
.padd {
  padding: 51px 220px 63px;
}
.store-type {
  color: #212121;
  height: 20px;
  font-size: 20px;
  border-left: 3px solid #5357cb;
}
.att-tip {
  color: #fb0000;
  font-size: 17px;
}
.materials {
  margin-top: 29px;
  font-size: 17px;
  line-height: 24px;
}
.start-attest {
  width: 138px;
  height: 44px;
  line-height: 44px;
  background: #191b5c;
  border-radius: 4px;
  color: #fff;
  font-size: 13px;
  text-align: center;
  margin: 176px auto;
}
.main-types {
  width: 116px;
  height: 38px;
  line-height: 38px;
  border-radius: 5px;
  border: 1px solid #5357cb;
  margin-top: 24px;
  color: #5357cb;
  font-size: 17px;
  text-align: center;
}
</style>
