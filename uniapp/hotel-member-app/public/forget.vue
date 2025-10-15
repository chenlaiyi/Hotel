<template>
	<view class="login">
		<view class="login_mask"></view>
		<view class="login_title">
			忘记密码
		</view>
		<!-- 登录 -->
		<view class="login_form">
			<view class="">
				<input type="text" placeholder="请输入账号" class="input_item" v-model="form.username">
			</view>
			<view class="">
				<u--input name="code" class="send_code" placeholder="验证码" @input="loginCodeChange($event,'code')">
					<template slot="suffix">
						<u-code ref="uCode" @change="codeChange" seconds="20" changeText="X秒重新获取"></u-code>
						<u-button @tap="getCode" :text="tips" type="code-btn" size="mini"></u-button>
					</template>
					</u-input>
				</u--input>
			</view>
			<view class="login_eye">
			
				<input type="password" placeholder="请输入密码" class="input_item" style="margin-top: 40rpx;"
					v-model="form.password" v-if="!eye_change">
				<input type='text' class="input_item" autocomplete="off" placeholder="请输入密码" style="margin-top: 40rpx;"
					v-model="form.password" v-else>
				<image src="@/static/svgs/p_hide.svg" mode="" v-if="!eye_change" @click="eye_change = true"></image>
				<image src="@/static/svgs/p_show.svg" mode="" v-else @click="eye_change = false"></image>
			</view>
			<view class="login_btn" @click="loginSubmitFn">
				登录
			</view>
		</view>
		
	</view>
</template>

<script>
	export default {
		data() {
			return {
				tips: '',
				// refCode: null,
				seconds: 10,
				// 展示
				loginShow: true,
				passwordType: true,
				MutexFlag: true,
				show: 'left',
				// 数据
				form: {
					mobile: '',
					password: '',
					code: '',
					username: '',
				},
				checkboxValue: '',
				choseAgreement: 'false',
				AgreementLength: 0,
				countDown: 30,
				// 倒计时
				timer: null,
				timerFlag: false,
				errFlag: true,
				// 倒计时
				// 互斥锁
				registerFlag: true
				// 互斥锁
			}
		},
		watch: {
			countDown(val) {
				if (val === 0) {
					clearInterval(this.timer)
					this.countDown = 30
					this.timerFlag = false
				}
			}
		},
		onLoad() {},
		methods: {
			codeChange(text) {
				this.tips = text;
			},
			getCode() {
				const that = this
				if (that.$refs.uCode.canGetCode) {
					// 模拟向后端请求验证码
					uni.showLoading({
						title: '正在获取验证码'
					})
					that.fui.usersendcode('forgetpass',this.form.mobile).then(res=>{
						uni.hideLoading();
						// 这里此提示会被this.start()方法中的提示覆盖
						uni.$u.toast('验证码已发送');
						// 通知验证码组件内部开始倒计时
						that.$refs.uCode.start();
					}).catch(err=>{
						uni.$u.toast(err.message);
					})
				} else {
					uni.$u.toast('倒计时结束后再发送');
				}
			},
			end() {
				uni.$u.toast('倒计时结束');
			},
			start() {
				uni.$u.toast('倒计时开始');
			},			
			loginCodeChange(e, key) {
				console.log(e, key)
				this.$set(this.form, key, e)
			},
			loginInputChange(e, key) {
				this.$set(this.form, key, e.detail.value)
			},
			// 记住密码
			checkboxChange() {

			},
			// 重置表单
			resetFn() {
				console.log('重置表单', this.form)
				this.form = {
					mobile: '',
					password: '',
					code: '',
					username: '',
				}
			},
			// 登录
			loginFn() {
				this.show = 'left'
				this.loginShow = true
				this.resetFn()
			},
			// 注册
			registerFn() {
				this.show = 'right'
				this.loginShow = false
				this.resetFn()
			},
			// 登录
			setPassSubmitFn(e) {
				let that = this
				if (that.MutexFlag) {
					// 非空判断
					if (that.loginShow) {
						if (!that.form.mobile) {
							return uni.showToast({
								title: '请输入手机号',
								icon: 'none'
							})
						}
						if (!that.form.password) {
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

					that.fui.userforgetpass(that.form.mobile, that.form.code, that.form.password).then(async res => {
						that.$Router.push({
							name: 'login'
						})
					}).catch(err => {
						console.log('错误', err)
						that.MutexFlag = true
						uni.showToast({
							title: err.message,
							icon: 'none'
						})
					})
				}
			},
			changeAgreement(e) {
				this.AgreementLength = e.detail.value.length
			},
			// 注册
			register(e) {
				let that = this
				that.form = e.detail.value
				if (that.registerFlag) {
					// 互斥锁
					that.registerFlag = false
					if (this.AgreementLength) {
						// 切换到注册页
						if (that.loginShow === false) {
							// 非空判断
							if (!that.form.mobile) {
								that.registerFlag = true
								return uni.showToast({
									title: '请输入手机号',
									icon: 'none'
								})
							}
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
						that.fui.userRegister('mobile', that.form.mobile, null, that.form.password).then(res => {
							uni.hideLoading()
							uni.showToast({
								icon: 'none',
								title: res.message,
							})
							that.registerFlag = true
							console.log('this.form', this.form)
							that.loginFn()
						}).catch(res => {
							console.log('错误', res)
							that.registerFlag = true
							uni.showToast({
								icon: 'none',
								title: res.message
							})
						})
					} else {
						that.registerFlag = true
						uni.showToast({
							icon: 'none',
							title: '请勾选协议',
						})
					}
				}
			},
			// 发送验证码
			sendCode() {
				let that = this
				if (that.loginShow === false) {
					if (!that.form.mobile) {
						return uni.showToast({
							icon: 'none',
							title: '请输入手机号'
						})
					}
				}

				that.fui.request('/user/register', 'POST', {
					mobile: that.form.mobile,
					type: 'register'
				}).then(res => {
					that.errFlag = true
					// console.log(res);
				}).catch(err => {
					that.errFlag = false
					uni.showToast({
						icon: 'none',
						title: err.message
					})
				})

				setTimeout(() => {
					if (that.errFlag) {
						if (!that.timerFlag) {
							that.timerFlag = true
							that.timer = setInterval(() => {
								that.countDown--
							}, 1000)
						}
					}
				}, 1000)
			}
		}
	}
</script>
<style lang="scss" scoped>
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
	
	.send_code {
		height: 50px;
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