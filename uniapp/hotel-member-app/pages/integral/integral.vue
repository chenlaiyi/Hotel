<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="我的积分" color="#000000" :border="false"  fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<!-- 背景图 -->
		<view class="int-top">
			<view class="fui-flex justify-between int-top-jf">
				<view class="">
					<view class="">积分</view>
					<view class="int-top-jf_num">190999</view>
				</view>
				<view class="">
					<button class="serve-btn" @click="goShop">积分商城</button>
				</view>
			</view>
			<view class="card int-bottom">
				<view class="fui-flex justify-center">
					<view class="padding-bottom-xs">
						<image src="https://oss.ddicms.cn/member/tb_left.png" mode="" style="width: 16rpx;height: 32rpx;"></image>
					</view>
					<view class="int-bottom_title">积分明细</view>
					<view class="">
						<image src="https://oss.ddicms.cn/member/tb_right.png" mode=""
							style="width: 16rpx;height: 32rpx;margin-top: 31rpx;"></image>
					</view>
				</view>
				<view v-for="i in 12" :key="i" class="inte_list margin-lr-sm fui-flex align-center justify-between">
					<view class="">
						<view class="text-lg color9">签到</view>
						<view class="color2 text-sm padding-top-xs">2022-09-03 12:12:12</view>
					</view>
					<view class="text-lg">+10</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
			
			};
		},
		onLoad() {
			
		},
		onShow() {
			let that = this;
		},
		methods: {
			// goPrepaid() {
			// 	let that = this;
			// 	that.$Router.push({
			// 		name: 'recharge'
			// 	});
			// },
			goShop() {
				let that = this;
				that.$Router.push({
					name: 'myshop'
				});
			}
			
		}
	};
</script>

<style lang="scss" scoped>
	.int-top {
		width: 100%;
		height: 334rpx;
		background: url('https://oss.ddicms.cn/member/jifen_bg.png') no-repeat center bottom;
		background-size: 100%;
	}

	.int-top-jf {
		margin: 40rpx 32rpx 48rpx;
		color: #fff;
		font-size: 24rpx;
	}

	.serve-btn {
		width: 160rpx;
		height: 56rpx;
		line-height: 56rpx;
		border-radius: 28rpx;
		font-size: 24rpx;
		color: #00AFC7;
	}

	.int-top-jf_num {
		font-size: 64rpx;
		font-weight: 600;
		margin-top: 3rpx;
	}

	.int-bottom_title {
		font-size: 30rpx;
		color: #000;
		font-weight: 600;
	}

	.inte_list {
		border-bottom: 2rpx solid #eeeeee;
		padding: 16rpx 0;
	}
</style>
