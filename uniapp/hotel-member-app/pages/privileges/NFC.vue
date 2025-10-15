<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="NFC" color="#000000" :border="false"  fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="nfc fui-flex flex-direction justify-center">
			<view class="nfc_img">
				<image src="https://oss.ddicms.cn/member/privileges/ele_nfc.png" mode="" style="width: 240rpx;height: 624rpx;">
				</image>
			</view>
			<view class="nfc_txt">
				请确保手机已开启NFC
			</view>
			<view class="">
				<fui-circular-progress :diam="77" :percentage="30" fontColor="#00AFC7" percentText="开锁中" defaultColor="#F5F5F5"
					progressColor="#00AFC7"></fui-circular-progress>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {

			};
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		.nfc {
			margin-top: 136rpx;

			.nfc_txt {
				margin: 80rpx 0 144rpx;
			}
		}
	}
</style>
