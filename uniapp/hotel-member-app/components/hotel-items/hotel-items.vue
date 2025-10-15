<template>
	<view>
		<!-- 列表 -->
		<view class="room-scorll-list fui-flex" v-for="(item1,index1) in hotelList" :key="index1">
			<view class="room-scorll-list_img margin-right-sm">
				<image :src="item1.thumb" mode="widthFix" style="width: 100%;height: 100%;border-radius: 10rpx;"
					@click="hotelDetail(item1)"></image>
				<view class="room-scorll-list_img_icon" @click="collectBtn(item1)">
					<iconfont :className="item1.is_like ? 'icon-full-love' : 'icon-love'" :size="20"
						:color="item1.is_like ? '#FB5B32' : '#ffffff'"></iconfont>
				</view>
			</view>
			<view class="room-scorll-list_info" @click="hotelDetail(item1)">
				<view class="room-scorll-list_title font_size_18 text-bold">
					{{item1.name}}
				</view>
				<view class="fui-flex margin-top-sm">
					<view class="room-scorll-list_num font_size_12 text-bold color14">
						{{item1.level}}
					</view>
					<view class="room-scorll-list_txt margin-left-xs color13">
						{{item1.comment_num}}条评论
					</view>
				</view>
				<view class="fui-flex margin-top-xs">
					<view class="">
						<iconfont className="icon-a--06" :size="16"></iconfont>
					</view>
					<view class="room-scorll-list_km margin-left-xs">
						距{{item1.distance}}km
					</view>
					<view class="room-scorll-list_km">
						{{item1.address}}
					</view>
				</view>
				<view class="fui-flex margin-bottom-xs" style="flex-wrap: wrap;">
					<view class="room-scorll-list_assess" v-for="item2 in item1.server" :key="item2.id">
						<view class="">
							{{item2.title}}
						</view>
					</view>
				</view>
				<view class="fui-flex justify-between">
					<view class="room-scorll-list_coupon">
						{{item1.is_use_coupon ? '可用券' : '不可用券'}}
					</view>
					<view class="fui-flex">
						<view class="margin-left-xs font_size_12">
							<text class="font_size_25 color6 text-bold">{{item1.price}}</text>/{{item1.time_length}}
						</view>
					</view>
				</view>
			</view>
		</view>

		<hotel-wish :show="showModal" :hotelId="hotelId" @cancel="hideWish" @success="wishSuccess"></hotel-wish>
		
	</view>
</template>

<script>
	export default {
		name: 'HotelItems',
		data() {
			return {
				hotelId:0,
				showModal: false,
			}
		},
		props: {
			hotelList: {
				type: Array,
				default () {
					return []
				}
			}
		},
		methods: {
			hideWish(){
				this.showModal = false
			},
			wishSuccess(){
				this.$emit('getHotels', {
					type: 1
				})
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
			},
			collectBtn(item) {
				let that = this
				that.hotelId = item.id
				console.log('is_like',item.is_like)
				if (!item.is_like) {
					that.showModal = true
				} else {
					that.showModal = false
					that.fui
						.request('/diandi_hotel/wish/delwish', 'POST', {
							hotel_id: item.id,
							room_id: 0, // 酒店固定是0
						}, false)
						.then(res => {
							this.$emit('getHotels', {
								type: 1
							})
						}).catch(res => {
							console.log('错误', res)
							uni.showToast({
								title: res.message,
								icon: 'none'
							})
						})
				}
			},
			//房间列表
			goHotelQuery() {
				let that = this
				that.$Router.push({
					name: 'hotelquery'
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.all_btn {
		font-size: 22rpx;
		color: #999999;
	}

	.room-scorll-list {

		margin-top: 32rpx;

		.room-scorll-list_img {
			position: relative;
			width: 200rpx;
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

	
</style>