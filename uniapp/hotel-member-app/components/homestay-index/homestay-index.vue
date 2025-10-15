<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="首页"  :fixed="true"   fixed placeholder :auto-back="true" :border="true">
		</u-navbar>
		<!-- #endif -->

		<view class="top_img">
			<image class="room_img" :src="topImg" mode="aspectFill" />
			<!-- 查询 -->
			<view class="hostel_detail bg-white">
				<!-- 当前位置 -->
				<view class="hostel_detail_line fui-flex justify-between" style="overflow: hidden;">
					<view class="fui-col-9">
						{{address || '西安'}}
					</view>
					<view class="height-line fui-col-1"></view>
					<view class="fui-flex flex-direction fui-col-2" style="color: #999999;" @click="getCurrentLoca()">
						<view class="">
							<iconfont className="icon-dangqianweizhi" :size="22" color="#999999"></iconfont>
						</view>
						<view class="" style="font-size: 24rpx;">
							我的位置
						</view>
					</view>
				</view>
				<!-- 开始结束时间 -->
				<uni-datetime-picker type='daterange' :start="todayTime" v-model="single1" @change="changeTime">
					<view class="hostel_detail_line fui-flex justify-between">
						<view class="shop-date">
							{{orderTime.startTimeStr}} <text class="shop margin-left-xs">入住</text>
						</view>
						<view class="center-time">{{orderTime.nightTime}}晚</view>
						<view class="shop-date">
							<text class="shop margin-right-xs">离店</text>{{orderTime.endTimeStr}}
						</view>
					</view>
				</uni-datetime-picker>
				<!-- 人数 -->
				<view class="hostel_detail_line fui-flex justify-between" @click="showActionSheet = true">
					<view class="hostel_detail_line_num">
						<text class="hostel_detail_line_num_text">{{personNum.adult}}个成人<span
								class="hostel_detail_line_num_dot"></span></text>
						<text class="hostel_detail_line_num_text">{{personNum.child}}个儿童<span
								class="hostel_detail_line_num_dot"></span></text>
					</view>
					<view class="">
						<iconfont className="icon-youjiantou1" :size="14"></iconfont>
					</view>
				</view>
				<view class="search_btn margin-top-lg text-white background3 fui-center" @click="searchHome">查询</view>
			</view>
		</view>
		<view class="room_tj">
			<!-- 特色民宿 -->
			<homestay-list page="homestayIndex"></homestay-list>
			<!-- 周边景点 -->
			<scenery-list page="homestayIndex"></scenery-list>
		</view>



		<!-- 人员选择 -->
		<u-modal :show="showActionSheet" @confirm="personNumClick" confirmColor="#00AFC7">
			<view class="slot-content">
				<u--form labelWidth="150" labelPosition="left" :model="personNum" ref="personNumForm">
					<u-form-item label="成人" prop="personNum.adult" ref="personNum.adult">
						<u-number-box v-model="personNum.adult" integer color="#ffffff" bgColor="#00AFC7"
							iconStyle="color: #fff;"></u-number-box>
					</u-form-item>
					<u-form-item label="儿童" prop="personNum.child" ref="personNum.child">
						<u-number-box v-model="personNum.child" :min="0" integer color="#ffffff" bgColor="#00AFC7"
							iconStyle="color: #fff;"></u-number-box>
					</u-form-item>
				</u--form>
			</view>
		</u-modal>

		<tab-bar :currentindex="currentTabIndex"></tab-bar>
	</view>
</template>

<script>
	var QQMapWX = require('@/common/utils/qqmap-wx-jssdk.min.js')
	var qqmapsdk
	export default {
		name:'homestayIndex',
		data() {
			return {
				key: 'N2PBZ-4LQKH-EJEDX-WT3FI-T2SO5-XZBRJ',
				showActionSheet: false, //默认不展示
				showModal: false, //收藏
				maskClosable: true,
				isCancel: true,
				toggleShow: true,
				qqmapsdk: null,
				single1: undefined, // 入住时间
				currentTabIndex: 0,
				currentTab: 0, // tab当前激活
				activeStyle: {
					color: '#00AFC7',
				},
				tabList: [],
				tabPath: '', //跳转路由
				rateIndex: 4,
				personNum: {
					adult: 1,
					child: 0
				},
				itemList: [{
						text: '1人',
						color: '#2B2B2B'
					},
					{
						text: '2人',
						color: '#2B2B2B'
					},
					{
						text: '3人',
						color: '#2B2B2B'
					},
					{
						text: '4人',
						color: '#2B2B2B'
					}
				],
				positionInfo: {
					longitude: '', //经度
					latitude: '', //纬度
				},
				address: '', // 当前位置
				greColor: '#00AFC7',
				yelColor: '#FF9500',
				swiperList: [{
						name: '热门',
						id: 'hot'
					},
					{
						name: '娱乐',
						id: 'yule'
					},
					{
						name: '体育',
						id: 'sports'
					},
					{
						name: '国内',
						id: 'domestic'
					},
					{
						name: '财经',
						id: 'finance'
					},
					{
						name: '科技',
						id: 'keji'
					},
					{
						name: '教育',
						id: 'education'
					},
					{
						name: '汽车',
						id: 'car'
					}
				],
				todayTime: '',
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
				template_type: 1, // 1：酒店     23456:非酒店 
			};
		},
		watch: {
			orderTime: {
				handler(newVal, oldVal) {
					if (newVal) {
						console.log('orderTime-change 设置', newVal)
						// 本地存储
						try {
							uni.setStorageSync('orderTime', newVal)
						} catch (e) {
							// error
							console.log('orderTime-change 设置error', e)
						}
					}
				},
				deep: true //对象中任一属性值发生变化，都会触发handler方法
			}
		},
		props:{
			topImg: {
				type:String,
				default:''
			}
		},
		created() {
			let that = this
			that.initTimeStr()
			that.getTabs()
			that.todayTime = that.fui.iglobal.formatDate(new Date())
			console.log('time', that.todayTime)
		},
		onLoad() {
			let that = this
			qqmapsdk = new QQMapWX({
				key: 'SZRBZ-PBSW4-4PYUI-F64ZA-IFYJF-CYBPD'
			})
			this.fui.mapdistanceCitylist().then(res => {
				console.log('mapdistanceCitylist', res)
			}).catch(err => {
				console.log('ststs-err', err)
			})
		},
		onReady() {
			setTimeout(() => {
				this.qqmapsdk = new QQMapWX({
					key: this.key
				});
				this.getCurrentLoca()
			}, 100)
		},
		methods: {
			initTimeStr() {
				let that = this
				const orderTime = uni.getStorageSync('orderTime')
				let today = new Date();
				console.log('initTimeStr-index', orderTime)
				const startTime = that.fui.iglobal.formatDate(today)
				if (orderTime && orderTime.startTime === startTime) {
					that.orderTime = orderTime
					that.personNum = orderTime.personNum || that.personNum
				} else {
					// 获取明天的日期
					let tomorrow = new Date(today.getTime() + 86400000); // 减去一天的毫秒数
					that.orderTime = {
						startTime: that.fui.iglobal.formatDate(today),
						endTime: that.fui.iglobal.formatDate(tomorrow),
						nightTime: 1,
						personNum: that.personNum,
						startTimeStr: that.fui.iglobal.formatDate(today, '{m}月{d}日'),
						endTimeStr: that.fui.iglobal.formatDate(tomorrow, '{m}月{d}日')
					}
				}
			},
			// tabs切换
			getTabs() {
				let that = this
				that.$api.hotelMobileIndexTabs().then(res => {
					that.tabList = res.data.map(item => {
						return {
							name: item.title,
							path: item.path,
							template_type: item.template_type
						}
					})
					// that.template_type = that.tabList[0].template_type
				}).catch(res => {
					console.log('错误', res)
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			// tabs切换
			tabsChange(e) {
				let that = this
				that.currentTab = e.index;
				that.template_type = e.template_type
				// that.tabPath = that.tabList[e.index].path
			},
			changeTime(e) {
				let that = this
				that.orderTime = {
					startTime: e[0],
					endTime: e[1],
					nightTime: (+new Date(e[1]) - +new Date(e[0])) / 1000 / 3600 / 24,
					personNum: that.personNum,
					startTimeStr: that.fui.iglobal.formatDate(e[0], '{m}月{d}日'),
					endTimeStr: that.fui.iglobal.formatDate(e[1], '{m}月{d}日')
				}
			},
			//搜索
			goSearch() {
				let that = this;
				that.$Router.push({
					name: 'hotelSearch',
					params: {}
				});
			},
			goMessage() {
				let that = this
				that.$Router.push({
					name: 'message'
				})
			},
			// 查询
			searchHome() {
				let that = this;
				if (that.template_type === 1) {
					that.$Router.push({
						name: 'apartmentQuery'
					});
				} else {
					that.$Router.push({
						name: 'apartment'
					});
				}

			},
			// 关闭弹层
			personNumClick() {
				let that = this;
				const total = that.fui.iglobal.arrSum([that.personNum.adult, that.personNum.child])
				console.log('总计人数', total)
				that.$set(that.orderTime, 'personNum', that.personNum)
				that.closeActionSheet();
			},
			closeActionSheet() {
				this.showActionSheet = false;
			},
			// 通过自带的方法获取到当前的经纬度，调用方法获取到地址获取到地址的中文信息
			getCurrentLoca() {
				let that = this
				// #ifdef MP-WEIXIN
				uni.getFuzzyLocation({
					type: 'wgs84',
					success: function(res) {
						// console.log('经纬度',res.longitude,res.latitude);
						qqmapsdk.reverseGeocoder({
							location: {
								latitude: res.latitude,
								longitude: res.longitude
							},
							success: (res) => {
								that.address = res.result.address
							},
							fail(e) {
								console.log(e);
							}
						})
					}
				});
				// #endif
			}
		},

	};
</script>

<style lang="scss" scoped>
	.hostel_detail_tabs {
		// width: 750rpx;
		// transform: translateX(-32rpx);
		display: block !important;
	}

	.navbar {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
	}

	.top_img {
		position: relative;
		height: 750rpx;

		.room_img {
			height: 350rpx;
			width: 100%;
		}

		// .top_info {
		// 	padding: 0 32rpx;
		// 	position: absolute;
		// 	top: 32rpx;
		// 	left: 32rpx;
		// 	font-size: 26rpx;
		// }

		// .search_border {
		// 	position: relative;
		// 	width: 534rpx;
		// 	height: 64rpx;
		// 	line-height: 64rpx;
		// 	color: rgba(255, 255, 255, .5);
		// 	background: rgba(0, 0, 0, 0.3);
		// 	border-radius: 32rpx;
		// 	box-sizing: border-box;
		// }

		// .search_big {
		// 	position: absolute;
		// 	top: 10rpx;
		// 	left: 32rpx;
		// }

		.hostel_detail {
			position: absolute;
			bottom: 20rpx;
			box-shadow: 0px 10rpx 12rpx 2rpx rgba(0, 0, 0, 0.1);
			border-radius: 10rpx;
			padding: 24rpx 32rpx;
			font-size: 28rpx;
			color: #000;
			overflow: hidden;
			left: 20rpx;
			right: 20rpx;

			.hostel_detail_line {
				height: 100rpx;
				border-bottom: 1rpx solid #E1E1E1;

				.hostel_detail_line_num {
					.hostel_detail_line_num_text {
						width: 182px;
						height: 45px;
						font-size: 32rpx;
						font-weight: 400;
						color: #000000;
						line-height: 32px;

						&:not(:last-child) {
							.hostel_detail_line_num_dot {
								width: 2px;
								height: 2px;
								background: #000000;
								display: inline-block;
								line-height: 1;
								margin: 5px;
							}
						}

					}
				}
			}
		}


	}




	.color_desc {
		color: #e8e8e8;
	}



	.center-time {
		width: 72rpx;
		height: 32rpx;
		border: 1rpx solid #D1D1D1;
		border-radius: 16rpx;
		text-align: center;
		font-size: 20rpx;
		color: #999999;
	}

	.shop {
		font-size: 22rpx;
		color: #999999;
	}

	.shop-date {
		font-size: 36rpx;
	}

	.height-line {
		width: 1rpx;
		height: 40rpx;
		background-color: #CCCCCC;
	}

	.border_bott {
		border-bottom: 2rpx solid rgba(112, 112, 112, 0.1);
		padding-bottom: 14rpx;
	}

	.type_color {
		color: #474747;
		font-size: 22rpx;
	}

	.center_border {
		width: 19px;
		border-bottom: 1px solid #707070;
	}

	.left_border {
		height: 25px;
		font-size: 36rpx;
		color: #000;
		border-right: 1px solid rgba(112, 112, 112, 0.3);
	}

	.search_btn {
		height: 88rpx;
		font-size: 36rpx;
		border-radius: 16rpx;
	}

	.margin-left_7 {
		margin-left: 14rpx;
	}

	.room_tj {
		margin: 31rpx 34rpx 0 34rpx;
	}

	.uni-swiper-tab {
		white-space: nowrap;
	}

	.block {
		position: relative;
		display: inline-block;
	}

	.all_btn {
		font-size: 22rpx;
		color: #999999;
	}

	.room-scorll-list {
		margin-top: 32rpx;

		.room-scorll-list_img {
			position: relative;
			width: 200rpx;
			height: 280rpx;
			border-radius: 10rpx;

			.room-scorll-list_img_icon {
				position: absolute;
				right: 12rpx;
				top: 12rpx;
			}
		}

		.room-scorll-list_title {
			width: 462rpx;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.room-scorll-list_num {
			padding: 2rpx 8rpx;
			background-color: #FF9500;
			border-radius: 5rpx;

		}

		.room-scorll-list_txt {
			font-size: 24rpx;
			// -webkit-transform: scale(0.8);
		}

		.room-scorll-list_km {
			position: relative;
			font-size: 24rpx;
			color: #666666;
			margin-right: 24rpx;
		}

		.room-scorll-list_km::before {
			content: "";
			position: absolute;
			right: -10rpx;
			top: 50%;
			transform: translateY(-50%);
			width: 1px;
			height: 24rpx;
			background-color: #D1D1D1;
		}

		.room-scorll-list_km:last-child::before {
			content: "";
			background-color: transparent;
		}

		.room-scorll-list_assess {
			padding: 2rpx 5rpx;
			background-color: #F9F9F9;
			border-radius: 5rpx;
			margin-right: 10rpx;
			margin-top: 10rpx;

			view {
				font-size: 22rpx;
				-webkit-transform: scale(0.8);
				color: #999999;
			}
		}

		.room-scorll-list_coupon {
			padding: 4rpx 14rpx;
			border: 1px solid rgba(251, 91, 50, .3);
			border-radius: 5rpx;
			color: #FB5B32;
			font-size: 24rpx;
		}
	}

	.room-scorll-item {
		margin-top: 32rpx;

		.room-scorll-list_img {
			position: relative;
			width: 100%;
			height: 360rpx;
			border-radius: 10rpx;

			.room-scorll-list_icon {
				position: absolute;
				right: 32rpx;
				top: 32rpx;
			}

			.room-scorll-list_assess {
				position: absolute;
				left: 16rpx;
				bottom: 16rpx;
				padding: 2rpx 12rpx;
				background-color: rgba(255, 255, 255, .8);
				border-radius: 5rpx;
			}
		}

		.room-scorll-list_txt {
			.txt {
				position: relative;
				margin-right: 24rpx;
			}

			.txt::after {
				content: "";
				position: absolute;
				right: -12rpx;
				top: 50%;
				transform: translateY(-50%);
				width: 1px;
				height: 20rpx;
				background-color: #D1D1D1;
			}

			.txt:last-child::after {
				content: "";
				background-color: transparent;
			}
		}

		.room-scorll-list_bor {
			.bor_con {
				padding: 4rpx 12rpx;
				font-size: 20rpx;
				border-radius: 5rpx;
				margin-right: 10rpx;
				font-size: 24rpx;

			}

			.bor_red {
				background-color: #FFEEEA;
				color: #FB5B32;
			}

			.bor_gray {
				background-color: #F9F9F9;
				color: #999999;
			}
		}
	}

	.fui-tab-items {
		display: inline-block;
		flex-wrap: nowrap;
		margin-right: 32rpx;
	}

	.fui-tab-items:last-child {
		margin-right: 0;
	}

	.fui-tab-item-swiper {
		flex-wrap: nowrap;
		white-space: nowrap;

		.swiper_image {
			position: relative;
			width: 280rpx;
			height: 280rpx;
			border-radius: 10rpx;

			.swiper_assess {
				position: absolute;
				bottom: 0;
				left: 0;
				padding: 2rpx 8rpx;
				background-color: rgba(1, 1, 1, .8);
				border-radius: 0rpx 10rpx 0rpx 10rpx;
				font-size: 20rpx;
			}
		}

		.swiper_line {
			width: 1px;
			height: 20rpx;
			background-color: #CCCCCC;
		}
	}

	.found_input {
		position: relative;
		padding: 14rpx 94rpx 14rpx 24rpx;
		background-color: #F9F9F9;
		border-radius: 20rpx;
		margin: 32rpx 0 20rpx;

		.found_input_sure {
			position: absolute;
			right: 24rpx;
			top: 14rpx;
			color: #00AFC7;
		}
	}

	.modal_txt {
		font-size: 32rpx;
		padding: 22rpx 0;
	}
</style>