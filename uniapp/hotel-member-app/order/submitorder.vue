<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="预订" color="#000000" :border="false"  fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="card">
			<view class="header">
				<view class="">
					{{orderTime.startTimeStr}}
					至
					{{orderTime.endTimeStr}}
					<text
						class="header_bc">共{{orderTime.nightTime ? orderTime.nightTime : getDefaultObj.nightTime}}晚</text>
				</view>
				<view class="header_title">
					{{orderDetail.lease_type === 0?roomDetail.title:orderDetail.name}}
				</view>
				<view class="header_info">
					<view class="fui-flex whole-room_other_txt">
						<view class="txt_every">
							{{roomDetail.room_num}}室
						</view>
						<view class="txt_every">
							{{roomDetail.bed}}床
						</view>
						<text class="gun" style="font-size: 22rpx;">|</text>
						<view class="txt_every">
							{{roomDetail.persons}}人
						</view>
						<text class="gun" style="font-size: 22rpx;">|</text>
						<view class="txt_every">
							{{roomDetail.area}}㎡
						</view>
					</view>
					<view class="font_size_12 color4" v-if="orderDetail.lease_type === 0">
						入住当天{{roomDetail.cancel_end}}前可免费取消
					</view>
					<view class="fui-flex">
						<view class="margin-right-sm text-center" v-for="(server,sindex) in roomDetail.server"
							:key="sindex">
							<u-tag :text="server.title" type="warning" plain plainFill border-color="#"
								size="mini"></u-tag>
						</view>
					</view>
				</view>
			</view>
		</view>
		<!-- 入住信息 -->
		<view class="card" style="padding-bottom: 0;">
			<view class="left_title">
				入住信息
			</view>
			<view class="line_item fui-flex justify-between" v-if="orderDetail.lease_type === 0">
				<view class="line_item_left">
					房间数
				</view>
				<view class="fui-flex">
					<u-number-box v-model="roomNum" :max="totalPersonNum" integer color="#ffffff" bgColor="#00AFC7"
						iconStyle="color: #00AFC7;" @change="roomNumChange"></u-number-box>
				</view>
			</view>
			<view class="line_item fui-flex justify-between">
				<view class="line_item_left">
					预订人数
				</view>
				<view class="">
					{{personNum.adult}}个成人/{{personNum.child}}个儿童
				</view>
			</view>

			<!-- <block>
				<view class="line_item fui-flex" v-for="(item,i) in addPerson" :key="i">
					<view class="fui-flex line_item_enter">
						<view class="padding-right-sm fui-flex flex-direction">
							<view class="">
								入住人{{i + 1}}
							</view>
							<view class="margin-top-sm" @click="delFn(item)">
								<i class="iconfont fuishanchuyixuanqunchengyuanchacha"
									style="font-size: 40rpx;color: #999999;"></i>
							</view>
						</view>
						<view class="" style="font-size: 28rpx;">
							<view class="padding-bottom-sm">
								住客姓名：{{item.userName}}
							</view>
							<view class="">
								电话号码：{{item.userMobile}}
							</view>
							<view class="padding-top-sm" v-if="item.userEmail">
								电子邮箱：{{item.userEmail}}
							</view>
						</view>
					</view>

					<view class="color4">
						编辑
					</view>
				</view>
			</block> -->


			<!-- <view class="line_item fui-flex">
				<view class="padding-right-sm line_item_enter">
					入住人{{addPerson.length + 1}}
				</view>
				<view class="color4" style="font-size: 28rpx;" @click="completion('bottom')">
					点击补全入住人信息
				</view>
			</view> -->

			<view class="line_item fui-flex" v-for="item in addPerson" :key="item.id">
				<view class="margin-right-sm" style="align-self: flex-start;" @click="delPerson(item)">
					<iconfont className="icon-shanchu1" :size="24" color="#FB5B32"></iconfont>
				</view>
				<view class="">
					<view class="font_size_16 text-bold">
						{{item.realname}}
					</view>
					<view class="padding-tb-xs font_size_14 color2">
						电话号码：{{item.mobile}}
					</view>
					<view class="font_size_14 color2">
						电子邮箱：{{item.icard_code}}
					</view>
				</view>
			</view>
			<view class="line_item fui-flex justify-center" @click="selectCheckIn">
				<view class="margin-right-xs">
					<iconfont className="icon-a-1_huaban1-031" color="#00AFC7"></iconfont>
				</view>
				<view class="color4 font_size_14">
					选择/添加入住人信息
				</view>
			</view>

		</view>

		<view class="card">
			<view class="line_item fui-flex" @click="reportShow = true">
				<view class="line_item_left">
					预计到店时间
				</view>
				<view class="" style="font-weight: bold;">
					{{reportTime}}
				</view>
				<view class="line_item_right">
					<dd-iconfont class-name="icon-31shezhi" size="18" color="#00AFC7"></dd-iconfont>
				</view>
			</view>
		</view>
		<view class="card" style="margin-bottom: 20rpx;">
			<view class="left_title">
				发票信息
			</view>
			<view class="" style="margin-top: 32rpx;font-size: 24rpx;">
				如需要发票，可向酒店索取（酒店可开普票、专票）
			</view>
		</view>
		<!-- <view class="margin-list">
			<view class="margin-list_label fui-flex justify-center">
				<label class="radio">
					<radio color="#00AFC7" value="r1" style="transform:scale(0.5)" />
				</label>
				<view class="radio_text">
					阅读并同意<text style="color: #00AFC7;">《用户授权协议》</text>及<text style="color: #00AFC7;">《隐私政策》</text>
				</view>
			</view>
		</view> -->
		<view class="bottom_btn fui-flex justify-between">
			<view class="">
				<text style="font-size: 24rpx;font-weight: bold;">支付</text>
				<text style="margin-left: 8rpx;color: #00AFC7">¥{{totalPrice}}</text>
			</view>
			<view class="fui-flex">
				<view class="fui-flex align-center" style="margin-right: 32rpx;" type="primary"
					@click="toggle('bottom')">
					<text style="margin-right: 8rpx;font-size: 24rpx;color: #999999;">明细</text>
					<image src="https://oss.ddicms.cn/member/detailed.png" mode="" style="width: 10rpx;height: 6rpx;" />
				</view>
				<view class="bottom_submit" @click="orderPay">
					去支付
				</view>
			</view>
		</view>

		<!-- 预计到店时间 start -->
		<u-datetime-picker ref="datetimePicker" :show="reportShow" @cancel="reportShow = false" @confirm="setReportTime"
			:formatter="formatter" mode="datetime" :minDate="minDate"  :maxDate="maxDate"></u-datetime-picker>
		<!-- 预计到店时间 end -->

		<!-- 弹窗 明细-->
		<view>
			<uni-popup ref="popup" background-color="#fff" type="bottom">
				<view class="popup-content">
					<view class="fui-flex popup-pad justify-end" @click="close">
						<view class="popup-pad">
							明细
						</view>
						<view class="" style="margin:0 0 38rpx 287rpx;">
							<iconfont className="icon-quxiao" :size="18" color="#999999"></iconfont>
						</view>
					</view>
					<view class="fui-flex justify-between popup-bot" v-if="payType === 1">
						<view class="">
							担保费
						</view>
						<view class="popup-txt">
							￥<text>200</text>
						</view>
					</view>
					<view class="fui-flex justify-between popup-bot">
						<view class="">
							{{popupPayStatus}}
						</view>
						<view class="popup-txt">
							￥<text>200</text>
						</view>
					</view>
					<view class="fui-flex justify-between popup-bot">
						<view class="">
							优惠合计
						</view>
						<view class="popup-txt popup-red">
							-￥<text>5</text>
						</view>
					</view>
					<view class="fui-flex justify-between popup-bot popup-top">
						<view class="">
							合计
						</view>
						<view class="popup-txt popup-all">
							￥<text>200</text>
						</view>
					</view>
				</view>
			</uni-popup>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				roomDetail: {},
				payType: 0,
				tabsPay: [{
						name: '在线支付'
					},
					{
						name: '余额支付'
					}
				],
				id: 0,
				userName: '',
				userMobile: '',
				userEmail: '',
				addPerson: [],
				roomNum: 1, //房间数
				hotel_id: '',
				room_id: '',
				cprice: '',
				totalPrice: 0,
				totalPersonNum: 1,
				personNum: {
					adult: 1,
					child: 0
				},
				orderTime: {
					startTime: '',
					endTime: '',
					nightTime: '',
					personNum: {
						adult: 1,
						child: 0
					},
					startTimeStr: '',
					endTimeStr: ''
				},
				reportShow: false,
				reportTime: '',
				orderDetail:{},
				minDate:0,
				maxDate:0
			}
		},
		computed: {
			popupPayStatus() {
				return this.payType === 0 ? '在线支付' : '到店支付'
			},
			idArr() {
				return this.addPerson.map(item => item.id)
			},
		},
		onShow() {
			let that = this
			that.initTimeStr()
			console.log('初始化时间参数', this.start_time, this.end_detime)

		},
		onReady() {
			let that = this
			uni.$on('selectArr', (data) => {
				console.log('selectArr-data', data)
				that.addPerson = data
			})
		},
		onLoad(options) {
			let that = this
			console.log('query', that.$Route.query)
			that.hotel_id = that.$Route.query.hotel_id
			that.cprice = that.$Route.query.cprice
			that.room_id = that.$Route.query.room_id
			that.totalPrice = (that.cprice * that.roomNum).toFixed(2)
			that.getRoomDetail(that.hotel_id,that.room_id)
			// #ifdef MP-WEIXIN
			this.$refs.datetimePicker.setFormatter(this.formatter)
			// #endif
			let today = new Date();
			this.minDate = today.getTime()
			this.maxDate = this.getFutureTimestamps(3);

		},
		onunload() {
			uni.$off('selectArr')
		},
		methods: {
			getFutureTimestamps(months) {
			  var today = new Date();
			  var futureDate = new Date(today.setMonth(today.getMonth() + months));
			  var timestamp = futureDate.getTime();
			  return timestamp;
			},
			initTimeStr() {
				let that = this
				const orderTime = uni.getStorageSync('orderTime')
				console.log('initTimeStr-index', orderTime)
				if (orderTime) {
					that.orderTime = orderTime
					that.personNum = orderTime.personNum || that.personNum
				} else {
					let today = new Date();
					// 获取明天的日期
					let tomorrow = new Date(today.getTime() + 86400000); // 减去一天的毫秒数
					that.orderTime = {
						startTime: that.fui.iglobal.formatDate(today),
						endTime: that.fui.iglobal.formatDate(tomorrow),
						nightTime: 1,
						personNum: that.personNum,
						startTimeStr: that.fui.iglobal.formatDate(tomorrow, '{m}月{d}日'),
						endTimeStr: that.fui.iglobal.formatDate(tomorrow, '{m}月{d}日')
					}
				}
				// 预计到店时间
				that.reportTime = that.orderTime.startTime
				that.totalPersonNum = that.fui.iglobal.arrSum([that.personNum.adult, that.personNum.child])
			},
			getRoomDetail(hotel_id,room_id) {
				let that = this
				that.$api.hotelMobileHotelOrRoom(hotel_id, that.room_id).then(res => {
					that.roomDetail = res.data.roomDetail
					that.orderDetail = res.data
				}).catch(err => {
					console.log(err)
					uni.showToast({
						title: err.message,
						icon: 'none'
					})
				})
			},
			formatter(type, value) {
				if (type === 'year') {
					return `${value}-`
				}
				if (type === 'month') {
					return `${value}-`
				}
				if (type === 'day') {
					return `${value}`
				}
				return value
			},
			setReportTime(e) {
				console.log(this.fui.iglobal.formatDate(e.value))
				this.reportTime = this.fui.iglobal.formatDate(e.value)
				console.log(this.reportTime)
				this.reportShow = false
			},
			cancelReport() {
				this.reportShow = false
			},
			delPerson(item) {
				const delIndex = this.addPerson.findIndex(o => o.mobile === item.mobile)
				this.addPerson.splice(delIndex, 1)
			},
			// getDetail() {
			// 	let that = this
			// 	that.fui
			// 		.request('/diandi_hotel/mobile/order/checkperson', 'POST', {
			// 			check_in_id: that.check_in_id
			// 		}, false)
			// 		.then(res => {
			// 			// that.detailObj = res.data.member
			// 			that.addPerson.push({
			// 				userName: res.data.realname,
			// 				userMobile: res.data.mobile,
			// 				userEmail: res.data.email
			// 			})
			// 		}).catch(res => {
			// 			uni.showToast({
			// 				res: res.message,
			// 				icon: 'none'
			// 			})
			// 		})
			// },
			selectFn(i) {
				let that = this
				that.payType = i
			},
			orderPay() {
				let that = this
				console.log(that.addPerson, that.roomNum)
				if (that.addPerson.length === that.totalPersonNum) {
					that.$api.hotelOrderCreateOrder(1, that.hotel_id, that.room_id,that.orderTime.startTime,
							that.orderTime.endTime, 0, 0, that.cprice, that.cprice - 0, that.idArr, that.roomNum)
						.then(res => {
							console.log('orderinfo', res)
							that.$Router.push({
								name: 'myorderPay',
								params: {
									order_id: res.data.id,
									pay_type: that.payType
								}
							})
						}).catch(err => {
							console.log(err)
							uni.showToast({
								title: err.message,
								icon: 'none'
							})
						})
				} else {
					const totalPersonNum = that.totalPersonNum
					uni.showToast({
						title: `应该填写${totalPersonNum}人信息`,
						icon: 'none'
					})
					return false
				}
				// console.log(this.idArr)
				// console.log(this.roomNum)

			},
			// 添加人员
			// finishBtn() {
			// 	let that = this
			// 	// that.userName = userName
			// 	let obj = {
			// 		userName: that.userName,
			// 		userMobile: that.userMobile,
			// 		userEmail: that.userEmail
			// 	}
			// 	// 添加对象
			// 	that.addPerson.push(obj)
			// 	// 重置表单
			// 	that.userName = '',
			// 		that.userMobile = '',
			// 		that.userEmail = ''
			// 	this.$refs.popupDetail.close()
			// },
			// 数字输入框
			roomNumChange(e) {
				let that = this
				that.roomNum = e.value;
				that.totalPrice = (e.value * that.cprice).toFixed(2)
			},
			// 选择/添加入住人
			selectCheckIn() {
				let that = this
				that.$Router.push({
					name: 'checkIn'
				})
			},
			// 弹出框
			toggle() {
				// this.type = type
				// open 方法传入参数 等同在 uni-popup 组件上绑定 type属性
				this.$refs.popup.open()
			},
			// 按钮关闭弹层
			close() {
				this.$refs.popup.close()
			},
		}
	}
</script>

<style>
	* {
		font-size: 28rpx;
	}

	.header_bc {
		display: inline-block;
		min-width: 72rpx;
		height: 36rpx;
		background: rgba(0, 175, 199, .1);
		border-radius: 4rpx;
		color: #00AFC7;
		font-size: 22rpx;
		text-align: center;
		padding: 0 10rpx;
		margin-left: 24rpx;
	}

	.header_title {
		font-size: 32rpx;
		font-weight: 600;
		color: #000000;
		margin-top: 16rpx;
	}

	.header_info {
		margin-top: 12rpx;
	}

	.gun {
		margin: 0 16rpx;
	}

	.left_title {
		font-size: 30rpx;
		font-weight: 600;
		color: #000000;
	}

	.line_item {
		position: relative;
		/* height: 88rpx; */
		padding: 32rpx 0;
		border-bottom: 1rpx solid #F5F5F5;
	}

	.line_item_left {
		width: 176rpx;
		font-size: 28rpx;
	}

	.line_item_enter {
		font-size: 28rpx;
	}

	.line_item_right {
		position: absolute;
		right: 0;
	}

	.select {
		width: 300rpx;
		height: 88rpx;
		line-height: 88rpx;
		text-align: center;
		border-radius: 10rpx;
		border: 2rpx solid #EEEEEE;
		font-size: 28rpx;
	}

	.select_bc {
		border: 2rpx solid #00AFC7;
		color: #00AFC7;
		background: url('https://oss.ddicms.cn/member/Select.png') no-repeat right bottom;
	}

	.bottom_btn {
		position: fixed;
		left: 0;
		bottom: 0;
		width: 750rpx;
		height: 120rpx;
		background: #FFFFFF;
		padding: 0 32rpx;
		box-sizing: border-box;
		border-top: 2rpx solid #EEEEEE;
		z-index: 9999;
	}

	.bottom_submit {
		width: 200rpx;
		height: 80rpx;
		line-height: 80rpx;
		text-align: center;
		background: #00AFC7;
		border-radius: 10rpx;
		color: #fff;
	}

	.card {
		margin-bottom: 0;
		box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
	}

	.margin-list {
		margin: 0 32rpx;
	}

	.margin-list_label {
		margin-bottom: 22rpx;
	}

	.radio_text {
		font-size: 20rpx;
	}

	.popup-content {
		width: 750rpx;
		box-sizing: border-box;
		padding: 32rpx 32rpx 0;
		border-radius: 40rpx 40rpx 0rpx 0rpx;
		background-color: #fff;
		margin-bottom: 120rpx;
	}

	.popup-add {
		margin-bottom: 0;
	}

	.popup-height {
		width: 200px;
	}

	.popup-pad {
		padding-bottom: 48rpx;
		font-size: 32rpx;
		color: #000000;
		font-weight: 600;
	}

	.popup-bot {
		padding-bottom: 24rpx;
		font-size: 28rpx;
		color: #000000;
	}

	.popup-txt {
		font-size: 32rpx;
		font-weight: 600;
	}

	.popup-all {
		font-size: 48rpx;
	}

	.popup-red {
		color: #FF0000;
	}

	.popup-top {
		font-size: 32rpx;
		padding: 31rpx 0 40rpx;
		border-top: 1rpx solid #EEEEEE;
		border-bottom: 1rpx solid #EEEEEE;
	}

	.popup-pad_detail {
		text-align: center;
		font-size: 36rpx;
	}

	.popup-border {
		padding: 24rpx 0;
		border-bottom: 1rpx solid #F5F5F5;
	}

	/* 公共按钮样式 */
	.com {
		width: 320rpx;
		height: 72rpx;
		line-height: 72rpx;
		font-size: 28rpx;
	}

	.cancel {
		background-color: #fff;
		color: #000000;
		border: 2rpx solid #EEEEEE;
	}

	.success {
		background-color: #00AFC7;
		color: #fff;
	}

	/* 修改数字框样式 */
	.test .uni-numbox__minus {
		border-radius: 50% !important;
		border: 1px solid #00AFC7;
		border-color: #00AFC7 !important;
	}

	.test .uni-numbox__minus text {
		color: #00AFC7 !important;
	}

	.test .uni-numbox__plus {
		border-radius: 50% !important;
		border: 1px solid #00AFC7;
		border-color: #00AFC7 !important;
	}

	.test .uni-numbox__plus text {
		color: #00AFC7 !important;
	}

	.uni-numbox__value {
		background-color: #fff !important;
	}

	.fui-numbox-icon {
		padding: 13rpx !important;
		background-color: #fff !important;
		border: 1rpx solid #00AFC7 !important;
	}

	.fui-num-input {
		border: 1rpx solid #fff !important;
	}

	/* 修改数字框样式 */
	::v-deep .test .uni-numbox__minus {
		border-radius: 50% !important;
		border: 1px solid #00AFC7;
		border-color: #00AFC7 !important;
	}

	::v-deep .test .uni-numbox__minus text {
		color: #00AFC7 !important;
	}

	::v-deep .test .uni-numbox__plus {
		border-radius: 50% !important;
		border: 1px solid #00AFC7;
		border-color: #00AFC7 !important;
	}

	::v-deep .test .uni-numbox__plus text {
		color: #00AFC7 !important;
	}

	::v-deep .uni-numbox__value {
		background-color: #fff !important;
	}

	::v-deep .fui-numbox-icon {
		padding: 8rpx !important;
		background-color: #fff !important;
		border: 1rpx solid #00AFC7 !important;
	}

	::v-deep .fui-num-input {
		border: 1rpx solid #fff !important;
	}
</style>