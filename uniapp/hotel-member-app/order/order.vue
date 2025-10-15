<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="订单" color="#000000" :border="false" fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<u-sticky bgColor="#fff" :offsetTop="offsetTop">
			<u-tabs :list="tab" @change="tabsChange" :currentTab='currentTab' lineColor="#00AFC7"></u-tabs>
		</u-sticky>
		<view class="" style="margin-top: 32rpx;" v-if="orderLists.length > 0">
			<view class="card" v-for="item in orderLists" :key="item.id">
				<view class="fui-flex justify-between">
					<view class="">
						订单号：{{item.order_number}}
					</view>
					<view class="list_status">
						{{item.status_label}}
					</view>
				</view>
				<view class="line"></view>
				<view class="" style="font-size: 28rpx;font-weight: 600;margin-top: 24rpx;margin-bottom: 20rpx;">
					{{item.hotel.name}}
				</view>
				<view class="fui-flex" style="color: #999999;margin-bottom: 12rpx;">
					<view class="" style="width: 120rpx;">
						入住日期：
					</view>
					<view class="">
						{{(item.start_time).slice(0,10)}}至{{(item.end_time).slice(0,10)}}<text
							class="margin-left-xs">{{parseInt(item.diff_day)}}晚/{{item.room_num}}间</text>
					</view>
				</view>
				<view class="fui-flex" style="color: #999999;margin-bottom: 12rpx;">
					<view class="" style="width: 120rpx;">
						入住人：
					</view>
					<view class="">
						{{item.member_names}}
					</view>
				</view>
				<view class="fui-flex" style="color: #999999;margin-bottom: 12rpx;">
					<view class="" style="width: 120rpx;">
						房间类型：
					</view>
					<view class="">
						{{item.room_desc}}
					</view>
				</view>
				<view class="fui-flex" style="color: #999999;margin-bottom: 12rpx;">
					<view class="" style="width: 120rpx;">
						创建日期：
					</view>
					<view class="">
						{{(item.create_time).slice(0,10)}}
					</view>
				</view>
				<view class="line"></view>
				<view class="fui-flex bottom_btn justify-between">
					<view class="">
						<text style="color: #999999;">{{item.pay_type_str}}：</text>
						<text
							style="color: #FF0000;font-size: 32rpx;font-weight: bold;">¥{{(+item.real_pay).toFixed(2)}}</text>
					</view>
				</view>
				<view class="line"></view>
				<view class="fui-flex bottom_btn justify-between">
					<!-- 按钮 -->
					<view class="fui-flex bottom_btn_g" :class="{'margin-bottom':item.status === 3}">
						<view class="reser-btn" @click="goPage('myorderDetails',item.id)">订单详情</view>
					</view>
					<!-- 1 => '待支付', -->
					<view class="fui-flex bottom_btn_g" v-if="item.status === 1">
						<view class="reser-btn" @click="orderDel(item)">取消订单</view>
						<view class="reser-btn reser-btn-blue" @click="orderPay(item)">立即支付</view>
					</view>
					<!-- 2 => '待入住', -->
					<view class="fui-flex bottom_btn_g" v-if="item.status === 2">
						<view class="reser-btn" @click="goPage('refund',item.id)">申请退款</view>
						<view class="reser-btn reser-btn-blue" @click="checkInRoom(item.is_check_in, item.id)">身份核验
						</view>
					</view>
					<!-- 3 => '已入住', -->
					<view class="fui-flex bottom_btn_g" v-if="item.status === 3">
						<view class="reser-btn">订单延期</view>
						<view class="reser-btn reser-btn-blue" @click="goPage('privileges',item.id)">去开门</view>
						<view class="outroom" @click="checkOutRoom(item.id)">
							退房有礼
						</view>

						<view class="again" @click="orderPay(item)">
							再次预订
						</view>
					</view>
					<!-- 4 => '待评价', -->
					<view class="fui-flex bottom_btn_g" v-if="item.status === 4">
						<view class="reser-btn" @click="goPage('orderStar',item.id)">评价酒店</view>
						<view class="reser-btn reser-btn-blue" @click="goPage('orderStar',item.id)">评价房间</view>
					</view>
					<!-- 5 => '已完成', -->
					<view class="fui-flex bottom_btn_g" v-if="item.status === 5">
						<!-- <button class="reser-btn">订单延期</button> -->
						<view class="reser-btn reser-btn-blue" @click="goPage('appraise')">再次预定</view>
					</view>
					<!-- 6 => '已取消', -->
					<!-- <view class="fui-flex bottom_btn_g" v-if="orderDetailObj.status === 6">
						<button class="reser-btn"  @click="goPage('appraise')">退款进度</button>
						<button class="reser-btn reser-btn-orange" @click="toggle('bottom')">立即评价</button>
					</view> -->
				</view>
			</view>
		</view>
		<view class="fui-flex flex-direction" v-else>
			<view class="" style="margin-top: 280rpx;">
				<image src="https://oss.ddicms.cn/member/order/null-data.png" mode=""
					style="width: 500rpx;height: 280rpx;"></image>
			</view>
			<view class="font_size_15 text-bold margin-tb-sm">
				暂无数据
			</view>
			<view class="font_size_12 color13">
				暂无订单数据，快去逛逛吧
			</view>
		</view>
		<!-- 是否退出登录 -->
		<uni-popup ref="popupLy" type="center" :animation="false">
			<view class="popupLy">
				<view class="popupLy_title">
					确认删除嘛?
				</view>
				<view class="popupLy_btn fui-flex justify-between">
					<view class="popupLy_btn_left" @click="closeLy">
						否
					</view>
					<view class="popupLy_btn_line">
						|
					</view>
					<view class="popupLy_btn_right" @click="submitLy">
						是
					</view>
				</view>
			</view>
		</uni-popup>
		<tab-bar :currentindex="currentTabIndex"></tab-bar>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				currentTab: 0,
				currentTabIndex: 3,
				tab: [{
						name: '全部',
						order_status: null
					},
					{
						name: '待支付',
						order_status: 1
					},
					{
						name: '待入住',
						order_status: 2
					},
					{
						name: '已入住',
						order_status: 3
					},
					{
						name: '待评价',
						order_status: 4
					},
					{
						name: '已完成',
						order_status: 5
					}
				],
				orderLists: [],
				orderHotelObj: {},
				detailList: [{
						order_status: 'finish',
						text: '已完成'
					},
					{
						order_status: 'pay',
						text: '待支付'
					},
					{
						order_status: 'assess',
						text: '待评价'
					},
				],
				currentNum: 0,
				assessCon: '', //评价内容
				query: {
					page: 1,
					pageSize: 10
				},
				total: undefined,
				hotelName: '',
				orderId: undefined,
				offsetTop: 0
			};
		},
		onLoad() {
			let that = this
			if (that.$Route.query.order_status) {
				that.currentTab = Number(that.$Route.query.order_status)
			}
			that.orderList(false)
			that.offsetTop = that.CustomBar
		},
		onReachBottom() {
			let that = this
			if ((that.query.page * that.query.pageSize) <= that.total) {
				that.query.page++
				that.orderList(true)
			} else {
				uni.showToast({
					title: '加载完毕',
					icon: 'none'
				})
			}
		},
		methods: {
			goPage(name, order_id) {
				this.$Router.push({
					name: name,
					params: {
						order_id: order_id
					}
				})
			},
			orderList(flag) {
				let that = this
				that.$api.hotelOrderList(this.query.page, this.query.pageSize, that.tab[that.currentTab].order_status)
					.then(res => {
						if (flag) {
							that.orderLists = [...that.orderLists, ...res.data.list]
						} else {
							that.orderLists = res.data.list
						}
						that.total = res.data.total
					}).catch(res => {
						uni.showToast({
							title: res.message,
							icon: 'none'
						});
					})
			},
			checkInRoom(is_check_in, order_id) {
				let that = this
				//是否公安核验
				if (is_check_in) {
					that.$Router.push({
						name: 'authentication',
						params: {
							order_id: order_id
						}
					})
				} else {
					that.orderConfirm(order_id, 3);
				}
			},
			checkOutRoom(order_id) {
				let that = this
				that.orderConfirm(order_id, 6);
			},
			orderConfirm(order_id, confirmType, params) {
				let that = this
				that.$api.hotelMobileOrderConfirm(order_id, confirmType, params).then(res => {
					console.log('订单操作成功', res);
					uni.showToast({
						title: res.message,
						icon: 'success'
					})
					that.orderList()
				}).catch(err => {
					console.log('订单操作识别-错误', err);
					uni.showToast({
						title: err.message,
						icon: 'error'
					})
				})
			},
			// 删除订单
			orderDel(item) {
				let that = this
				that.$refs.popupLy.open()
				that.orderId = item.id
			},
			submitLy() {
				let that = this

				that.$api.hotelOrderDelorder(that.orderId).then(res => {
					if (res.code === 200) {
						that.$refs.popupLy.close()
						that.orderList(false)
					}
					console.log(res, '2222')
				}).catch(err => {
					uni.showToast({
						title: err.message,
						icon: 'none'
					})
				})
			},
			closeLy() {
				let that = this
				that.$refs.popupLy.close()
			},
			tabsChange(e) {
				let that = this
				that.$set(that.query, 'page', 1)
				that.currentTab = e.index;
				that.orderList(false)
			},
			// 再次预定
			orderDetailBtn() {
				let that = this
				that.$Router.push({
					name: 'myreserve'
				})
			},
			// 立即支付
			orderPay(item) {
				let that = this
				that.$Router.push({
					name: 'myorderPay',
					params: {
						order_id: item.id,
						status: item.status
					}
				})
			},
			changeNum(e) {
				let that = this
				that.currentNum = e.index
			},
		},


	};
</script>

<style lang="scss" scoped>
	.line {
		height: 2rpx;
		background: #F5F5F5;
		border-radius: 10rpx;
		margin-top: 23rpx;
	}

	.bottom_btn {
		height: auto;
		margin-top: 16rpx;
		justify-content: flex-end;
		flex-wrap:wrap;

		.bottom_btn_g {

			view {
				padding: 0 10rpx;
				height: 56rpx;
				line-height: 56rpx;
				text-align: center;
				background: #FFFFFF;
				border-radius: 4rpx;
				border: 2rpx solid #EEEEEE;
				margin-left: 24rpx;
				color: #999999;
				border-radius: 10rpx;
			}

			.again {
				color: #000000;
				font-size: 24rpx;
				font-weight: 400;
			}

			.pay {
				background: #00AFC7;
				color: #fff;
			}

			.outroom {
				background: #00AFC7;
				color: #fff;
			}

			.inroom {
				background: #00AFC7;
				color: #fff;
			}

			.evaluation {
				background: #FF9500;
				color: #fff;
			}
		}
	}

	.card {
		margin-top: 0;
		box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
	}

	.list_status {
		font-size: 24rpx;
		color: #00AFC7;
	}

	.reser-btn-green {
		width: 100%;
		height: 80rpx;
		line-height: 80rpx;
		background-color: #00AFC7;
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


	.popupLy {
		width: 686rpx;
		height: 232rpx;
		background: #FFFFFF;
		border-radius: 40rpx 40rpx 40rpx 40rpx;
		padding: 48rpx 32rpx;
		box-sizing: border-box;

		.popupLy_title {
			text-align: center;
			font-size: 32rpx;
			font-weight: 400;
			color: #000000;
		}

		.popupLy_btn {
			margin-top: 49rpx;

			.popupLy_btn_left {
				width: 278rpx;
				font-size: 30rpx;
				text-align: center;
			}

			.popupLy_btn_line {
				flex: 1;
				text-align: center;
				transform: translateY(-6rpx);
			}

			.popupLy_btn_right {
				width: 278rpx;
				font-size: 30rpx;
				color: #00AFC7;
				text-align: center;
			}
		}
	}
</style>