<template>
	<view class="container">
		<business-index v-if="wxapp_index_type === 1"></business-index>
		<hotel-index :topImg="topImg"  v-if="wxapp_index_type === 2"></hotel-index>
		<apartment-index   :topImg="topImg" v-if="wxapp_index_type === 3"></apartment-index>
		<homestay-list  :topImg="topImg" v-if="wxapp_index_type === 4"></homestay-list>
		<tab-bar :currentindex="0"></tab-bar>
	</view>
</template>

<script>
	export default{
		data(){
			return {
				wxapp_index_type: null,
				topImg: ''
			}
		},
		onLoad() {
			this.$api.hotelMobileIndexConfig().then(res=>{
				if(res.data){
					this.wxapp_index_type = res.data.wxapp_index_type ?? 1
					this.topImg = res.data.index_thumb
				}else{
					this.wxapp_index_type = 1
				}
			})
		}
	}
</script>