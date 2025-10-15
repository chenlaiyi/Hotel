<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="确认订单" color="#000000" :border="false" :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="card" @click="addressAdd">
			<view class="card-address fui-flex justify-between align-center"
				v-if="JSON.stringify(selectedAddress) === '{}'">
				<view class="font_size_16 text-bold">
					请添加收件地址
				</view>
				<view class="">
					<iconfont className="icon-youjiantou1" :size="14" color="#999999"></iconfont>
				</view>
			</view>
			<view class="card-address fui-flex justify-between align-center" v-else>
				<view class="">
					<view class="padding-bottom-xs font_size_16 text-bold">
						<text class="padding-right-sm">{{selectedAddress.username}}</text>
						<text>{{selectedAddress.mobile}}</text>
					</view>
					<view class="card-address_txt font_size_12 color13">
						{{selectedAddress.address}}
					</view>
				</view>
				<view class="">
					<iconfont className="icon-youjiantou1" :size="14" color="#999999"></iconfont>
				</view>
			</view>
			<view class="">
				<image src="https://oss.ddicms.cn/member/fixed/address.png" mode=""
					style="width: 100%;height: 6rpx;display: block;">
				</image>
			</view>
		</view>

		<view class="card">
			<view class="card-pad fui-flex justify-between">
				<view class="fui-flex">
					<view class="card-pad_border">
						<image :src="goodsObj.thumb" mode=""
							style="width: 100%;height:100%;border: 2rpx solid #EEEEEE;border-radius: 10rpx;"></image>
					</view>
					<view class="margin-left-sm">
						<view class="margin-bottom-lg font_size_16 text-bold">
							{{goodsObj.goods_name}}
						</view>
						<view class="fui-flex">
							<iconfont className="icon-a-0-01" color="#FF9500" :size="18"></iconfont>
							<view class="font_size_16 text-bold color5 margin-left-xs">
								{{goodsObj.goods_integral}}
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
				<view class="fui-flex justify-between font_size_14 margin-bottom-sm">
					<view class="">
						运费
					</view>
					<view class="text-bold">
						￥<text>0</text>
					</view>
				</view>
				<view class="fui-flex justify-between font_size_14">
					<view class="">
						使用积分
					</view>
					<view class="text-bold">
						{{goodsObj.goods_integral}}
					</view>
				</view>
			</view>
		</view>

		<view class="card">
			<view class="card-pad">
				<view class="fui-flex justify-between font_size_14">
					<view class="">
						支付方式
					</view>
					<view class="text-bold">
						积分兑换
					</view>
				</view>
			</view>
		</view>

		<view class="card-bottom">
			<view class="fui-flex justify-between">
				<view class="card-bottom_left font_size_14">
					使用积分：<text class="text-bold color4 font_size_16">{{goodsObj.goods_integral}}</text>
				</view>
				<view class="card-bottom_right" @click="verifyBtn">
					确认支付
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				selectedAddress: {}, //收货地址
				goodsObj: {}, //商品信息
			};
		},
		onLoad(options) {
			let that = this
			that.addressDefault()
			that.getGoodsDetail(options.goods_id)
		},
		onShow() {
			let that = this
			// 监听地址信息变化的全局事件
			uni.$on('addressSelected', (address) => {
				that.selectedAddress = address
			})
		},
		methods: {
			// 获取详情
			getGoodsDetail(goods_id) {
				let that = this
				that.$api.integralGoodsDetail(goods_id).then(res => {
					that.goodsObj = res.data.goods
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			// 获取默认地址
			addressDefault() {
				let that = this
				that.$api.integralAddressGetdefault().then(res => {
					that.selectedAddress = {
						address_id: res.data.address_id,
						username: res.data.name,
						mobile: res.data.phone,
						address: res.data.province_id + res.data.city_id + res.data.region_id + res.data.detail
					}
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			addressAdd() {
				let that = this
				that.$Router.push({
					name: 'address',
					params: {
						selectedAddress: encodeURIComponent(JSON.stringify(that.selectedAddress))
					}
				})
			},
			verifyBtn() {
				let that = this

				that.$api.integralOrderCreategoodsorder(that.goodsObj.goods_id, that.goodsObj.goods_price, 0, 1, that
					.selectedAddress.address_id, that.selectedAddress.username, that.selectedAddress.mobile, that
					.selectedAddress.address, 1, 1, '', '', '').then(res => {
					console.log(res, '9999999999999')
					if (res.code === 200) {
						uni.showToast({
							title: '支付成功',
							icon: 'none'
						})
						that.$Router.push({
							name: 'shopsuccess',
							params: {
								order_id: res.data.order_id
							}
						})

					}
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})

			},
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		.card {
			padding: 0;
			box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
			border-radius: 20rpx;
			margin-bottom: 0;

			.card-address {
				height: 146rpx;
				padding: 0 32rpx;
				box-sizing: border-box;

				.card-address_txt {
					width: 580rpx;
					white-space: nowrap;
					overflow: hidden;
					text-overflow: ellipsis;
				}
			}

			.card-pad {
				padding: 32rpx;

				.card-pad_border {
					width: 128rpx;
					height: 128rpx;
				}
			}
		}

		.card-bottom {
			position: fixed;
			left: 0;
			bottom: 0;
			width: 100%;
			height: 100rpx;

			.card-bottom_left {
				flex: 1;
				height: 100rpx;
				line-height: 100rpx;
				padding-left: 32rpx;
				border-top: 2rpx solid #EEEEEE;
			}

			.card-bottom_right {
				width: 320rpx;
				height: 100rpx;
				line-height: 100rpx;
				text-align: center;
				font-size: 28rpx;
				color: #FFFFFF;
				background-color: #00AFC7;
			}
		}
	}
</style>