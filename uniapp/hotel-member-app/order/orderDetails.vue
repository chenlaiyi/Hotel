<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="订单详情" color="#000000" :border="false" fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="header fui-flex justify-between">
			<view class="">
				<view class="header_status">{{orderDetailObj.status_label}}</view>
				<view class="header_title">{{orderDetailObj.body}}</view>
				<view class="header_num">订单编号：{{orderDetailObj.order_number}}</view>
			</view>
		</view>
		<view class="card card_detail fui-flex justify-between">
			<view class="card_detail_pay">
				{{orderDetailObj.pay_type_str}} <text class="card_detail_m padding-left-xs">¥</text><text
					class="card_detail_n">{{orderDetailObj.real_pay}}</text>
			</view>
			<view class="fui-flex card_detail_fees" @click="openPayDetailPopup">
				<view class="padding-right-xs">
					费用明细
				</view>
				<view class="">
					<i class="iconfont fuiyoujiantoucu" style="font-size: 18rpx;color: #999999;"></i>
				</view>
			</view>
		</view>
		<view class="card card_detail">
			<view class="card_img_title">
				【{{orderDetailObj.hotel_type}}】{{orderRoom.title}}
			</view>
			<view class="fui-flex order-room">
				<view class="margin-right-sm">
					<image :src="orderHotel.thumb" mode="" style="width: 168rpx;height: 168rpx;border-radius: 4rpx;">
					</image>
				</view>
				<view class="room-desc-main">
					<view class="room-desc">
						<view class="room-desc-title">
							地址
						</view>
						<view class="room-desc-tag">
							{{orderRoom.address}}
						</view>
					</view>
					<view class="room-desc">
						<view class="room-desc-title">
							房型
						</view>
						<view class="room-desc-tag">
							{{orderDetailObj.hotel_type}}
						</view>
					</view>
					<view class="room-desc">
						<view class="room-desc-title">
							楼层
						</view>
						<view class="room-desc-tag">
							{{orderDetailObj.tier_title}}
						</view>
					</view>
					<view class="room-desc">
						<view class="room-desc-title">
							房态
						</view>
						<view class="room-desc-tag">
							{{orderDetailObj.room_desc}}
						</view>
					</view>
				</view>
			</view>
			<view class="hotel-comment">
				<u-row customStyle="margin-bottom: 10px">
					<u-col span="2">
						<view class="hotel-comment-title">
							评价
						</view>
					</u-col>
					<u-col span="10">
						<view class="fui-flex">
							<view class="hotel-comment-start">
								<u-rate :current="orderRoom.comment_start" normal="#ccc" active="#FF9500"
									:size="14"></u-rate>
							</view>
							<view class="hotel-comment-number">
								{{orderRoom.comment_start}}
							</view>
						</view>

					</u-col>
				</u-row>
			</view>
		</view>
		<view class="card card_msg">
			<view class="card_msg_title">
				入住信息
			</view>
			<!-- <view class="fui-flex justify-between margin-bottom-sm">
				<view class="">
					<view class="card_msg_date">入住日期</view>
					<view class="card_msg_time">03月13日<text>今天</text></view>
				</view>
				<view class="fui-flex align-center">
					<view class="card_msg_line"></view>
					<view class="card_msg_border">1晚</view>
					<view class="card_msg_line"></view>
				</view>
				<view class="">
					<view class="card_msg_date">退房日期</view>
					<view class="card_msg_time">03月14日<text>明天</text></view>
				</view>
			</view> -->
			<view class="">
				<view class="fui-flex card_msg-bot">
					<view class="fui-col-3 card_msg-bot_left">
						房间数
					</view>
					<view class="fui-col-9">
						{{orderDetailObj.room_num}}
					</view>
				</view>
				<view class="fui-flex card_msg-bot">
					<view class="fui-col-3 card_msg-bot_left">
						入住天数
					</view>
					<view class="fui-col-9">
						{{orderDetailObj.diff_day}}晚
					</view>
				</view>
				<view class="fui-flex card_msg-bot">
					<view class="fui-col-3 card_msg-bot_left">
						入住时间
					</view>
					<view class="fui-col-9">
						{{orderDetailObj.start_time}}
					</view>
				</view>
				<view class="fui-flex padding-top-sm">
					<view class="fui-col-3 card_msg-bot_left">
						退房时间
					</view>
					<view class="fui-col-9">
						{{orderDetailObj.end_time}}
					</view>
				</view>
			</view>
		</view>
		<view class="card card_msg" style="padding-bottom: 0;">
			<view class="card_msg_title">
				住客信息
			</view>
			<view class="" style="border-bottom: 2rpx solid #F5F5F5;" v-for="item in orderDetailObj.checkInPerson"
				:key="item.id">
				<view class="padding-tb-sm font_size_16 text-bold">
					{{item.realname}}
				</view>
				<view class="padding-bottom-sm color2">
					电话号码：<text>{{item.mobile}}</text>
				</view>
				<view class="padding-bottom-sm color2">
					身份证号：<text>{{item.icard_code}}</text>
				</view>
			</view>

		</view>
		<view class="card card_msg">
			<view class="card_msg_title">
				发票信息
			</view>
			<!-- <view class="invoice" @click="invoicingBtn">
					<button class="invoice_btn">开发票</button>
				</view> -->
			<view class="padding-top-sm font_size_12">
				如需要发票，可向酒店索取（酒店可开普票、专票）
			</view>
		</view>
		<!-- 按钮 -->
		<!-- 1 => '待支付', -->
		<view class="bottom_btn fui-flex justify-between" v-if="orderDetailObj.status === 1">
			<button class="reser-btn" @click="orderConfirm(0)">取消订单</button>
			<button class="reser-btn reser-btn-blue" @click="orderPay">立即支付</button>
		</view>
		<!-- 2 => '待入住', -->
		<view class="bottom_btn fui-flex justify-between" v-if="orderDetailObj.status === 2">
			<button class="reser-btn" @click="goPage('refund')">申请退款</button>
			<button class="reser-btn reser-btn-blue"  @click="checkInRoom()">身份核验</button>
		</view>
		<!-- 3 => '已入住', -->
		<view class="bottom_btn fui-flex justify-between" v-if="orderDetailObj.status === 3">
			<button class="reser-btn" >订单延期</button>
			<button class="reser-btn reser-btn-blue" @click="goPage('privileges')">去开门</button>
		</view>
		<!-- 4 => '待评价', -->
		<view class="bottom_btn fui-flex justify-between" v-if="orderDetailObj.status === 4">
			<button class="reser-btn" @click="goPage('appraise')">评价酒店</button>
			<button class="reser-btn reser-btn-blue"  @click="goPage('appraise')">评价房间</button>
		</view>
		<!-- 5 => '已完成', -->
		<view class="bottom_btn fui-flex justify-between" v-if="orderDetailObj.status === 5">
			<!-- <button class="reser-btn">订单延期</button> -->
			<button class="reser-btn reser-btn-blue"  @click="goPage('appraise')">再次预定</button>
		</view>
		<!-- 6 => '已取消', -->
		<!-- <view class="bottom_btn fui-flex justify-between" v-if="orderDetailObj.status === 6">
			<button class="reser-btn"  @click="goPage('appraise')">退款进度</button>
			<button class="reser-btn reser-btn-orange" @click="toggle('bottom')">立即评价</button>
		</view> -->
	

		<!-- 弹层 -->
		<view>
			<!-- 普通弹窗 -->
			<uni-popup ref="popup" background-color="#fff" type="bottom">
				<view class="popups">
					<view class="fui-flex justify-end">
						<view class="popups_title">评价</view>
						<view class="" @click="close">
							<iconfont className="icon-quxiao"></iconfont>
						</view>
					</view>
					<view class="fui-flex justify-center" style="margin: 51rpx 0 60rpx;">
						<fui-rate :quantity="5" :current="currentNum" @change="changeNum" normal="#EEEEEE"
							active="#FF9500" :size="36"></fui-rate>
					</view>
					<view class="popups_textarea">
						<textarea name="" id="" cols="30" rows="10" placeholder="请输入评价内容"></textarea>
					</view>
					<button class="reser-btn reser-btn-green">提交</button>
				</view>
			</uni-popup>
		</view>
		<!-- 费用明细 start -->
		<u-popup :show="payDetailPopup" @close="closePayDetailPopup" @open="openPayDetailPopup">
			<view class="pay-detail-popup">
				<u-row>
					<u-col span="6" offset="2">
						<view class="pay-detail-popup-left">
							应付金额
						</view>
					</u-col>
					<u-col span="6">
						<view class="pay-detail-popup-right">
							{{orderDetailObj.amount_payable || 0}}
						</view>
					</u-col>
				</u-row>
				<u-row class="pay-detail-popup-row">
					<u-col span="6"  offset="2">
						<view class="pay-detail-popup-left">
							优惠金额
						</view>
					</u-col>
					<u-col span="6">
						<view class="pay-detail-popup-right">
							{{orderDetailObj.discount || 0}}
						</view>
					</u-col>
				</u-row>
				<u-row class="pay-detail-popup-row">
					<u-col span="6"  offset="2">
						<view class="pay-detail-popup-left">
							实付金额
						</view>
					</u-col>
					<u-col span="6">
						<view class="pay-detail-popup-right">
							{{orderDetailObj.real_pay || 0}}
						</view>
					</u-col>
				</u-row>
			</view>
		</u-popup>
		<!-- 费用明细 end -->
	</view>
</template>

<script>
	export default {
		data() {
			return {
				payDetailPopup: false,
				currentNum: 0,
				orderDetailObj: {},
				orderRoom: {},
				orderHotel: {},

			}
		},
		onLoad() {
			let that = this
			that.orderDetail(that.$Route.query.order_id)
		},
		computed: {
			idArr() {
				return this.orderDetailObj.checkInPerson.map(item => item.id)
			}
		},
		methods: {
			goPage(name){
				this.$Router.push({
					name:name,
					params:{
						order_id:this.orderDetailObj.id
					}
				})
			},
			closePayDetailPopup() {
				this.payDetailPopup = false
			},
			openPayDetailPopup() {
				this.payDetailPopup = true
			},
			orderDetail(order_id) {
				let that = this
				that.$api.hotelOrderOrderdetail(order_id).then(res => {
					// console.log(res, '5555')
					that.orderDetailObj = res.data
					that.orderRoom = res.data.room
					that.orderHotel = res.data.hotel

				}).catch(err => {
					uni.showToast({
						title: err.message,
						icon: 'none'
					})
				})
			},
			checkInRoom() {
				let that = this
				//是否公安核验
				if (that.orderDetailObj.is_check_in) {
					that.$Router.push({
						name: 'authentication',
						params: {
							order_id: that.orderDetailObj.id
						}
					})
				} else {
					that.orderConfirm(3);
				}
			},
			orderConfirm(confirmType,params){
				let that = this
				that.$api.hotelMobileOrderConfirm(that.orderDetailObj.id,confirmType,params).then(res=>{
					console.log('订单操作成功',res);
					uni.showToast({
						title: res.message,
						icon: 'success'
					})
					that.orderDetail(that.orderDetailObj.id)
				}).catch(err=>{
					console.log('订单操作识别',err);
					uni.showToast({
						title: err.message,
						icon: 'error'
					})
				})
			},
			toggle() {
				// open 方法传入参数 等同在 uni-popup 组件上绑定 type属性
				this.$refs.popup.open()
			},
			// 开发票
			// invoicingBtn() {
			// 	let that = this
			// 	that.$Router.push({
			// 		name: 'invoicing'
			// 	})
			// },
			// 再次预定
			bookAgain() {
				let that = this
				that.$Router.push({
					name: 'myreserve'
				})
			},
			changeNum(e) {
				let that = this
				that.currentNum = e.index
			},
			close() {
				this.$refs.popup.close()
			},
			// 去支付
			orderPay() {
				let that = this
				that.$Router.push({
					name: 'myorderPay'
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.order-room {
		margin-top: 20rpx;

		.room-desc-main {
			.room-desc {
				display: flex;
				height: 45rpx;
				line-height: 45rpx;

				.room-desc-title {
					font-size: 28rpx;
					width: 90rpx;

					&::after {
						padding-left: 10rpx;
						content: ':';
					}
				}

				.room-desc-tag {}

			}
		}


	}

	.hotel-comment {
		margin-top: 20rpx;
		font-size: 28rpx;

		.hotel-comment-title {}

		.hotel-comment-start {}

		.hotel-comment-number {
			padding-left: 20rpx;
		}
	}

	.pay-detail-popup {
		margin: 40rpx;
		.pay-detail-popup-left {
			height: 60rpx;
			font-size: 28rpx;
		}
		.pay-detail-popup-right {
			height: 60rpx;
			font-size: 28rpx;
			&::before {
				content: '￥';
				padding-right: 10rpx;
			}
		}
	}

	.header {
		height: 274rpx;
		background: #00AFC7;
		padding: 40rpx 32rpx;
		box-sizing: border-box;
		font-size: 24rpx;
		color: #fff;
	}

	.header_status {
		font-size: 36rpx;
		font-weight: 600;
	}

	.header_title {
		margin: 19rpx 0 11rpx;
	}

	.card {
		margin-top: 0;
		box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
	}

	.refund_btn {
		width: 144rpx;
		height: 48rpx;
		line-height: 48rpx;
		border: 2rpx solid #EEEEEE;
		border-radius: 24rpx;
		font-size: 24rpx;
		text-align: center;
		color: #666666;
	}

	.card_detail {
		margin-top: -48rpx;
		background: #FFFFFF;
		box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
		border-radius: 20rpx 20rpx 20rpx 20rpx;
		opacity: 1;
	}

	.card_detail_pay {
		font-size: 30rpx;
		color: #000000;
		font-weight: 600;
	}

	.card_detail_fees {
		font-size: 24rpx;
		color: #999999;
	}

	.card_detail_m {
		font-size: 28rpx;
		color: #FF0000;
	}

	.card_detail_n {
		font-size: 40rpx;
		color: #FF0000;
	}

	.card_img_title {
		font-size: 30rpx;
		color: #000000;
		font-weight: 600;
	}

	.card_img_address {
		margin: 10rpx 0 36rpx;
		font-size: 24rpx;
		color: #999999;
	}

	.card_msg {
		font-size: 28rpx;
		color: #000;
	}

	.card_msg_title {
		font-size: 30rpx;
		font-weight: 600;
		margin-bottom: 12rpx;
	}

	/* .card_msg_date {
		font-size: 24rpx;
		color: #999999;
		margin-bottom: 11rpx;
	} */

	/* .card_msg_border {
		width: 80rpx;
		height: 40rpx;
		line-height: 40rpx;
		text-align: center;
		border: 2rpx solid #EEEEEE;
		border-radius: 20rpx;
		font-size: 24rpx;
		color: #999999;
	} */

	/* .card_msg_line {
		width: 22rpx;
		height: 2rpx;
		background-color: #EEEEEE;
	} */

	.card_msg-bot {
		padding: 24rpx 0;
		border-bottom: 1rpx solid #EEEEEE;
	}

	.card_msg-bot_left {
		color: #999999;
	}

	.card_msg_com {
		font-size: 24rpx;
	}

	.bottom_btn {
		position: fixed;
		left: 0;
		bottom: 0;
		width: 100%;
		height: 120rpx;
		background-color: #fff;
		padding: 24rpx 32rpx;
		box-sizing: border-box;
		border-top: 2rpx solid #EEEEEE;
	}

	.reser-btn {
		width: 320rpx;
		height: 72rpx;
		line-height: 72rpx;
		font-size: 28rpx;
		color: #000;
		background-color: #fff;
		border: 2rpx solid #EEEEEE;
	}

	.reser-btn-blue {
		background-color: #00AFC7;
		color: #fff;
	}

	.reser-btn-orange {
		background-color: #FF9500;
		color: #fff;
	}

	.popups {
		height: 792rpx;
		border-radius: 40rpx 40rpx 0 0;
		padding: 32rpx 32rpx 64rpx;
		box-sizing: border-box;
		background-color: #fff;
	}

	.popups_title {
		margin: 0 287rpx;
		font-size: 32rpx;
		color: #000;
		font-weight: 600;
	}

	.popups_textarea {
		width: 100%;
		height: 360rpx;
		background-color: #F9F9F9;
		border-radius: 10rpx;
		margin-bottom: 48rpx;
		padding: 32rpx;
		box-sizing: border-box;
	}

	.reser-btn-green {
		width: 100%;
		height: 80rpx;
		background-color: #00AFC7;
		color: #fff;
	}

	.invoice_btn {
		width: 152rpx;
		height: 56rpx;
		line-height: 56rpx;
		border: 1rpx solid #00AFC7;
		background-color: #fff;
		font-size: 24rpx;
		color: #00AFC7;
	}
</style>