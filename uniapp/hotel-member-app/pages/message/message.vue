<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="消息通知" color="#000000" :border="false"  fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="" v-for="(item,index) in 5" :key="index">
			<view class="message">
				2023-03-03 12:23
			</view>
			<view class="card">
				<view class="fui-flex message_flex">
					<view class="message_flex_icon margin-right-sm">
						<iconfont className="icon-a-_huaban1" :size="18" color="#FFFFFF"></iconfont>
					</view>
					<view class="message_flex_title">
						系统通知
					</view>
				</view>
				<view class="message_con">
					智能锁作为“门面”，出色的外观颜值，更符合用户的需求。十大标准中的高颜值，是指“优秀的外观设计+先进的制造工艺+精湛的表面处理”，高颜值不仅是好看，还意味着历久弥新，
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
			let that = this
		},
		methods: {
			// 获取消息列表
			getMessageList() {
				let that = this
				
			}
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		.message {
			margin: 32rpx auto;
			width: 240rpx;
			height: 40rpx;
			line-height: 40rpx;
			background-color: #F9F9F9;
			border-radius: 22rpx;
			font-size: 24rpx;
			color: #999999;
			text-align: center;
		}

		.card {
			margin: 0 32rpx 16rpx;
			box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(25, 27, 92, 0.1);

			.message_flex {
				position: relative;
				padding-bottom: 26rpx;
				border-bottom: 1rpx solid #EEEEEE;

				.message_flex_icon {
					width: 56rpx;
					height: 56rpx;
					line-height: 56rpx;
					border-radius: 50%;
					background-color: #00AFC7;
					text-align: center;
				}

				.message_flex_title {
					font-size: 36rpx;
					font-weight: bold;
				}
			}

			.message_flex::before {
				content: "";
				position: absolute;
				left: 40rpx;
				top: 0;
				width: 16rpx;
				height: 16rpx;
				background-color: #FF3F57;
				border-radius: 50%;
			}

			.message_con {
				line-height: 44rpx;
				margin-top: 24rpx;
				font-size: 28rpx;
				color: #999999;
			}

		}
	}
</style>

