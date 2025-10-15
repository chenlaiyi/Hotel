<template>
	<view>
		<view v-for="(item,ins) in homestayList" :key="ins">
			<view class="fui-flex justify-between margin-left_7 margin-top-lg" @click="goApartMent">
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
			<view class="">
				<homestay-item :homestayList="item.list" @getHotels="getCommonIndex('hotList', 2)"></homestay-item>
			</view>
		</view>
		<hotel-wish :show="showModal" :hotelId="hotelId" @cancel="hideWish" @success="wishSuccess"></hotel-wish>
	</view>
</template>

<script>
	export default {
		name:"homestay-list",
		data() {
			return {
				homestayList:[],	
				greColor: '#00AFC7',
				yelColor: '#FF9500',
				hotelId:0,
				showModal: false,
			}
		},
		props:{
			page:{
				type:String,
				default:'index'
			},
		},
		created() {
			console.log('民宿列表读取');
			this.getCommonIndex('homestayList', 2)
		},
		methods: {
			hideWish(){
				this.showModal = false
			},
			wishSuccess(){
				this.getCommonIndex('homestayList',2)
			},
			getCommonIndex(dataArray, type) {
				let that = this
				that.fui
					.request('/diandi_hotel/mobile/index/ad', 'GET', {
						type: type,
						page: that.page
					}, false)
					.then(res => {
						console.log('民宿列表读取', res)
						that[dataArray] = res.data
					}).catch(res => {
						console.log('错误', res)
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
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
						  this.getCommonIndex('apartmentList',  this.type)
						}).catch(res => {
							console.log('错误', res)
							uni.showToast({
								title: res.message,
								icon: 'none'
							})
						})
				}
			},
			// 民宿
			goApartMent() {
				let that = this
				that.$Router.push({
					name: 'apartment'
				})
				// 本地存储
				uni.setStorageSync('setTime', JSON.stringify(that.setObj))
			}
		}
	}
</script>

<style lang="scss" scoped>
	.all_btn {
		font-size: 22rpx;
		color: #999999;
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
			
			.txt:not(:first-child)::after {
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
</style>