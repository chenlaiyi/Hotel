<template>
	<view>
		<view class="content">
			<!-- <view> -->
			<view class="container">
			  <view class="wechatapp">
			    <!-- <image class="header" src="/images/wechatapp.png"></image> -->
			    <view class="header">
			      <open-data class="" type="userAvatarUrl"></open-data>
			    </view>
			  </view>
			  <view class="auth-title">申请获取以下权限</view>
			  <view class="auth-subtitle">获得你的公开信息（昵称、头像等）</view>
			  <view class="login-btn">
			    <button class="btn-normal" open-type="getPhoneNumber" @getphonenumber="wechatLogin" lang="zh_CN">授权登录</button>
			  </view>
			  <view class="no-login-btn">
			    <button class="btn-normal" @click="onNotLogin">暂不登录</button>
			  </view>
			</view>
			<!-- </view> -->
		</view>
	</view>
</template>

<script>
export default {
	data() {
		return {
			backurl:'',
			code:''
		};
	},
	onShow(){
		this.initCode()
	},
	onLoad(e) {
		let _this = this
		_this.backurl = e.back
	},
	methods: {
		 initCode(){
			 let _this = this
			 uni.login({
			 	provider: 'weixin',
			 	success: function(loginRes) {
			 		_this.code = loginRes.code
			 	},
			 	fail(e) {
			 		reject(e)
			 		console.log('e1',e)
			 	}
			 });
		 },
		 getMobile(detail){
			let _this = this
			uni.showLoading();
			return new Promise((resolve, reject) => {
				const res =  _this.fui.request("/wechat/decrypt/msg", "POST",Object.assign(detail,{
					code:_this.code,
					encryptedData:encodeURIComponent(detail.encryptedData)
				}) , false).then(res=>{
					uni.hideLoading();
					resolve(res)
				}).catch(res=>{
					reject(res)
				})
			})
			
		},
		async wechatLogin(e){
			let _this = this
			console.log(e.detail)
			//#ifdef MP-WEIXIN
			// 登录态过期
			await _this.fui.getUserInfo(function(res){
				console.log('res',res);
				console.log('登录以后',_this.backurl)
				uni.navigateBack({
				})
			})
			// ,mobileRes.data.phoneNumber
			//#endif
		},
		onNotLogin(e){
			this.$Router.push({name:'index'})
		}
	}
};
</script>

<style lang="scss" scoped>
	.container {
	  padding: 0 60rpx;
	}
	
	.wechatapp {
	  padding: 80rpx 0 48rpx;
	  border-bottom: 1rpx solid #e3e3e3;
	  margin-bottom: 72rpx;
	  text-align: center;
	}
	
	.wechatapp .header {
	  width: 190rpx;
	  height: 190rpx;
	  border: 2px solid #fff;
	  margin: 0rpx auto 0;
	  border-radius: 50%;
	  overflow: hidden;
	  box-shadow: 1px 0px 5px rgba(50, 50, 50, 0.3);
	}
	
	.auth-title {
	  color: #585858;
	  font-size: 34rpx;
	  margin-bottom: 40rpx;
	}
	
	.auth-subtitle {
	  color: #888;
	  margin-bottom: 88rpx;
	  font-size: 28rpx;
	}
	
	.login-btn {
	  padding: 0 20rpx;
	}
	
	.login-btn button {
	  height: 88rpx;
	  line-height: 88rpx;
	  background:  #218569;
	  color: #fff;
	  font-size: 30rpx;
	  border-radius: 999rpx;
	  text-align: center;
	}
	
	.no-login-btn {
	  margin-top: 20rpx;
	  padding: 0 20rpx;
	}
	
	.no-login-btn button {
	  height: 88rpx;
	  line-height: 88rpx;
	  background: #dfdfdf;
	  color: #fff;
	  font-size: 30rpx;
	  border-radius: 999rpx;
	  text-align: center;
	}
	/* //授权弹框 start */
		.auth_wrap {
			position: fixed;
			width: 100%;
			bottom: -120%;
			transition: all 0.35s linear;
	
			&.show {
				bottom: 0;
				transition: all 0.35s linear;
	
				.mask {
					display: block;
				}
			}
	
			.mask {
				width: 100%;
				height: 100vh;
				position: fixed;
				background: rgba(0, 0, 0, 0.5);
				z-index: 98;
				top: 0;
				display: none;
			}
	
			.auth_content {
				padding: 32rpx 32rpx 72rpx 32rpx;
				position: relative;
				z-index: 99;
				background: #fff;
				border-radius: 16rpx 16rpx 0 0;
	
				.auth_top {
					position: relative;
	
					.ptitle {
						font-size: 30rpx;
						font-weight: bold;
						margin-bottom: 24rpx;
					}
	
					.txt {
						font-size: 26rpx;
						color: #999;
					}
	
					.close {
						width: 26rpx;
						height: 26rpx;
						position: absolute;
						right: 0;
						top: 0;
	
						image {
							width: 100%;
							height: 100%;
						}
					}
				}
	
				.auth_ul {
					margin-top: 16rpx;
	
					.auth_li {
						display: flex;
						align-items: center;
						border-top: solid 1px #f5f5f5;
						padding: 24rpx 0;
	
						&:last-child {
							border-bottom: solid 1px #f5f5f5;
						}
	
						.tit {
							width: 140rpx;
							font-size: 30rpx;
						}
	
						.rit {
							width: calc(100% - 140rpx);
	
							input {
								font-size: 28rpx;
								height: 72rpx;
								width: 100%;
							}
	
							image {
								width: 54rpx;
								height: 54rpx;
								border-radius: 50%;
							}
	
							button {
								width: 100%;
								height: 72rpx;
								background: #fff;
								text-align: left;
								padding: 0;
	
								&:after {
									border: solid 1px #fff;
								}
	
								// opacity: 0;
							}
						}
					}
				}
	
				.confirm_btn {
					width: 420rpx;
					margin: 46rpx auto 0 auto;
					background: #39b54a;
					color: #fff;
					border-radius: 8rpx;
					height: 94rpx;
					display: flex;
					align-items: center;
					justify-content: center;
					font-size: 30rpx;
				}
			}
		}
	
		/* //授权弹框 end */
</style>
