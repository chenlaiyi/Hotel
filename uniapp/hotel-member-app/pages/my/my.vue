<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="我的" color="#000000" :border="false" fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<!-- 用户信息 -->
		<view class="my_top">
			<view class="fui-flex justify-between margin-top-sm" @click="editInfo">
				<view class="fui-flex">
					<view class="my-top-img margin-right-sm">
						<image :src="member.avatar" mode="" style="width: 100%;height: 100%;border-radius: 50%;">
						</image>
					</view>
					<view class="">
						<view class="my-top-username">
							{{member.realname}}
						</view>
						<view class="my-top-id">
							ID：{{member.member_id}}
						</view>
					</view>
				</view>
				<view class="">
					<iconfont className="icon-31shezhi" :size="20"></iconfont>
				</view>
			</view>
		</view>
		<!-- 我的订单 -->
		<view class="card padding-0 padding-bottom-xs my-status" style="margin-bottom: 0;">
			<view class="my-detail fui-flex justify-between" @click="goAllOrder">
				<view class="text-bold">
					我的订单
				</view>
				<view class="fui-flex">
					<view class="font_size_13 color13">
						全部订单
					</view>
					<view class="" style="margin: 0 32rpx 3rpx 10rpx;">
						<iconfont className="icon-youjiantou1" :size="14" color="#999999"></iconfont>
					</view>
				</view>
			</view>
			<view class="fui-flex justify-around padding-bottom-sm">
				<view class="fui-flex flex-direction" v-for="(item,index) in orderList" :key="index"
					@click="goAftersale(item)">
					<view class="my-pay_img">
						<image :src="item.orderImg" mode="" style="width: 100%;height: 100%;"></image>
					</view>
					<view class="padding-top-sm">
						{{item.orderTxt}}
					</view>
				</view>
			</view>
		</view>
		<!-- 常用功能 -->
		<view class="card padding-0">
			<view class="my-detail my-often text-bold">
				常用功能
			</view>
			<view class="fui-flex my-con">
				<view class="my-icon margin-right-sm">
					<image src="https://oss.ddicms.cn/member/my/yue.png" mode=""
						style="width: 100%;height: 100%;"></image>
				</view>
				<view class="fui-flex justify-between my-con_bottom" @click="gohref(1)">
					<view class="font_size_16">
						我的余额
					</view>
					<view class="">
						<iconfont className="icon-youjiantoucu" :size="12"></iconfont>
					</view>
				</view>
			</view>
			<view class="fui-flex my-con">
				<view class="my-icon margin-right-sm">
					<image src="https://oss.ddicms.cn/member/my/kaquan.png" mode=""
						style="width: 100%;height: 100%;"></image>
				</view>
				<view class="fui-flex justify-between my-con_bottom" @click="gohref(2)">
					<view class="font_size_16">
						我的卡券
					</view>
					<view class="">
						<iconfont className="icon-youjiantoucu" :size="12"></iconfont>
					</view>
				</view>
			</view>
			<view class="fui-flex my-con">
				<view class="my-icon margin-right-sm">
					<image src="https://oss.ddicms.cn/member/my/address.png" mode=""
						style="width: 100%;height: 100%;"></image>
				</view>
				<view class="fui-flex justify-between my-con_bottom" @click="gohref(4)">
					<view class="font_size_16">
						地址管理
					</view>
					<view class="">
						<iconfont className="icon-youjiantoucu" :size="12"></iconfont>
					</view>
				</view>
			</view>
			<view class="fui-flex my-con">
				<view class="my-icon margin-right-sm">
					<image src="https://oss.ddicms.cn/member/my/jifen.png" mode=""
						style="width: 100%;height: 100%;"></image>
				</view>
				<view class="fui-flex justify-between my-con_bottom" @click="gohref(3)">
					<view class="font_size_16">
						我的积分
					</view>
					<view class="">
						<iconfont className="icon-youjiantoucu" :size="12"></iconfont>
					</view>
				</view>
			</view>
			<!-- <view class="fui-flex my-con">
				<view class="my-icon margin-right-sm">
					<image src="https://oss.ddicms.cn/member/my/chongzhi.png" mode=""
						style="width: 100%;height: 100%;"></image>
				</view>
				<view class="fui-flex justify-between my-con_bottom" @click="gohref(5)">
					<view class="font_size_16">
						充值
					</view>
					<view class="">
						<iconfont className="icon-youjiantoucu" :size="12"></iconfont>
					</view>
				</view>
			</view> -->
			<view class="fui-flex my-con">
				<view class="my-icon margin-right-sm">
					<image src="https://oss.ddicms.cn/member/my/xinyuandan.png" mode=""
						style="width: 100%;height: 100%;"></image>
				</view>
				<view class="fui-flex justify-between my-con_bottom" @click="gohref(6)">
					<view class="font_size_16">
						心愿单
					</view>
					<view class="">
						<iconfont className="icon-youjiantoucu" :size="12"></iconfont>
					</view>
				</view>
			</view>
			<view class="fui-flex my-con">
				<view class="my-icon margin-right-sm">
					<image src="https://oss.ddicms.cn/member/my/bangzhu.png" mode=""
						style="width: 100%;height: 100%;"></image>
				</view>
				<view class="fui-flex justify-between my-con_bottom" @click="gohref(7)">
					<view class="font_size_16">
						帮助中心
					</view>
					<view class="">
						<iconfont className="icon-youjiantoucu" :size="12"></iconfont>
					</view>
				</view>
			</view>
			<view class="fui-flex my-con">
				<view class="my-icon margin-right-sm">
					<image src="https://oss.ddicms.cn/member/my/fapiao.png" mode=""
						style="width: 100%;height: 100%;"></image>
				</view>
				<view class="fui-flex justify-between my-con_bottom" @click="gohref(8)">
					<view class="font_size_16">
						发票记录
					</view>
					<view class="">
						<iconfont className="icon-youjiantoucu" :size="12"></iconfont>
					</view>
				</view>
			</view>
			<view class="fui-flex my-con">
				<view class="my-icon margin-right-sm">
					<image src="https://oss.ddicms.cn/member/my/shouhou.png" mode=""
						style="width: 100%;height: 100%;"></image>
				</view>
				<view class="fui-flex justify-between my-con_bottom" @click="gohref(9)">
					<view class="font_size_16">
						我的售后
					</view>
					<view class="">
						<iconfont className="icon-youjiantoucu" :size="12"></iconfont>
					</view>
				</view>
			</view>
			<!-- #ifndef MP -->
			<view class="fui-flex my-con" @click="backBtn('center')">
				<view class="my-icon margin-right-sm">
					<image src="https://oss.ddicms.cn/member/my/loginout.png" mode=""
						style="width: 100%;height: 100%;"></image>
				</view>
				<view class="fui-flex justify-between my-con_bottom">
					<view class="font_size_16">
						退出登录
					</view>
					<view class="">
						<iconfont className="icon-youjiantoucu" :size="12"></iconfont>
					</view>
				</view>
			</view>
			<!-- #endif -->

		</view>
		<!-- 是否退出登录 -->
		<uni-popup ref="popupLy" type="center" :animation="false">
			<view class="popupLy">
				<view class="popupLy_title">
					是否退出
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
				currentTabIndex: 4,
				userinfo: {},
				is_device: false,
				member: {},
				orderList: [{
						orderImg: 'https://oss.ddicms.cn/member/my/zhifu.png',
						orderTxt: '待支付',
						current: 1
					}, {
						orderImg: 'https://oss.ddicms.cn/member/mys/stay.png',
						orderTxt: '待入住',
						current: 2
					},
					{
						orderImg: 'https://oss.ddicms.cn/member/mys/icon_alive.png',
						orderTxt: '已入住',
						current: 3
					},
					{
						orderImg: 'https://oss.ddicms.cn/member/my/pingjia.png',
						orderTxt: '待评价',
						current: 4
					},
					{
						orderImg: 'https://oss.ddicms.cn/member/my/wancheng.png',
						orderTxt: '已完成',
						current: 5
					}
				]
			};
		},
		onShow() {
			let that = this;
			that.$nextTick(function() {});
		},
		onLoad() {
			let that = this;
			that.memberInfo()
		},
		methods: {
			// 全部订单
			goAllOrder() {
				let that = this
				that.$Router.push({
					name: 'myorder',
					params: {
						order_status: 0
					}
				})
				that.$store.state.currentindex = 3
			},
			// 订单
			goAftersale(item) {
				let that = this;
				that.$Router.push({
					name: 'myorder',
					params: {
						order_status: item.current
					}
				});
				that.$store.state.currentindex = 3
			},
			//修改个人资料
			editInfo() {
				let that = this;
				that.$Router.push({
					name: 'pmodified'
				});
			},
			gohref(index) {
				let that = this;
				let name = '';
				if (index === 1) {
					name = 'mybalance';
				} else if (index === 2) {
					name = 'myoupon';
				} else if (index === 3) {
					name = 'myintegral';
				} else if (index === 4) {
					name = 'address';
				}
				// else if (index === 5) {
				// 	name = 'myrecharge';
				// }
				else if (index === 6) {
					name = 'wish';
				} else if (index === 7) {
					name = 'phelp';
				} else if (index === 8) {
					name = 'pinvoice';
				} else if (index === 9) {
					name = 'afterSale'
				}
				that.$Router.push({
					name: name,
				});
			},
			memberInfo() {
				let that = this
				that.$api.hotelMemberInfo().then(res => {
					that.member = res.data
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			// 是否退出登录
			backBtn() {
				let that = this
				that.$refs.popupLy.open()
			},
			// 退出
			closeLy() {
				let that = this
				that.$refs.popupLy.close()
			},
			// 是
			submitLy() {
				let that = this
				that.fui.loginOut(function() {
					that.$Router.replaceAll({
						name: 'login'
					})
				})
				that.closeLy()
			},
		}
	};
</script>

<style lang="scss" scoped>
	.navbar {
		padding-left: 25rpx;
	}

	.container {
		padding-bottom: 128rpx;
	}

	.card {
		box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(204, 204, 204, 0.3);
	}


	.my_top {
		width: 100%;
		height: 240rpx;
		background: linear-gradient(180deg, #E0F5F8 0%, #FFFFFF 100%);
		padding: 0 32rpx;
		box-sizing: border-box;
	}

	.my-top-img {
		width: 120rpx;
		height: 120rpx;
		border-radius: 50%;
		border: 6rpx solid #fff;
		box-sizing: border-box;
	}

	.my-top-username {
		font-size: 40rpx;
		font-weight: 600;
	}

	.my-top-id {
		font-size: 24rpx;
		margin-top: 10rpx;
	}

	.my-status {
		margin: -56rpx 32rpx 0;
	}

	.my-detail {
		padding: 32rpx 0 32rpx 32rpx;
		font-size: 36rpx;
	}

	.my-often {
		padding: 32rpx 0 8rpx 32rpx;
	}

	.my-pay_img {
		width: 48rpx;
		height: 48rpx;
	}

	.my-con {
		padding: 0 32rpx;
	}

	.my-icon {
		width: 36rpx;
		height: 36rpx;
		padding: 30rpx 0;
	}

	.my-con_bottom {
		flex: 1;
		border-bottom: 2rpx solid #F5F5F5;
		padding: 31rpx 0;
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