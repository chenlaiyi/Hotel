<template>
	<view class="m-auth container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="智能入住" color="#000000" :border="false"  fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="h264 bg00AFC7" :style="{'paddingTop': StatusBar + 'px'}"></view>
		<view class="pofx l0 zindex10 w100b bg00AFC7 h88 dsf alc jcc text-36 colfff" :style="{'top': StatusBar + 'px'}">我的权限</view>
		<view class="pl30 pr30 pb32">
			<view class="bdra20 bgfff pl32 pr32 pt32 pb32 shadow mb32 mtf160">
				<view class="lh50 text-36 col000 fwb onehide">四季酒店（高新路店）</view>
				<view class="lh38 pt28 text-26 col999">
					<view>订单编号：204567895433212</view>
					<view class="pt10">入住时间：2023-05-30</view>
					<view class="pt10">退房时间：2023-06-30</view>
				</view>
			</view>
			<view class="bgfff pl32 pr32 pt32 pb16 bdra20 shadow mb32">
				<view class="lh50 text-36 col000 fwb onehide pb24">门禁卡</view>
				<view class="dsf alc pt30 pb30 pl32 pr32 bgFEF8EA bdra20 mb16">
					<view class="w80 h80 bdra50b bgFBBB32">
						<image class="w100b h100b" src="" mode=""></image>
					</view>
					<view class="fg1 pl24 pr24 bsbox">
						<view class="lh42 text-30 col000">大门门禁</view>
						<view class="lh33 text-24 col999 mt6">A栋</view>
					</view>
					<view class="dsf alc">
						<view class="text-32 col000 fwb pr14">12345678</view>
						<u-icon name="eye" color="#999" size="20"></u-icon>
					</view>
				</view>
				<view class="dsf alc pt30 pb30 pl32 pr32 bgEAF1FE bdra20 mb16">
					<view class="w80 h80 bdra50b bg3276FC">
						<image class="w100b h100b" src="" mode=""></image>
					</view>
					<view class="fg1 pl24 pr24 bsbox">
						<view class="lh42 text-30 col000">楼层门禁</view>
						<view class="lh33 text-24 col999 mt6">A栋-101层</view>
					</view>
					<view class="dsf alc">
						<view class="text-32 col000 fwb pr14">**** ****</view>
						<u-icon name="eye-off" color="#999" size="20"></u-icon>
					</view>
				</view>
				<view class="dsf alc pt30 pb30 pl32 pr32 bgF0FBDE bdra20 mb16">
					<view class="w80 h80 bdra50b bgB9D787">
						<image class="w100b h100b" src="" mode=""></image>
					</view>
					<view class="fg1 pl24 pr24 bsbox">
						<view class="lh42 text-30 col000">房间门禁</view>
						<view class="lh33 text-24 col999 mt6">A栋-101层-1101室</view>
					</view>
					<view class="dsf alc">
						<view class="text-32 col000 fwb pr14">**** ****</view>
						<u-icon name="eye-off" color="#999" size="20"></u-icon>
					</view>
				</view>
			</view>
			<view class="bgfff pl32 pr32 pt32 pb16 bdra20 shadow mb32">
				<view class="lh50 text-36 col000 fwb onehide pb24">开关</view>
				<view class="dsf alc pt30 pb30 pl32 pr32 bgf9 bdra20 mb16">
					<view class="w72 h72 dsf alc jcc overhide">
						<image class="w100b h100b" src="https://oss.ddicms.cn/member/new/bg.jpg" mode=""></image>
					</view>
					<view class="fg1 pl24 pr24 bsbox">
						<view class="lh42 text-30 col000">房间门禁</view>
						<view class="lh33 text-24 col999 mt6">A栋-101层-1101室</view>
					</view>
					<view class="dsf alc">
						<image class="w26 h56 mr6" src="https://oss.ddicms.cn/member/new/bg.jpg" mode=""></image>
						<image class="w26 h56" src="https://oss.ddicms.cn/member/new/bg.jpg" mode=""></image>
					</view>
				</view>
				<view class="dsf alc pt30 pb30 pl32 pr32 bgf9 bdra20 mb16">
					<view class="w72 h72 dsf alc jcc overhide">
						<image class="w100b h100b" src="https://oss.ddicms.cn/member/new/bg.jpg" mode=""></image>
					</view>
					<view class="fg1 pl24 pr24 bsbox">
						<view class="lh42 text-30 col000">房间门禁</view>
						<view class="lh33 text-24 col999 mt6">A栋-101层-1101室</view>
					</view>
					<view class="dsf alc">
						<image class="w26 h56 mr6" src="https://oss.ddicms.cn/member/new/bg.jpg" mode=""></image>
						<image class="w26 h56" src="https://oss.ddicms.cn/member/new/bg.jpg" mode=""></image>
					</view>
				</view>
			</view>
			<view class="bgfff pl32 pr32 pt32 pb16 bdra20 shadow mb32">
				<view class="lh50 text-36 col000 fwb onehide pb24">电表</view>
				<view class="dsf alc pt30 pb30 pl32 pr32 bgf9 bdra20 mb16">
					<view class="w72 h72 dsf alc jcc overhide">
						<image class="w100b h100b" src="https://oss.ddicms.cn/member/new/bg.jpg" mode=""></image>
					</view>
					<view class="fg1 pl24 bsbox">
						<view class="lh42 text-30 col000">房间门禁</view>
						<view class="lh33 text-24 col999 mt6">A栋-101层-1101室</view>
					</view>
				</view>
			</view>
			<view class="bgfff pl32 pr32 pt32 pb32 bdra20 shadow">
				<view class="h50 dsf alc jcsb">
					<view class="text-36 col000 fwb">我的协议</view>
					<u-icon name="arrow-right" color="#999" size="16"></u-icon>
				</view>
			</view>
		</view>
		<view class="pofx z-index10 l0 w100b h88 dsf alc jcc bgEBF9FB text-30 col00AFC7 hover-btn" style="bottom: 100rpx;" @click="goPage('orderSelect')">切换订单</view>
		<tab-bar :currentindex="3"></tab-bar>
	</view>
</template>

<script>
	export default{
		data(){
			return {
				systemInfo: this.systemInfo,
				StatusBar: 0,
				isScroll: false,
				order_id:0,
				deviceList:[]
			}
		},
		onPageScroll(e) {
			if(e.scrollTop > 10 && !this.isScroll){
				this.isScroll = true
			}else if(e.scrollTop <= 10 && this.isScroll){
				this.isScroll = false
			}
		},
		onLoad() {
			this.systemInfo = uni.getSystemInfoSync()
			this.StatusBar = this.systemInfo.statusBarHeight
		},
		onShow() {
			this.order_id = this.$route.query.order_id
			this.getDevice(this.order_id)
		},
		methods:{			
			goPage(name,params){
				this.$Router.push({
					name:name,
					params:params
				})
			},
			getDevice(order_id){
				this.$api.hotelDeviceByorder(order_id).then(res=>{
					this.deviceList = res.data
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.m-auth{
		min-height: 100vh;
		box-sizing: border-box;
		padding-bottom: calc(constant(safe-area-inset-bottom) + 88rpx + 100rpx);
		padding-bottom: calc(env(safe-area-inset-bottom) + 88rpx + 100rpx);
		background-color: #fff;
	}
	.shadow{
		box-shadow: 0px 5px 20px 1px rgba(204,204,204,0.3);
	}
</style>