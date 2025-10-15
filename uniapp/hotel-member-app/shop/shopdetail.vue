<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar :auto-back="true" :fixed="true" title="商品详情"></u-navbar>
		<!-- #endif -->
		<view class="header_swiper">
			<swiper class="swiper" circular :indicator-dots="indicatorDots" :autoplay="autoplay" :interval="interval"
				:duration="duration" indicator-color='#D1D1D1' indicator-active-color='#00AFC7'>
				<swiper-item v-for="(item,i) in itemDetails.slides" :key="i">
					<view class="swiper-item uni-bg-red">
						<image :src="item.url" mode=""></image>
					</view>
				</swiper-item>
			</swiper>
		</view>
		<view class="main">
			<view class="main_top">
				<view class="main_top_top">
					<view class="main_top_left">
						<text
							style="color: #00AFC7;font-size: 48rpx;margin-right: 8rpx;">¥{{itemDetails.goods_price}}</text>
						<text style="color: #999999;font-size: 24rpx">¥{{itemDetails.line_price}}</text>
					</view>
					<view class="main_top_right">
						销量:{{itemDetails.sales_initial}}
					</view>
				</view>
				<view class="main_top_bottom">
					{{itemDetails.goods_name}}
				</view>
			</view>
			<view class="main_bottom" v-html="itemDetails.content">

			</view>
		</view>

		<view class="bottom">
			<view class="bottom_left">
				<uni-icons type="phone-filled" size="20" style="margin-right: 10rpx;"></uni-icons>
				<text>联系商家</text>
			</view>
			<view class="bottom_right" @click="goBuy">
				立即兑换
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				// 展示
				indicatorDots: true,
				autoplay: true,
				interval: 2000,
				duration: 500,
				// 数据
				itemDetails: {}
			};
		},
		onLoad() {
			this.getItemDetailsFn()
		},
		methods: {
			getItemDetailsFn() {
				let that = this
				that.$api.integralGoodsDetail(that.$Route.query.goods_id).then(res => {
					that.itemDetails = res.data.goods
					console.log(that.itemDetails, 'sssssss');
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			goBuy() {
				this.$Router.push({
					name: 'verifyOrder',
					params: {
						goods_id: this.itemDetails.goods_id
					}
				})
			}
		}
	}
</script>

<style lang="scss">
	.container {
		padding-bottom: 120rpx;

		.header_swiper {
			margin-bottom: 32rpx;
			height: 500rpx;

			.swiper {
				height: 100%;

				.swiper-item {
					height: 100%;
				}
			}
		}

		.main {
			padding: 0 32rpx;

			.main_top {
				padding: 24rpx 32rpx 40rpx;
				box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
				border-radius: 20rpx 20rpx 20rpx 20rpx;

				.main_top_top {
					display: flex;
					justify-content: space-between;
					align-items: flex-end;

					.main_top_left {}

					.main_top_right {
						font-size: 24rpx;
						font-weight: 300;
						color: #999999;
					}
				}

				.main_top_bottom {
					margin-top: 12rpx;
					font-size: 32rpx;
					font-weight: 600;
					color: #000000;
				}
			}

			.main_middle {
				margin-top: 32rpx;
				margin-bottom: 32rpx;
				box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
				border-radius: 20rpx 20rpx 20rpx 20rpx;
				padding: 32rpx;

				.main_middle_title {
					font-size: 32rpx;
					font-weight: 600;
					color: #000000;
					margin-bottom: 32rpx
				}

				.scroll-view_H {
					white-space: nowrap;
					width: 100%;

					.scroll-view-item_H {
						display: inline-block;
						width: 184rpx;
						border-radius: 10rpx 10rpx 10rpx 10rpx;
						margin-right: 16rpx;

						.scroll-view-item_H_pic {
							height: 184rpx;
							background-color: pink;
						}

						.scroll-view-item_H_pic_msg {
							white-space: normal;
							margin-top: 8rpx;
							margin-bottom: 12rpx;
							font-size: 26rpx;
							font-weight: 400;
							color: #000000;
						}

						.scroll-view-item_H_pic_money {
							font-size: 26rpx;
							font-weight: 600;
							color: #00AFC7;
						}
					}
				}

			}

			.main_bottom {
				margin-top: 32rpx;

				image {
					width: 100%;
					height: 100%;
				}
			}
		}

		.bottom {
			position: fixed;
			bottom: 0;
			left: 0;
			width: 100vw;
			height: 100rpx;
			display: flex;
			background-color: #fff;
			z-index: 9999;

			.bottom_left {
				width: 270rpx;
				display: flex;
				justify-content: center;
				align-items: center;
			}

			.bottom_right {
				flex: 1;
				background-color: #00AFC7;
				display: flex;
				justify-content: center;
				align-items: center;
				color: #fff;
			}
		}
	}
</style>