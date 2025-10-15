<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="身份认证" color="#000000" :border="false" fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="card">
			<view class="fui-flex margin-tb-lg invo-list">
				<view class="margin-right-lg"> 是否本人 </view>
				<view class="fui-flex">
					<radio-group class="uni-list" @change="radioChange">
						<label class="uni-list-cell uni-list-cell-pd margin-right-sm"
							v-for="(item, index) in radioItems" :key="index">
							<radio color="#00AFC7" style="transform: scale(0.6)" :id="item.status" :value="item.status"
								:checked="item.checked"></radio>
							<label class="label-2-text" :for="item.status">
								<text>{{ item.value }}</text>
							</label>
						</label>
					</radio-group>
				</view>
			</view>
			<!-- 身份验证 -->
			<view class="identity-mes" v-if="isSelf === 1">
				<view class="margin-bottom-xl">
					<u-divider text="上传人脸照片" textPosition="left"></u-divider>
				</view>
				<view class="auth-main">
					 <u-row customStyle="margin-bottom: 10px">
						<u-col span="6">
							<view class="auth-img">
								<view class="auth-img_scan">
									<image :src="tempFilePaths ? tempFilePaths : url" :mode="tempFilePaths ? 'center' : ''">
									</image>
								</view>
								<view class="auth-absolute"></view>
								
							</view>
						</u-col>
						<u-col span="6">
							<view class="">
								<button class="search_btn text-white background3 fui-center" @click="takePictures">
									点击拍照
								</button>
							</view>
						</u-col>
					</u-row>
					<uni-row>
						<view class="auth-text">请确保正脸出现在取景框中，以便获取完整的照片</view>
					</uni-row>				
					
					
				</view>
				
			
				
			</view>
			<view class="identity-mes padding-bottom-sm">
				<view class="margin-bottom-xl">
					<u-divider text="上传证件照片" textPosition="left"></u-divider>
				</view>
				<view class="fui-flex justify-between">
					<view class="identity-mes_iscard margin-right-sm" @tap="frontPic" v-if="tempPathFront === ''">
						<view class="correct">
							<image src="https://oss.ddicms.cn/member/person/correct.png" mode="aspectFit"
								style="width: 100%;height: 100%;">
							</image>
						</view>
						<view class="identity-mes_correct fui-flex flex-direction justify-center">
							<view class="identity-mes_iscard_icon">
								<iconfont className="icon--camera" :size="24" color="#fff"></iconfont>
							</view>
							<view class="identity-mes_iscard_text margin-bottom-xs">
								上传人像面
							</view>
						</view>
					</view>
					<view class="identity-mes_iscard iscard_replace margin-right-sm" @tap="frontPic" v-else>
						<view class="identity-mes_iscard_img">
							<image :src="tempPathFront" mode="scaleToFill" style="width: 328rpx;height: 196rpx;">
							</image>
							<view class="iscard_replace_abs">
								重新上传
							</view>
						</view>
					</view>

					<view class="identity-mes_iscard" @tap="backPic" v-if="tempPathBack===''">
						<view class="reverse">
							<image src="https://oss.ddicms.cn/member/person/reverse.png" mode="aspectFit"
								style="width: 100%;height: 100%;">
							</image>
						</view>
						<view class="identity-mes_reverse fui-flex flex-direction justify-center">
							<view class="identity-mes_iscard_icon">
								<iconfont className="icon--camera" :size="24" color="#fff"></iconfont>
							</view>
							<view class="identity-mes_iscard_text margin-bottom-xs">
								上传国徽面
							</view>
						</view>
					</view>
					<view class="identity-mes_iscard iscard_replace" @tap="backPic" v-else>
						<view class="identity-mes_iscard_img">
							<image :src="tempPathBack" mode="scaleToFill" style="width: 328rpx;height: 196rpx;">
							</image>
							<view class="iscard_replace_abs">
								重新上传
							</view>
						</view>
					</view>
				</view>
				
				<view class="padding-0 invo-list">
					<view class="margin-bottom-xl">
						<u-divider text="证件信息" textPosition="left"></u-divider>
					</view>
					<view class="fui-flex invo-list_input">
						<view class="fui-col-4 invo-list_text"> 姓名 </view>
						<view class="fui-col-8">
							<input class="uni-input" type="text" @input="formInput($event,'realname')"
								placeholder="请输入真实姓名" placeholder-class="placeholder" />
						</view>
					</view>
					<view class="fui-flex invo-list_input">
						<view class="fui-col-4 invo-list_text"> 身份证号 </view>
						<view class="fui-col-8">
							<input class="uni-input" type="idcard" @input="formInput($event,'icard_code')"
								placeholder="请输入身份证号" placeholder-class="placeholder" />
						</view>
					</view>
					<view class="fui-flex invo-list_input">
						<view class="fui-col-4 invo-list_text"> 手机号 </view>
						<view class="fui-col-8">
							<input class="uni-input" type="number" @input="formInput($event,'mobile')"
								placeholder="请输入手机号码" placeholder-class="placeholder" v-model="mobile" />
						</view>
					</view>
				</view>
			</view>
			


			<view class="" v-if="isSelf === 0">
				<view class="padding-0 invo-list">
					<view class="fui-flex invo-list_input">
						<view class="fui-col-3 invo-list_text"> 手机号 </view>
						<view class="fui-col-9" style="position: relative">
							<input class="uni-input" type="number" placeholder="请输入手机号码" placeholder-class="placeholder"
								@input="formInput($event,'mobile')" />
							<view class="text-26 color4" style="position: absolute; right: 0; top: 5rpx">
								发送验证短信
							</view>
						</view>
					</view>
				</view>
			</view>
			
			<view class="margin-list">
				<view class="margin-list_label fui-flex justify-center">
					<radio-group @change="radioFn">
						<label class="radio">
							<radio color="#00AFC7" style="transform: scale(0.6)" :value="true" />
						</label>
					</radio-group>
						
					<view class="radio_text">
						我已阅读并同意<text class="color4" @click="agreement('bottom')">用户协议</text>和<text class="color4"
							@click="agreement('bottom')">隐私协议</text>
					</view>
				</view>
				<button class="serve-btn text-white background3 fui-center" @click="saveFace">
					{{ verifyTxt }}
				</button>
			</view>
		</view>
		<avatar @upload="myUpload" ref="avatar"></avatar>

		<!-- 同意协议弹框 -->
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
	import avatar from "../components/yq-avatar/yq-avatar.vue";
	import face from "@/static/svgs/face.svg"
	export default {
		components: {
			avatar,
		},
		data() {
			return {
				tempFilePaths: "", // 图片地址
				tempFilePathsOld: "", // 图片地址
				url: '',
				urls: [],
				realname: "", //输入真实姓名
				icard_code: "", //输入真实身份证号
				mobile: "", // 手机号
				isSelf: 1,
				radioItems: [{
						value: "是",
						checked: "true",
						status: 1,
					},
					{
						value: "否",
						status: 0,
					},
				],
				verifyTxt: "身份核验",
				setUpinfoFlag: true, // 点击下一步互斥锁
				radioVal: false, // 同意单选
				radioVal1: false, // 同意单选
				// 切换状态
				showResult: true,
				checked: false,
				// 变量名
				idcard: '',
				tempPathBack: '', //身份证反面地址
				tempPathFront: '', //身份证正面地址
				icard_back: '',
				icard_front: '',
				//certifyStatus: undefined, //0未认证，1认证中，2认证成功，3认证失败
				order_id:''
			};
		},
		onLoad(options) {
			let that = this
			console.log(options,'33333333333333',that.$Route.query)
			that.order_id = that.$Route.query.order_id
			that.url = face
		},
		methods: {
			// 身份证正面照
			frontPic() {
				let that = this
				uni.chooseImage({
					count: 1,
					sizeType: ['original', 'compressed'],
					sourceType: ['album', 'camera'], //从相册选择
					success(res) {
						console.log(res)
						that.fui
							.uploadFile(res.tempFilePaths[0])
							.then(res => {
								console.log('身份证正面照',res)
								// console.log(res.data.url);
								that.tempPathFront = res.data.url
								that.icard_front = res.data.url
							})
							.catch(res => {
								console.log('cuowu', res);
							});
					}
				});
			},
			// 身份证反面照
			backPic(e) {
				let that = this
				uni.chooseImage({
					count: 1,
					sizeType: ['original', 'compressed'],
					sourceType: ['album', 'camera'], //从相册选择
					success(res) {
						that.fui
							.uploadFile(res.tempFilePaths[0])
							.then(res => {
								console.log(res.data.url)
								that.tempPathBack = res.data.url
								that.icard_back = res.data.url
							})
							.catch(res => {
								console.log('cuowu')
							})
					}
				});
			},
			// 同意
			agreeBtn() {
				let that = this
				that.$refs.popup.close()
				that.checked = true
			},
			agreement(type) {
				let that = this
				this.$refs.popup.open(type)
			},
			backBtn() {
				let that = this
				that.$refs.popup.close()
			},
			formInput(e, f) {
				console.log(e, f);
				let that = this
				if (f == 'realname') {
					that.realname = e.detail.value
				} else if (f == 'icard_code') {
					that.icard_code = e.detail.value
				} else if (f == 'mobile') {
					that.mobile = e.detail.value
				}
			},
			// 选中状态
			radioChange(e) {
				let checked = e.target.value;
				let changed = {};
				for (let i = 0; i < this.radioItems.length; i++) {
					if (checked.indexOf(this.radioItems[i].status) !== -1) {
						changed["radioItems[" + i + "].checked"] = true;
					} else {
						changed["radioItems[" + i + "].checked"] = false;
					}
				}
				this.isSelf = Number(e.detail.value);
				this.radioVal = false
				this.radioVal1 = false
				// 重置手机号
				this.mobile = "";
			},
			// 点击派拍照裁剪
			takePictures() {
				// uni.chooseImage({
				//     sizeType:['original'],
				//     success:(res)=>{
				//         console.log(res,'22222222222222222222');
				//     }
				// })
				this.$refs.avatar.fChooseImg(0, {
					selWidth: "300upx",
					selHeight: "300upx",
					expWidth: "260upx",
					expHeight: "260upx",
				});
			},
			// 压缩图片
			myUpload(rsp) {
				let that = this;
				that.setUpinfoFlag = false
				// 未裁剪的图片  rsp.path[1]
				console.log('myUpload', rsp)
				that.fui
					.uploadFile(rsp.path[1])
					.then((res) => {
						that.tempFilePathsOld = res.url;
						// // 裁剪压缩后得图片  rsp.path[0]

						const ext = rsp.path[0].split('.')[1]
						uni.compressImage({
							src: rsp.path[0],
							quality: 80,
							success: (res) => {
								console.log('myUpload1', res.tempFilePath)
								uni.getFileSystemManager().readFile({
									filePath: res.tempFilePath, // 要读取的文件路径
									encoding: 'base64', // 编码格式
									success: file => {
										console.log('上传图片成功', file)
										that.fui
											.uploadbaseimg(
												{images:`data:image/png;base64,${file.data}`})
											.then((res) => {
												that.tempFilePaths = res.data.url;
												that.setUpinfoFlag = true
											})
											.catch((res) => {
												console.log("图片压缩错误",res);
											});

									}
								})


							},
						});
					})
					.catch((res) => {
						console.log("cuowu");
					});

			},
			// 人脸数据保存
			saveFace() {
				let that = this;
				// console.log('saveFace',{
				//                         isSelf: that.isSelf,
				//                         realname: that.realname,
				//                         icard_code: that.icard_code,
				//                         face_img: that.tempFilePaths,
				//                         face_old: that.tempFilePathsOld,
				//                         mobile: that.mobile,
				//                     })
				if (this.isSelf) {
					if (!that.realname)
						return uni.showToast({
							title: "姓名不能为空",
							icon: "none",
						});
					if (!that.icard_code)
						return uni.showToast({
							title: "请输入正确的身份证号",
							icon: "none",
						});
				}

				if (that.mobile.length <= 0)
					return uni.showToast({
						title: "手机号不能为空",
						icon: "none",
					});
				that.setUpinfoFlag = false
				console.log(that.isSelf);
				console.log(that.realname);
				console.log(that.icard_code);
				console.log(that.tempFilePaths);
				console.log(that.tempFilePathsOld);
				console.log(that.mobile);
				that.$api.hotelGztSaveface(
							that.isSelf,
							that.order_id,
							that.icard_front,
							that.icard_back,
							that.realname,
							that.icard_code,
							that.tempFilePaths,
							that.tempFilePathsOld,
							that.mobile
					)
					.then((res) => {
						that.$Router.push({
							name: "myorderDetails",
							params: {
								order_id: that.order_id
							}
						});
						that.verifyTxt = "下一步";
						that.setUpinfoFlag = true
					})
					.catch((res) => {
						uni.showToast({
							title: res.message,
							icon: "none",
						});
						that.$Router.push({
							name: "myorderDetails",
							params: {
								order_id: that.order_id
							}
						});
						that.verifyTxt = "下一步";
						that.setUpinfoFlag = true
					});
			},
			radioFn(e) {
				this.radioVal = Boolean(e.detail.value)
			},
			radioFn1(e) {
				this.radioVal1 = Boolean(e.detail.value)
			},
			// 提交
			submit() {
				let that = this
				if (that.setUpinfoFlag) {
					that.saveFace();
				}

				if (
					that.realname.length === 0 &&
					that.icard_code.length === 0 &&
					that.mobile.length === 0
				) {
					that.verifyTxt = "下一步";
				} else {
					that.verifyTxt = "正在核验";
				}
			},
			// 修改用户信息
			setUpinfo() {
				let that = this;
				// if (that.isSelf) {
				//     if (that.radioVal) {
				//     } else {
				//         uni.showToast({
				//             title: '请勾选协议后，在点击下一步',
				//             icon: 'none'
				//         })
				//     }
				// } else {
				//     if (that.radioVal1) {
				//         that.submit()
				//     } else {
				//         uni.showToast({
				//             title: '请勾选协议后，在点击下一步',
				//             icon: 'none'
				//         })
				//     }
				// }

				if (that.radioVal1 || that.radioVal) {
					that.submit()
				} else {
					uni.showToast({
						title: '请勾选协议后，在点击下一步',
						icon: 'none'
					})
				}
			},
		},
	};
</script>

<style lang="scss" scoped>
	.auth-main {
		padding: 30rpx 0;
		.auth-img {
			width: 250rpx;
			height: 250rpx;
			margin-top:30rpx;
			
			.auth-img_scan {
				width: 260rpx;
				height: 260rpx;
				border: 1rpx dotted #00afc7;
				border-radius: 10rpx;
				box-sizing: border-box;
				display: flex;
				align-items: center;
				justify-content: center;
				image {
					width: 260rpx;
					height: 260rpx;
				}
			}
		}
		.auth-text {
			padding: 16rpx 0;
		}
	}
	

	.search_btn {
		width: 200rpx;
		height: 64rpx;
		font-size: 24rpx;
		border-radius: 32rpx;
	}

	.invo-list {
		padding: 0rpx 32rpx;
		margin-bottom: 0;
	}

	.invo-list_input {
		padding: 24rpx 0;
		border-bottom: 2rpx solid #f5f5f5;
	}

	.invo-list_text {
		font-size: 28rpx;
		color: #000;
	}

	.uni-input {
		font-size: 28rpx;
	}

	.placeholder {
		font-size: 28rpx;
		color: #d1d1d1;
		font-weight: 600;
	}

	.margin-list {
		margin: 0 32rpx;
	}

	.margin-list_label {
		margin: 45rpx 0 22rpx;
	}

	.radio_text {
		font-size: 24rpx;
	}

	.serve-btn {
		width: 100%;
		height: 72rpx;
		font-size: 28rpx;
		border-radius: 10rpx;
	}

	.card {
		margin: 0 32rpx;
		padding: 0;
		box-shadow: 0rpx 5rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
		border-radius: 20rpx;

		.identity-mes {
			margin: 56rpx 32rpx 0;

			.identity-mes_title {
				font-size: 30rpx;
				font-weight: 600;
				padding-top: 32rpx;
				border-bottom: 1px dotted #00AFC7;
				padding-bottom: 32rpx;
			}

			.identity-mes_inp {
				padding: 32rpx 0;
				border-bottom: 1rpx solid #F5F5F5;
			}

			.identity-mes_inp_title {
				font-size: 28rpx;
			}

			.identity-mes_iscard {
				position: relative;
				width: 300rpx;
				height: 180rpx;
				padding: 12rpx;
				background-color: #F9F9F9;

				.reverse {
					width: 100%;
					height: 100%;
					position: relative;
				}

				.identity-mes_reverse {
					position: absolute;
					left: 50%;
					bottom: 12rpx;
					transform: translateX(-50%);

					.identity-mes_iscard_icon {
						width: 72rpx;
						height: 72rpx;
						line-height: 72rpx;
						text-align: center;
						background-color: #00AFC7;
						border-radius: 50%;
					}

					.identity-mes_iscard_text {
						font-size: 24rpx;
						color: #999999;
						margin-top: 18rpx;
					}
				}

				.correct {
					width: 100%;
					height: 100%;
					position: relative;
				}

				.identity-mes_correct {
					position: absolute;
					left: 50%;
					bottom: 12rpx;
					transform: translateX(-50%);

					.identity-mes_iscard_icon {
						width: 72rpx;
						height: 72rpx;
						line-height: 72rpx;
						text-align: center;
						background-color: #00AFC7;
						border-radius: 50%;
					}

					.identity-mes_iscard_text {
						font-size: 24rpx;
						color: #999999;
						margin-top: 18rpx;
					}
				}
			}

			.iscard_replace {
				background-color: transparent;
				padding: 0;

				.identity-mes_iscard_img {
					position: relative;
					width: 100%;
					height: 100%;

					.iscard_replace_abs {
						position: absolute;
						left: 0;
						top: 0;
						width: 100%;
						height: 100%;
						background-color: rgba(0, 0, 0, .3);
						color: #FFFFFF;
						text-align: center;
						line-height: 180rpx;
					}
				}
			}

			.identity-mes_check {
				margin: 54rpx 0 24rpx;

				.identity-mes_txt {
					font-size: 24rpx;
					color: #999999;
				}
			}
		}
	}

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