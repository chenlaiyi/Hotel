<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="支付订单" color="#000000" :border="false"  fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="card" style="header">
			<view class="" style="text-align: center;color: #999999;">
				实付金额
			</view>
			<view class="" style="text-align: center;font-size: 80rpx;font-weight: bold;">
				<text style="text-align: center;font-size: 40rpx;font-weight: bold;">¥</text>{{orderDetail.real_pay}}
			</view>
			<view class="" style="text-align: center;">
				剩余支付时间
				<u-count-down :time="5 * 60 * 60 * 1000" format="HH:mm:ss"></u-count-down>
			</view>
		</view>
		<view class="card main" style="padding-bottom: 0;">
			<view class="main_item">
				<u-radio-group v-model="pay_type" :borderBottom="true" placement="column" iconPlacement="right">
					<u-radio class="pay-item" :customStyle="{marginBottom: '16px'}" v-for="(item, index) in payList"
						:key="index" :label="item.text" :name="item.val" activeColor="#00AFC7">
						<view class="fui-flex">
							<view class="margin-right-xs padding-top-xs">
								<image :src="item.url" mode="" style="width: 40rpx;height: 40rpx;"></image>
							</view>
							<view class="">
								{{item.text}}
							</view>
						</view>
					</u-radio>
				</u-radio-group>
			</view>
		</view>
		<view class="bottom_btn" @click="sureBtn">
			确认支付
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				radioIndex: 0,
				payList: [{
						val: 1,
						url: '/static/svgs/wechat.svg',
						text: '微信支付'
					},
					{
						val: 2,
						url: '/static/svgs/balance.svg',
						text: '余额支付'
					},
					{
						val: 3,
						url: '/static/svgs/pay_icon2.svg',
						text: 'senangpay'
					},
				],
				order_id: 0,
				pay_type: 1,
				orderDetail: {},
				msg_template:[]
			}
		},
		onLoad() {
			let that = this
			console.log('order_id', that.$Route.params)
			that.order_id = that.$Route.query.order_id
			that.initOrder(that.order_id)
		},
		methods: {
			initOrder(order_id) {
				let that = this
				that.$api.hotelMobileOrderDetail(that.order_id).then(res => {
					that.orderDetail = res.data
					console.log(res)
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					});
				})
			},
			radioFn(i) {
				this.radioIndex = i
			},
			// 支付订单接口
			orderauth() {
				let that = this
				that.$api.hotelWoAuthdevice(that.id).then(res => {
					// that.$Router.push({
					// 	name: 'myorder'
					// })
					console.log(res)
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					});
				})
			},
			orderpay() {
				let that = this
				console.log('pay_type', this.pay_type);
				that.$api.hotelMobileOrderPay(that.id).then(res => {
					console.log(res)
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			sureBtn() {
				let that = this
				console.log('pay_type', this.pay_type);
				switch (this.pay_type){
					case 1: //微信支付
					 this.fui.wechatpay(that.orderDetail.body, that.orderDetail.order_number, that.orderDetail.amount_payable, that.orderDetail.id, (res)=>{
						 console.log('支付成功',res);
					 })
					break;
					case 2: //余额支付
						this.balancePay()
					break;
					case 3: //跨境支付
					break;
				}
			},
			//余额支付
			balancePay() {
				let that = this;
				// #ifdef MP-WEIXIN
				that.fui
					.request(
						'diandi_hotel/mobile/order/balancePay',
						'POST',
						{
							order_number: that.orderDetail.order_number,
							pay_price: that.orderDetail.amount_payable
						},
						true
					)
					.then(res => {
						if (res.code === 200) {
							// uni.requestSubscribeMessage({
							// 	tmplIds: that.msg_template,
							// 	success(res) {
								
							// 	},
							// 	fail(res){
							// 		uni.showToast({
							// 			title: res.errMsg,
							// 			icon: 'none'
							// 		});
							// 	}
							// });
							that.$Router.push({
								name:'myorderPaySuccess',
								params:{
									order_id: that.orderDetail.id
								}
							})
						} else {
							uni.showToast({
								title: res.message,
								icon: 'none'
							});
						}
					})
					.catch(res => {
						console.log('shibai', res);
						uni.showToast({
							title: res.message,
							icon: 'none'
						});
					});
				
				// #endif
				
				// #ifdef APP || H5
				that.fui
					.request(
						'diandi_hotel/mobile/order/balancePay',
						'POST',
						{
							order_number: that.orderDetail.order_number,
							pay_price: that.orderDetail.amount_payable
						},
						true
					)
					.then(res => {
						if (res.code === 200) {
							that.$Router.push({
								name:'myorderPaySuccess',
								params:{
									order_id: that.orderDetail.id
								}
							})
						} else {
							uni.showToast({
								title: res.message,
								icon: 'none'
							});
						}
					})
					.catch(res => {
						console.log('shibai', res);
						uni.showToast({
							title: res.message,
							icon: 'none'
						});
					});
				// #endif
			}
		}
	}
</script>

<style>
	.card {
		margin-bottom: 0;
		box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
	}

	.pay-item {
		border-bottom: 1px solid #EEEEEE;
	}

	.header {
		padding: 48rpx;
	}

	.left_title {
		font-size: 30rpx;
		font-weight: 600;
		color: #000000;
	}

	.main_item {
		border-bottom: 1rpx solid #EEEEEE;
	}

	.right_ {
		width: 36rpx;
		height: 36rpx;
		border-radius: 50%;
		border: 2rpx solid #CCCCCC;
	}

	.right_pic {
		width: 40rpx;
		height: 40rpx;
		border: 2rpx solid transparent !important;
		background: url('https://oss.ddicms.cn/member/answer.png') no-repeat center;
		background-size: contain;
	}

	.bottom_btn {
		margin: 0 auto;
		margin-top: 56rpx;
		width: 686rpx;
		height: 80rpx;
		line-height: 80rpx;
		text-align: center;
		background: #00AFC7;
		border-radius: 10rpx;
		color: #fff;
	}
</style>