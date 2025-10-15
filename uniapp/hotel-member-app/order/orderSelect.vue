<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="选择订单" color="#000000" :border="false" fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
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
					<!-- 按钮 -->
					<view class="fui-flex bottom_btn_g">
						<view class="reser-btn" @click="goPage('privileges',item.id)">选择该订单</view>
					</view>
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
	</view>
</template>

<script>
	export default {
		data() {
			return {
				currentTab: 0,
				currentTabIndex: 3,
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
			that.orderList()
			that.offsetTop = that.CustomBar
		},
		onReachBottom() {
			let that = this
			if ((that.query.page * that.query.pageSize) <= that.total) {
				that.query.page++
				that.orderList()
			} else {
				uni.showToast({
					title: '加载完毕',
					icon: 'none'
				})
			}
		},
		methods: {
			goPage(name, order_id) {
				this.$Router.replace({
					name: name,
					params: {
						order_id: order_id
					}
				})
			},
			orderList() {
				let that = this
				that.$api.hotelDeviceOrderList(this.query.page, this.query.pageSize)
					.then(res => {
						that.orderLists = [...that.orderLists, ...res.data.list]
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
			}
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
		width: 100%;
		margin-top: 8px;
		flex-direction: column-reverse;
		text-align: center;

		.bottom_btn_g {
			padding: 0 100rpx;
			height: 40px;
			background-color: #00AFC7;
			border-radius: 20px;
			text-align: center;
			color: #FFFFFF;
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