<template>
	<view class="login">
		<view class="login_mask"></view>
		<view class="login_header fui-flex justify-end">
			<text v-if="loginShow" @click="registerFn">注册</text>
			<text v-if="!loginShow" @click="loginFn">登录</text>
		</view>
		<view class="login_title" v-if="loginShow">
			欢迎回来
		</view>
		<view class="login_title" v-else>
			账号注册
		</view>
		<!-- 登录 -->
		<view class="login_form" v-if="loginShow">
			<view class="">
				<input type="text" placeholder="请输入账号" class="input_item" v-model="form.username">
			</view>
			<view class="login_eye">
				<input type="password" placeholder="请输入密码" class="input_item" style="margin-top: 40rpx;"
					v-model="form.password" v-if="!eye_change">
				<input type='text' class="input_item" autocomplete="off" placeholder="请输入密码" style="margin-top: 40rpx;"
					v-model="form.password" v-else>
				<image src="@/static/svgs/p_hide.svg" mode="" v-if="!eye_change" @click="eye_change = true"></image>
				<image src="@/static/svgs/p_show.svg" mode="" v-else @click="eye_change = false"></image>
			</view>
			<view class="" style="margin-top: 24rpx;color: #00AFC7;text-align: end;font-size: 24rpx;">
				忘记密码
			</view>
			<view class="login_btn" @click="loginSubmitFn">
				登录
			</view>
		</view>
		<!-- 注册 -->
		<view class="login_form" v-else>
			<view class="">
				<input type="text" placeholder="请输入账号" class="input_item" v-model="form.username">
			</view>

			<!-- <view class="login_form_code">
				<input v-model="form.code" placeholder="获取验证码" class="input_item" style="margin-top: 40rpx;">
				<view v-if="countDown===30" class="login_form_code_time font_size_12 color14" @click="sendCode">
					发送验证码
				</view>
				<view v-else class="login_form_code_time font_size_12 color14">
					倒计时{{countDown}}s
				</view>
			</view> -->

			<view class="login_eye">
				<input type="password" placeholder="请输入密码" class="input_item" style="margin-top: 40rpx;"
					v-model="form.password" v-if="!eye_change">
				<input type='text' class="input_item" autocomplete="off" placeholder="请输入密码" style="margin-top: 40rpx;"
					v-model="form.password" v-else>
				<image src="@/static/svgs/p_hide.svg" mode="" v-if="!eye_change" @click="eye_change = true"></image>
				<image src="@/static/svgs/p_show.svg" mode="" v-else @click="eye_change = false"></image>
			</view>

			<view class="login_btn" @click="register">
				注册
			</view>
		</view>
	</view>
</template>

<script>
	import {
		mapMutations
	} from 'vuex'
	export default {
		data() {
			return {
				loginShow: true, // 登录 or 注册
				MutexFlag: true,
				form: {
					username: '',
					password: '',
					mobile: '',
					code: '' //发送验证码
				},
				eye_change: false, // 密码显示隐藏
				countDown: 30,
				// 倒计时
				timer: null,
				timerFlag: false,
				errFlag: true,
				// 倒计时
				registerFlag: true
			}
		},
		watch: {
			countDown(val) {
				if (val === 0) {
					let that = this
					clearInterval(that.timer)
					that.countDown = 30
					that.timerFlag = false
				}
			}
		},
		methods: {
			// 重置表单
			resetFn() {
				let that = this
				that.$set(that.form, 'username', '')
				that.$set(that.form, 'password', '')
				// that.$set(that.form, 'code', '')
				// that.$set(that.form, 'mobile', '')
			},
			// 登录标题
			loginFn() {
				let that = this
				that.loginShow = true
				that.resetFn()
			},
			// 注册标题
			registerFn() {
				let that = this
				that.loginShow = false
				that.resetFn()
			},
			// 点击登录
			// 登录
			loginSubmitFn() {
				let that = this
				if (that.MutexFlag) {
					// 互斥锁
					that.MutexFlag = false
					// 非空判断
					if (that.loginShow) {
						if (!that.form.username) {
							that.MutexFlag = true
							return uni.showToast({
								title: '请输入手机号',
								icon: 'none'
							})
						}
						if (!that.form.password) {
							that.MutexFlag = true
							return uni.showToast({
								title: '请输入密码',
								icon: 'none'
							})
						}
					}
					uni.showLoading({
						title: '登录中',
						mask: true
					})
					
					that.fui.userLogin('mobile',that.form.username,null, that.form.password).then(res=>{
						that.MutexFlag = true
						uni.hideLoading()
						// #ifdef MP-WEIXIN
						uni.setStorageSync('fans', res.data.wxappFans);
						// #endif
						
						// #ifndef MP-WEIXIN
						uni.setStorageSync('fans', res.data.wechatFans);
						// #endif
						uni.setStorageSync('nickname', res.data.member.nickname);
						uni.setStorageSync('access_token', res.data.access_token);
						uni.setStorageSync('refresh_token', res.data.refresh_token);
						uni.setStorageSync('expiration_time', res.data.expiration_time);
						uni.setStorageSync('member', res.data.member);
						that.$Router.replaceAll({
							name: 'index'
						});
					}).catch(res=>{
						that.MutexFlag = true
						console.log(res)
						uni.showToast({
							icon: 'error',
							title: res.message,
							duration: 2000
						});
					})
				}
			},
			// 点击注册
			// 注册
			register() {
				let that = this
				if (that.registerFlag) {
					// 互斥锁
					that.registerFlag = false
					// 切换到注册页
					if (that.loginShow === false) {
						// 非空判断
						if (!that.form.username) {
							that.registerFlag = true
							return uni.showToast({
								title: '请输入账户',
								icon: 'none'
							})
						}
						// if (!that.form.code) {
						// 	that.registerFlag = true
						// 	return uni.showToast({
						// 		title: '请输入验证码',
						// 		icon: 'none'
						// 	})
						// }
						if (!that.form.password) {
							that.registerFlag = true
							return uni.showToast({
								title: '请输入密码',
								icon: 'none'
							})
						}
					}
					uni.showLoading({
						title: '注册中',
						mask: true
					})
					that.fui.userRegister('mobile',that.form.username,null, that.form.password).then(res => {
						if (res.code === 200) {
							uni.hideLoading()
							uni.showToast({
								title: res.message,
								icon: 'none'
							})
							that.registerFlag = true
							that.loginFn()
						}
					}).catch(res => {
						that.registerFlag = true
						uni.showToast({
							icon: 'none',
							title: res.message
						})
					})

				}
			},
			sendCode() {
				let that = this
				if (that.loginShow === false) {
					if (!that.form.username) {
						return uni.showToast({
							title: '请输入手机号',
							icon: 'none'
						})
					}
				}
				that.fui.usersendcode({
						mobile: that.form.username,
						type: 'register'
					})
					.then(res => {
						that.errFlag = true
						console.log(res)
					}).catch(res => {
						that.errFlag = false
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
					})
				setTimeout(() => {
					if (that.errFlag) {
						that.timerFlag = true
						that.timer = setInterval(() => {
							that.countDown--
						}, 1000)
					}
				}, 1000)

			}
		}
	}
</script>

<style>
	.login {
		position: relative;
		width: 100vw;
		height: 100vh;
		background: url('https://oss.ddicms.cn/member/login/login_bg.png') no-repeat;
		background-size: cover;
	}

	.login_mask {
		position: absolute;
		top: 0;
		left: 0;
		background-color: rgba(0, 0, 0, .5);
		width: 100vw;
		height: 100vh;

	}

	.login_header {
		position: absolute;
		top: 88rpx;
		left: 0;
		width: 100vw;
		height: 88rpx;
		background: transparent;
		padding: 0 32rpx;
		color: #fff;
		box-sizing: border-box;
	}

	.login_title {
		position: absolute;
		top: 220rpx;
		left: 64rpx;
		font-size: 48rpx;
		font-weight: 600;
		color: #FFFFFF;
	}

	.login_form {
		position: absolute;
		top: 420rpx;
		width: 100vw;
		padding: 0 64rpx;
		box-sizing: border-box;
	}

	.login_form_code {
		position: relative;
	}

	.login_form_code_time {
		position: absolute;
		right: 24rpx;
		top: 50%;
		transform: translateY(-50%);
	}

	.input_item {
		width: 622rpx;
		height: 80rpx;
		background: rgba(255, 255, 255, .1);
		border-radius: 40rpx;
		text-indent: 24rpx;
		color: #fff;
	}

	.login_btn {
		margin-top: 64rpx;
		height: 80rpx;
		line-height: 80rpx;
		text-align: center;
		background: #00AFC7;
		border-radius: 40rpx;
		color: #fff;
		font-size: 28rpx;
		letter-spacing: 4rpx;
	}

	.login_eye {
		position: relative;
	}

	.login_eye image {
		position: absolute;
		top: 20rpx;
		right: 28rpx;
		width: 50rpx;
		height: 40rpx;
		color: #fff;
	}
</style>