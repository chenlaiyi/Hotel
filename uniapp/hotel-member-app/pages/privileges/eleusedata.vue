<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="用电数据" color="#000000" :border="false"  fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="eleuse">
			<view class="card fui-flex flex-direction justify-center align-center">
				<view class="">
					<!-- <iconfont className="icon-a--07"></iconfont> -->
					<i class="iconfont icon-light" style="font-size: 70rpx;color: #FF9500;"></i>
				</view>
				<view class="margin-tb-sm font_size_14">
					总用电（kwh）
				</view>
				<view class="eleuse_num">
					68
				</view>
			</view>
			<view class="eleuse_title text-bold">
				每月用电记录
			</view>
			<view class="card eleuse_list">
				<view class="eleuse_list_pad fui-flex justify-between" v-for="(item,index) in 3" :key="index">
					<view class="color13 text-24">
						2023年4月
					</view>
					<view class="text-28 text-bold">
						3.23kwh
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

			};
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		margin: 0 32rpx 0;

		.eleuse {
			.card {
				margin: 32rpx 0 0;
				box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(204, 204, 204, 0.3);
				
				.eleuse_num {
					font-size: 200rpx;
				}
			}
			.eleuse_title {
				margin: 48rpx 0 24rpx;
				
			}
			.eleuse_list {
				padding: 0 32rpx;
				.eleuse_list_pad {
					padding: 25rpx 0;
					border-bottom: 2rpx solid #F5F5F5;
				}
			}
		}
	}
</style>
