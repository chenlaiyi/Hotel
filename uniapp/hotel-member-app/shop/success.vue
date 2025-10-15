<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="兑换成功" color="#000000" :border="false" :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="shopdetail fui-flex justify-center" style="align-items: flex-start;padding: 60rpx;">
			<view class="">
				<iconfont className="icon-a--01" :size="24" color="#FFFFFF" style="margin-top: 5rpx;"></iconfont>
			</view>
			<view class="font_size_20 color14 margin-left-xs">
				兑换成功
			</view>
		</view>
		<view class="shopdetail_con">
			<view class="card">
				<view class="card-address">
					<view class="padding-bottom-xs font_size_16 text-bold">
						<text class="padding-right-sm">{{addressObj.name}}</text> <text>{{addressObj.phone}}</text>
					</view>
					<view class="font_size_12 color13">
						{{addressObj.province_id + addressObj.city_id + addressObj.region_id + addressObj.detail}}
					</view>
				</view>
			</view>

			<view class="card" v-for="(item,index) in goodsObj.goods" :key="index">
				<view class="card-pad fui-flex justify-between">
					<view class="fui-flex">
						<view class="card-pad_border">
							<image :src="item.thumb" mode="" style="width: 100%;height:100%;border: 2rpx solid #EEEEEE;
						border-radius: 10rpx;"></image>
						</view>
						<view class="margin-left-sm">
							<view class="margin-bottom-lg font_size_16 text-bold">
								{{item.goods_name}}
							</view>
							<view class="fui-flex">
								<iconfont className="icon-a-0-01" color="#FF9500" :size="18"></iconfont>
								<view class="font_size_16 text-bold color5 margin-left-xs">
									{{item.goods_integral}}
								</view>
							</view>
						</view>
					</view>
					<view class="font_size_12 color13" style="align-self: flex-end;">
						x<text>1</text>
					</view>
				</view>
			</view>

			<view class="card">
				<view class="card-pad">
					<view class="card-pad_bottom fui-flex justify-between font_size_14">
						<view class="">
							运费
						</view>
						<view class="text-bold">
							￥<text>0</text>
						</view>
					</view>
					<view class="card-pad_bottom fui-flex justify-between font_size_14">
						<view class="">
							使用积分
						</view>
						<view class="text-bold">
							{{goodsObj.pay_integral}}
						</view>
					</view>
					<view class="card-pad_bottom fui-flex justify-between font_size_14">
						<view class="">
							订单编号
						</view>
						<view class="text-bold">
							{{goodsObj.order_no}}
						</view>
					</view>
					<view class="card-pad_bottom fui-flex justify-between font_size_14">
						<view class="">
							兑换时间
						</view>
						<view class="text-bold">
							{{goodsObj.pay_time}}
						</view>
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
				addressObj: {},
				goodsObj: {},
			};
		},
		onLoad(options) {
			let that = this
			that.getShopDetail(options.order_id)
		},
		methods: {
			// 订单详情
			getShopDetail(order_id) {
				let that = this
				that.$api.integralOrderDetail(order_id).then(res => {
					that.addressObj = res.data.address
					that.goodsObj = res.data
					console.log(res)
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		.shopdetail {
			height: 262rpx;
			background-color: #00AFC7;
			box-sizing: border-box;
		}

		.shopdetail_con {
			margin-top: -112rpx;

			.card {
				padding: 0;
				box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
				border-radius: 20rpx;
				margin-bottom: 0;

				.card-address {
					padding: 32rpx;
					box-sizing: border-box;
				}

				.card-pad {
					padding: 32rpx;

					.card-pad_border {
						width: 128rpx;
						height: 128rpx;

					}

					.card-pad_bottom {
						margin-bottom: 24rpx;
					}

					.card-pad_bottom:last-child {
						margin-bottom: 0;
					}
				}
			}
		}
	}
</style>