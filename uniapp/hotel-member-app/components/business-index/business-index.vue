<template>
	<view class="m-home">
		<image class="w100b h360" src="https://oss.ddicms.cn/member/new/bg1.png" mode=""></image>
		<view class="pofx l0 t0 w100b bsbox pl32 pr32 zindex10 h88 dsf alc jcsb box-search" 
		:class="{'active': isScroll}"
		:style="{'paddingTop': StatusBar+'px'}">
			<view class="l-bg w400 h64 bdra50 bgfff-op3 bsbox pl24 pr24 dsf alc">
				<u-icon class="l-icon" name="search" size="24" color="rgba(249,249,249,.5)"></u-icon>
				<view class="l-txt text-28 colfff op7 pl10">搜索酒店/民宿/公寓</view>
			</view>
			<view class="dsf alc">
				<u-icon class="r-icon1" name="eye" color="#fff" size="24"></u-icon>
				<u-icon class="ml28 r-icon2" name="bell" color="#fff" size="24"></u-icon>
			</view>
		</view>
		<view class="mtf60 pb80 poreIndex zindex10 bgfff bdra40">
			<view class="dsf pl32 pt32">
				<view class="w328 mr30">
					<view class="dsf overhide pore pb30" @click="goPage('hotelIndex')">
						<image class="w100b bdra20" src="https://oss.ddicms.cn/member/new/bg5.png" mode="widthFix"></image>
						<!-- <view class="poab b30 l0 pl24 pb24 pr24 w100b bsbox onehide colfff text-32">酒店</view> -->
					</view>
					<view class="dsf overhide pore pb30"  @click="goPage('apartmentIndex')">
						<image class="w100b bdra20" src="https://oss.ddicms.cn/member/new/bg7.png" mode="widthFix"></image>
						<!-- <view class="poab b30 l0 pl24 pb24 pr24 w100b bsbox onehide colfff text-32">公寓</view> -->
					</view>
					<view class="dsf overhide pore pb30"  @click="goPage('hotelIndex')">
						<image class="w100b bdra20" src="https://oss.ddicms.cn/member/new/bg9.png" mode="widthFix"></image>
						<!-- <view class="poab b30 l0 pl24 pb24 pr24 w100b bsbox onehide colfff text-32">客栈</view> -->
					</view>
				</view>
				<view class="w328">
					<view class="dsf overhide pore pb30" @click="goPage('homestayIndex')">
						<image class="w100b bdra20" src="https://oss.ddicms.cn/member/new/bg6.png" mode="widthFix"></image>
						<!-- <view class="poab b30 l0 pl24 pb24 pr24 w100b bsbox onehide colfff text-32">民宿</view> -->
					</view>
					<view class="dsf overhide pore pb30"  @click="goPage('hotelIndex')">
						<image class="w100b bdra20" src="https://oss.ddicms.cn/member/new/bg8.png" mode="widthFix"></image>
						<!-- <view class="poab b30 l0 pl24 pb24 pr24 w100b bsbox onehide colfff text-32">办公室</view> -->
					</view>
					<view class="dsf overhide pore pb30"  @click="goPage('hotelIndex')">
						<image class="w100b bdra20" src="https://oss.ddicms.cn/member/new/bg10.png" mode="widthFix"></image>
						<!-- <view class="poab b30 l0 pl24 pb24 pr24 w100b bsbox onehide colfff text-32">茶室</view> -->
					</view>
				</view>
			</view>
		</view>
		<tab-bar :currentindex="0"></tab-bar>
	</view>
</template>

<script>
	export default{
		name: 'businessIndex',
		data(){
			return {
				systemInfo: this.systemInfo,
				StatusBar: 0,
				isScroll: false,
				businessType: 1
			}
		},
		onPageScroll(e) {
			if(e.scrollTop > 10 && !this.isScroll){
				this.isScroll = true
			}else if(e.scrollTop <= 10 && this.isScroll){
				this.isScroll = false
			}
			console.log(e.scrollTop, this.isScroll)
		},
		onLoad() {
			this.systemInfo = uni.getSystemInfoSync()
			this.StatusBar = this.systemInfo.statusBarHeight
			// if(this.businessType === 1){
			// 	this.goPage('hotelIndex')
			// }
		},
		methods:{
			goPage(name,params){
				this.$Router.push({
					name:name,
					params:params
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.m-home{
		min-height: 100vh;
		background-color: #fff;
	}
	.box-search{
		&.active{
			background-color: #fff;
			.l-bg{
				background-color: #f9f9f9;
				.l-icon{
					/deep/{
						.u-icon__icon{
							color: #333!important;
						}
					}
				}
				.l-txt{
					color: #999;
				}
			}
			.r-icon1,.r-icon2{
				/deep/{
					.u-icon__icon{
						color: #333!important;
					}
				}
			}
		}
	}
	.poreIndex{
		position: inherit;
	}
</style>