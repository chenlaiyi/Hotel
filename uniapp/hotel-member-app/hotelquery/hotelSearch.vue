<template>
	<view class="container">
		<!-- 搜索 -->
		<!-- <hx-navbar :back="false" :fixed="true" :backgroundColor="[255,255,255]">
			<template slot="right">
				<view class="room-search fui-flex justify-between">
					<view class="room-search_add fui-flex">
						<view class="fui-flex padding-left-xs font_size_12 text-bold">
							<view class="">
								西安
							</view>
							<view class="room-search_add_pull">
								<iconfont className="icon-xiajiantou1" :size="16"></iconfont>
							</view>
						</view>
						<view class="fui-flex padding-left-xs font_size_12 text-bold">
							<view class="">
								酒店
							</view>
							<view class="room-search_add_pull">
								<iconfont className="icon-xiajiantou1" :size="16"></iconfont>
							</view>
						</view>
						<view class="room-search_add_input">
							<input class="fui-input" placeholder="输入关键词搜索" placeholder-class="fui-phcolor" />
						</view>
					</view>
					<view class="font_size_14" @click="closeBack">
						取消
					</view>
				</view>
			</template>
		</hx-navbar> -->

		<!-- 热门搜索 -->
		<view class="">
			<view class="margin-tb-sm font_size_14 text-bold">
				热门搜索
			</view>
			<view class="hot-search fui-flex">
				<view class="hot-search_word">
					地铁口
				</view>
				<view class="hot-search_word">
					四季酒店
				</view>
				<view class="hot-search_word">
					商务
				</view>
				<view class="hot-search_word">
					会展中心
				</view>
				<view class="hot-search_word">
					大唐不夜城
				</view>
				<view class="hot-search_word">
					大雁塔
				</view>
				<view class="hot-search_word">
					大唐西市
				</view>
			</view>
		</view>
		<view class="">
			<view class="fui-flex justify-between margin-tb-sm font_size_14 text-bold">
				<view class="">
					历史搜索
				</view>
				<view class="hot-search_del">
					<iconfont className="icon-close_recode" :size="20"></iconfont>
				</view>
			</view>
			<view class="hot-search fui-flex">
				<view class="hot-search_word">
					地铁口
				</view>
				<view class="hot-search_word">
					四季酒店
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {

			};
		},
		onLoad() {
			let that = this
			// that.getHotSearch()
			// that.getHisSearch()
		},
		methods: {
			// 历史记录
			getHisSearch() {
				let that = this
				that.fui
					.request('/diandi_hotel/mobile/search/his','GET',{},false).then(res=>{
						console.log(res)
					}).catch(res=>{
						uni.showToast({
							title:res.message,
							icon:'none'
						})
					})
			},
			// 热门搜索
			getHotSearch() {
				let that = this
				that.fui
					.request('/diandi_hotel/mobile/search/hot','GET',{},false)
					.then(res=>{
						console.log(res)
					}).catch(res=>{
						uni.showToast({
							title:res.message,
							icon:'none'
						})
					})
			},
			closeBack() {
				let that = this
				that.$Router.back()
			}
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		padding: 0 32rpx 0;

		.room-search {
			height: 88rpx;
			box-sizing: border-box;
			margin-right: 200rpx;

			.room-search_add {
				width: 598rpx;
				height: 64rpx;
				background-color: #F9F9F9;
				border-radius: 36rpx;
				box-sizing: border-box;
				padding-left: 24rpx;

				.room-search_add_pull {
					position: relative;
					padding-right: 26rpx;
				}

				.room-search_add_pull::after {
					content: "";
					position: absolute;
					top: 50%;
					right: 13rpx;
					transform: translateY(-50%);
					width: 1px;
					height: 32rpx;
					background-color: #D1D1D1;
				}

				.room-search_add_input {
					width: 320rpx;

					.fui-input {
						font-size: 24rpx;
					}

					.fui-phcolor {
						font-size: 24rpx;
						color: #D1D1D1;
					}
				}
			}
		}

		.hot-search {
			flex-wrap: wrap;

			.hot-search_word {
				padding: 11rpx 24rpx;
				background-color: #F9F9F9;
				border-radius: 10rpx;
				font-size: 24rpx;
				color: #666666;
				margin: 0 24rpx 16rpx 0;
			}
		}

		.hot-search_del {
			width: 40rpx;
			height: 40rpx;
			line-height: 40rpx;
			border-radius: 50%;
			border: 2rpx solid #EEEEEE;
			text-align: center;
		}
	}
	
	::v-deep .hx-navbar__header-btns {
		min-width: 0;
		padding-right: 0;
	}
</style>