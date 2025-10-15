<template>
	<view>
		<!-- 点击收藏模态框 -->
		<u-modal class="model-wish" title="添加心愿单" :show="show"  @confirm="selectWish"
			:showCancelButton="true" @cancel="cancelModal" confirmColor="#00AFC7">
			<view class="content-wish">
				<view class="">
					<u-input class="input-wish" placeholder="创建心愿单" border="surround" v-model="foundWishTxt">
						<template slot="suffix">
							<u-button class="createWish" @tap="foundWishBtn" text="创建" type="primary" size="mini"></u-button>
						</template>
					</u-input>
				</view>
				<view class="modal_txt">
					<u-radio-group v-model="cate_id" placement="column" @change="groupChange">
						<u-radio class="modal_txt_title" shape="square" :customStyle="{marginBottom: '8px'}"
							v-for="(item, index) in wishList" :key="index" :label="item.title" :name="item.id">
							<view class="modal_txt_title">
								{{item.title}}
							</view>
							<view class="modal_txt_total">
								{{item.total}}
							</view>
						</u-radio>
					</u-radio-group>
				</view>
			</view>
		</u-modal>
	</view>
</template>

<script>
	export default {
		name: "hotel-wish",
		data() {
			return {
				cate_id: 0,
				showWish: false,
				foundWishTxt: '', //添加心愿单
				wishList: [], //心愿列表
			};
		},
		watch: {
			show(n) {
				this.showWish = n
				if (n) {
					this.cate_id = 0
					this.getWishList()
				}
			}
		},
		props: {
			show: {
				type: Boolean,
				default: false
			},
			hotelId: {
				type: Number,
				default: 0
			},
			roomId: {
				type: Number,
				default: 0
			},
			roomType: {
				type: Number,
				default: 1
			},
		},
		methods: {
			groupChange(n) {
				this.cate_id = n
				console.log('groupChange', n);
			},
			// 酒店详情
			hotelDetail(item) {
				let that = this
				that.$Router.push({
					name: "querydetail",
					params: {
						hotel_id: item.id
					}
				})
				// 本地存储
				uni.setStorageSync('setTime', JSON.stringify(that.setObj))
			},
			//房间列表
			goHotelQuery() {
				let that = this
				that.$Router.push({
					name: 'hotelquery'
				})
				// 本地存储
				uni.setStorageSync('setTime', JSON.stringify(that.setObj))
			}, // 酒店心愿开始
			cancelModal() {
				this.$emit('cancel')
			},
			getWishList() {
				let that = this
				that.fui
					.request('/diandi_hotel/wish/list', 'GET', {}, false)
					.then(res => {
						that.wishList = res.data
					}).catch(res => {
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
					})
			},
			selectWish() {
				let that = this
				that.fui
					.request('/diandi_hotel/wish/addwish', 'POST', {
						hotel_id: that.hotelId,
						room_id: that.roomId, // 酒店固定是0
						cate_id: that.cate_id
					}, false)
					.then(res => {
						if (res.code === 200) {
							this.$emit('success', {
								roomType: this.roomType
							})
							this.$emit('cancel')
						}
					}).catch(res => {
						console.log('错误', res)
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
					})
			},
			foundWishBtn() {
				let that = this
				if (that.foundWishTxt !== '') {
					that.fui
						.request('/diandi_hotel/wish/add', 'POST', {
							title: that.foundWishTxt
						}, false)
						.then(res => {
							if (res.code === 200) {
								uni.showToast({
									title: "心愿单创建成功",
									icon: 'none'
								})
								that.getWishList()
								that.foundWishTxt = ''
							}
						}).catch(res => {
							console.log('错误', res)
							uni.showToast({
								title: res.message,
								icon: 'none'
							})
						})
				} else {
					uni.showToast({
						title: '心愿单名称不能为空',
						icon: 'none'
					})
				}
			}
		}
	}
</script>
<style lang="scss" scoped>
	.model-wish {

		::v-deep {
			.u-modal {
				width: 686rpx;
				background: #FFFFFF;
				border-radius: 40rpx 40rpx 40rpx 40rpx;
				opacity: 1;
				.content-wish {
					width: 586rpx;	
				}
			}

			.input-wish {
				width: 522rpx;
				height: 72rpx;
				background: #e6e6e6;
				border-radius: 20rpx 20rpx 20rpx 20rpx;
				// opacity: 1;
			}

			.u-modal__title {
				height: 50rpx;
				margin-top: 32rpx;
				margin-bottom: 32rpx;
				padding-top: 0px;
				font-size: 36rpx;
				font-weight: 600;
				color: #000000;
				line-height: 36rpx;
			}
			.modal_txt {
				margin-top: 20rpx;

				.modal_txt_item {
					margin: 0 22rpx;

					.modal_txt_title {
						width: 160rpx;
						height: 45rpx;
						font-size: 32rpx;
						font-weight: 400;
						color: #000000;
						line-height: 32rpx;
					}

					.modal_txt_total {
						width: 17rpx;
						height: 40rpx;
						font-size: 28rpx;
						font-weight: 300;
						color: #999999;
						line-height: 28rpx;
					}
				}

			}
		}
	}
</style>