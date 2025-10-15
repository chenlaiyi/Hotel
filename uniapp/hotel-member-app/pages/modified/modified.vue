<template>
	<view class="container">
		<!-- 个人资料 -->
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="个人资料" color="#000000" :border="false" fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="card">
			<!-- 头像 -->
			<view class="fui-flex flex-direction justify-center">
				<view class="infor_avart">
					<fui-upload :value="avatarUrlStr.length ? avatarUrlStr : avatar" :limit="1"
						@complete="resultactive($event,'avatar')" @remove="removeactive($event,'avatar')">
					</fui-upload>
				</view>
				<view class="infor_btn">
					点击更换头像
				</view>
			</view>
			<!-- 信息 -->
			<view class="fui-flex message">
				<view class="fui-col-3 message_title">
					姓名
				</view>
				<view class="fui-col-9">
					<input type="text" v-model="username" placeholder="请输入用户名" style="font-size: 28rpx;"
						:disabled="showDisable" placeholder-style="font-size:28rpx;color:#D1D1D1;">
				</view>
			</view>
			<view class="fui-flex message">
				<view class="fui-col-3 message_title">
					手机号
				</view>
				<view class="fui-col-9">
					<input type="text" v-model="mobile" placeholder="请输入手机号" style="font-size: 28rpx;"
						:disabled="showDisable" placeholder-style="font-size:28rpx;color:#D1D1D1;">
				</view>
			</view>
			<view class="fui-flex message">
				<view class="fui-col-3 message_title">
					身份证号
				</view>
				<view class="fui-col-9">
					<input type="text" v-model="idcard" placeholder="请输入身份证号" style="font-size: 28rpx;"
						:disabled="showDisable" placeholder-style="font-size:28rpx;color:#D1D1D1;">
				</view>
			</view>
			<view class="fui-flex message">
				<view class="fui-col-3 message_title">
					性别
				</view>
				<view class="fui-col-9">
					<radio-group class="fui-flex" @change="genderChange">
						<label class="fui-flex" v-for="(item,index) in radioItems" :key="index">
							<view>
								<radio style="transform: scale(0.6);" :id="item.name" :value="item.name"
									:disabled="showDisable" :checked="item.name == gender"></radio>
							</view>
							<view>
								<label class="label-2-text" :for="item.name">
									<text>{{item.value}}</text>
								</label>
							</view>
						</label>
					</radio-group>
				</view>
			</view>
		</view>
		<view class="fui-flex align-center identity-mes_check">
			<view class="">
				<checkbox :checked="checked" style="transform: scale(0.5);"></checkbox>
			</view>
			<view class="identity-mes_txt" @click="agreement('bottom')">
				我已阅读并同意<text class="color4">用户协议</text>和<text class="color4">隐私协议</text>
			</view>
		</view>
		<!-- 按钮 -->
		<view class="">
			<button class="save" @click="saveBtn">保存</button>
		</view>

		<view class="" style="margin: 0 32rpx 30rpx;">
			<uni-popup type="bottom" ref="popup" background-color="#fff">
				<view class="popup-content">
					<view class="popup-content_title">
						用户协议
					</view>
					<view class="popup-content_con">
						<view class="">
							1、注册/登录注册1、打开动擎E刻APP、点击注册进入注册页根据要求填写手机号并填写验证码、完成注册。
						</view>
						<view class="">
							2、登录账号密码登录（主页点击“登录”按钮，如已经完成注册，可使用账号密码登录）验证码登录（主页点击“登录”按钮——选择验证码登录）。
						</view>
						<view class="">
							3、添加车辆，在车库页，点击右上角标冬添加车辆、填写车辆信息，填写您的车牌号、选择车型（支持修改），填写科尼德车联控车智能设备序列号（不支持修改），车架号、发动机号、选填（支持修改）点击下一步出厂年份为。
						</view>
						<view class="">
							4、删除车辆当您需要将车辆与设备解绑，进行重新绑定时，选择删除车辆。删除车辆操作：点击删除车辆，输入您的账号密码，点击确定回到车库查看车辆是否删除。
						</view>
					</view>
					<view class="fui-flex popup-content_btn justify-between align-center">
						<view class="popup-content_btn_con">
							<button class="back con" @click="backBtn">不同意并退出</button>
						</view>
						<view class="line"></view>
						<view class="popup-content_btn_con">
							<button class="agree con" @click="agreeBtn">同意</button>
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
				// 切换状态
				showResult: true,
				checked: false,
				showDisable: false,
				// 变量
				certifyStatus: undefined,
				username: '',
				mobile: undefined,
				idcard: '',
				gender: 0,
				radioItems: [{
						name: '0',
						value: '男',
					},
					{
						name: '1',
						value: '女',
					}
				],
				avatarUrl: '', //会员头像
				avatarUrlStr: [], //初始化图片的路径
				avatar: [] //默认头像
			};
		},
		onShow() {
			let that = this
			that.getInfodetail()
			// if (this.$store.state.userInfo.icard_auth_status === 1 || this.$store.state.userInfo.icard_auth_status ===
			//     2) {
			//     that.showDisable = true
			// }

		},
		methods: {
			// 同意
			agreeBtn() {
				let that = this
				that.$refs.popup.close()
				that.checked = true
			},
			backBtn() {
				let that = this
				that.$refs.popup.close()
			},
			agreement(type) {
				let that = this
				this.$refs.popup.open(type)
			},
			// 获取房东信息
			getInfodetail() {
				let that = this
				that.$api.hotelBlocMemberInfoDetail().then(res => {
					that.username = res.data.realname
					that.mobile = res.data.mobile
					that.idcard = res.data.idcard
					that.gender = res.data.gender
					that.avatarUrlStr = [res.data.avatar]
					// that.$store.dispatch('setUserInfoActions', res.data)
					console.log(res.data.avatar, '9999999999')
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			// 保存按钮
			saveBtn() {
				let that = this
					that.$api.hotelBlocMemberInfoEdit(that.username, that.mobile, that.idcard, that.gender, that.avatarUrlStr[0]).then(res => {
						if (res.code === 200) {
							uni.showToast({
								title: res.message,
								icon: 'none'
							})
						}
					}).catch(res => {
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
					})
				that.$Router.back()
			},
			genderChange(e) {
				let that = this
				that.gender = Number(e.detail.value)
			},
			// 拿到上传的图片
			resultactive(e, index) {
				let that = this
				if (e.imgArr.length === 0) {
					return false;
				}
				that.fui
					.uploadFile(e.imgArr[0])
					.then(res => {
						that.$set(that.avatarUrlStr, 0, res.data.url);
					})
					.catch(res => {
						console.log('cuowu', res, this.avatarUrl);
					});
			},
			removeactive(e, k) {
				let that = this
				//移除图片
				that.avatarUrlStr = [];
				// console.log(that.avatarUrlStr);
			}
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		margin-top: 16rpx;

		.card {
			box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
			margin-top: 0;
			padding: 48rpx 32rpx 0;

			.infor_avart {
				margin-bottom: 24rpx;

			}

			.infor_btn {
				font-size: 28rpx;
				margin: 0 0 24rpx;
			}

			.message {
				padding: 30rpx 0;
				border-bottom: 1rpx solid #F5F5F5;

				.message_title {
					font-size: 28rpx;
					color: #666666;
				}

			}
		}

		.save {
			margin: 48rpx 32rpx 0;
			height: 88rpx;
			line-height: 88rpx;
			font-size: 28rpx;
			color: #FFFFFF;
			background-color: #00AFC7;
		}
	}

	::v-deep .uni-radio-wrapper .uni-radio-input-checked {
		background-color: rgb(0, 175, 199) !important;
		border-color: rgb(0, 175, 199) !important;
	}

	::v-deep .uni-label-pointer {
		font-size: 28rpx;
		margin-right: 28rpx;
	}

	// 隐私
	.popup-content {
		width: 686rpx;
		height: 1000rpx;
		background-color: #fff;
		border-radius: 40rpx 40rpx 40rpx 40rpx;
		padding: 32rpx 0 8rpx;
		box-sizing: border-box;
		margin: 0 32rpx 30rpx;
		overflow: hidden;

		.popup-content_title {
			font-size: 36rpx;
			font-weight: 600;
			text-align: center;
			margin-bottom: 32rpx;
		}

		.popup-content_con {
			max-height: 774rpx;
			font-size: 28rpx;
			line-height: 56rpx;
			overflow-y: auto;
			padding: 0 40rpx;
		}

		.popup-content_btn {
			border-top: 1rpx solid #EEEEEE;

			.line {
				width: 2rpx;
				height: 42rpx;
				background-color: #CBCBCB;
				margin: 0 32rpx;
			}

			.popup-content_btn_con {
				flex: 1;
				background-color: #fff;
			}

			.con {
				background-color: #fff;
				font-size: 30rpx;
				line-height: 106rpx;
			}

			.back {
				color: #000;
			}

			.agree {
				color: #00AFC7;
			}
		}
	}
</style>