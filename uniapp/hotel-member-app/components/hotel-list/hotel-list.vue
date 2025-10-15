<template>
	<view>
		<view v-for="(item,i) in hotelList" :key="i">
			<view class="fui-flex justify-between margin-left_7 margin-top-lg" @click="goHotelQuery">
				<view class="text-xl color12 text-bold">{{item.advertise.name}}</view>
				<view class="all_btn fui-flex">
					<view class="">
						全部
					</view>
					<view class="margin-left-xs">
						<iconfont className="icon-youjiantou1" :size="6" color="#999999"></iconfont>
					</view>
				</view>
			</view>
			<!-- 列表 -->
			
			<hotel-items :hotel-list="item.list" @getHotels="getCommonIndex('hotList', 1)"></hotel-items>	
		</view>
		
	</view>
</template>

<script>
	export default {
		name: 'HotelList',
		data() {
			return {
				hotelList:[]
			}
		},
		props:{
			page:{
				type:String,
				default:'hotelIndex'
			}
		},
		created() {
			console.log('酒店列表-init')
			this.getCommonIndex('hotList', 1)
		},
		methods: {
			// type共用接口
			getCommonIndex(dataArray, type) {
				let that = this
				that.fui
					.request('/diandi_hotel/mobile/index/ad', 'GET', {
						type: type,
						page: that.page
					}, false)
					.then(res => {
						console.log('广告', res)
						that.hotelList = res.data
					}).catch(res => {
						console.log('错误', res)
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
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