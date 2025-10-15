<template>
	<view>
		<view v-for="(item,rimins) in sceneryList" :key="rimins">
			<view class="fui-flex justify-between margin-left_7 margin-top-lg" @click="goCircum">
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
			<view class="room-scorll-list">
				<scroll-view class="uni-swiper-tab" scroll-x>
					<view v-for="(item1, index1) in item.list" :key="item1.id" class="fui-tab-items" :id="item1.id"
						:data-current="index1" @click="goCircumrelevancy">
						<view class="fui-tab-item-swiper">
							<view class="swiper_image">
								<image :src="item1.thumb" mode=""
									style="width: 100%;height: 100%;border-radius: 10rpx;">
								</image>
								<view class="swiper_assess color14">
									{{item1.distance}}km
								</view>
							</view>
							<view class="text-bold margin-top-xs font_size_12"
								style="width: 200rpx;overflow: hidden;text-overflow: ellipsis;">
								{{item1.title}}
							</view>
							<view class="fui-flex margin-top-xs font_size_10">
								<view class="color5 text-bold">
									{{item1.level_star}}星
								</view>
								<!-- <view class="swiper_line margin-lr-xs"></view>
								<view class="color13">
									123评论
								</view> -->
							</view>
						</view>
					</view>
				</scroll-view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				sceneryList:[]
			}
		},
		created() {
			this.getCommonIndex('sceneryList', 3)
		},
		methods: {
			getCommonIndex(dataArray, type) {
				let that = this
				that.fui
					.request('/diandi_hotel/mobile/index/ad', 'GET', {
						type: type,
						page: 'index'
					}, false)
					.then(res => {
						console.log('广告', res)
						that[dataArray] = res.data
					}).catch(res => {
						console.log('错误', res)
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
					})
			},
			goCircum() {
				let that = this
				that.$Router.push({
					name: 'circum'
				})
			},
			goCircumrelevancy() {
				let that = this
				that.$Router.push({
					name: 'circumdetail'
				})
			},
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
			height: 280rpx;
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
		
		.fui-tab-item-swiper {
			flex-wrap: nowrap;
			white-space: nowrap;
		
			.swiper_image {
				position: relative;
				width: 280rpx;
				height: 280rpx;
				border-radius: 10rpx;
		
				.swiper_assess {
					position: absolute;
					bottom: 0;
					left: 0;
					padding: 2rpx 8rpx;
					background-color: rgba(1, 1, 1, .8);
					border-radius: 0rpx 10rpx 0rpx 10rpx;
					font-size: 20rpx;
				}
			}
		
			.swiper_line {
				width: 1px;
				height: 20rpx;
				background-color: #CCCCCC;
			}
		}
	}
</style>