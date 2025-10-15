<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="我的发票" color="#000000" :border="false"  :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="card" v-for="(item,index) in 3" :key="index">
			<view class="card_top fui-flex justify-between">
				<view class="fui-flex">
					<view class="card_top_bor color4">
						电子
					</view>
					<view class="font_size_12 margin-left-xs">
						2023-04-20 11:00:56
					</view>
				</view>
				<view class="font_size_12 color4 text-bold">
					已开票
				</view>
			</view>
			<view class="card_text">
				<view class="font_size_14 text-bold padding-bottom-xs">
					西安店滴云网络科技有限公司
				</view>
				<view class="fui-flex font_size_12 color13 padding-bottom-xs">
					<view class="fui-col-6">
						发票代码
					</view>
					<view class="fui-col-6">
						发票号码
					</view>
				</view>
				<view class="fui-flex font_size_12 padding-bottom-sm">
					<view class="fui-col-6">
						044031800311
					</view>
					<view class="fui-col-6">
						044031800311
					</view>
				</view>
				<view class="card_text_bottom fui-flex justify-between">
					<view class="font_size_12 color13">
						价税合计：<text class="font_size_16 color6 text-bold">¥200</text>
					</view>
					<view class="">
						<button class="serve-btn font_size_12 color13">查看详情</button>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {

			}
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		.card {
			padding: 0;
			box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
			margin-bottom: 0;

			.card_top {
				padding: 24rpx 32rpx;
				background-color: #EBF9FB;

				.card_top_bor {
					padding: 2rpx 8rpx;
					border-radius: 5rpx;
					border: 1rpx solid #00AFC7;
					font-size: 20rpx;
					-webkit-transform: scale(0.8);
				}
			}
			
			.card_text {
				padding: 24rpx 32rpx 27rpx;
				.card_text_bottom {
					padding-top: 28rpx;
					border-top: 2rpx solid #F5F5F5;
					
					.serve-btn {
						width: 158rpx;
						height: 56rpx;
						line-height: 50rpx;
						background-color: transparent;
						border: 2rpx solid #EEEEEE;
					}
				}
			}
		}
	}
</style>
