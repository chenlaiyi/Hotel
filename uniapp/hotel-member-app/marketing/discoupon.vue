<template>
	<view class="container">
		<view class="coupon_list fui-flex justify-between text-sm" v-for="i in 6" :key="i">
			<view class="margin-left-lg">
				<view class="fui-flex">
					<image class="left_img" src="https://oss.ddicms.cn/member/jd.png" mode="widthFix">
					</image>
					<view class="text-df">酒店代金券</view>
				</view>
				<view class="margin-tb-sm color2">有效期至2023-01-05 15：49</view>
				<view class="fui-flex color2">
					使用说明
					<image class="margin-left-xs" style="width: 10rpx;"
						src="https://oss.ddicms.cn/member/my14.png" mode="widthFix"></image>
				</view> 
			</view>
			<view class="text-white text-xl text-center fui-col-4">￥678</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {};
		},
		methods: {}
	};
</script>

<style>
	.coupon_list {
		height: 190rpx;
		margin: 30rpx 38rpx 0;
		background-image: url('https://oss.ddicms.cn/member/discouponbj.png');
		background-size: 100%;
	}

	.left_img {
		width: 30rpx;
		height: 30rpx;
		margin: 0 34rpx;
	}
</style>
