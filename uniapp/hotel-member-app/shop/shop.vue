<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="积分商城" color="#000000" :border="false" :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="shop-top">
			<view class="fui-flex justify-between shop-top-jf">
				<view class="">
					<view class="">积分</view>
					<view class="shop-top-jf_num">{{user_integral}}</view>
				</view>
				<view class="">
					<button class="serve-btn" @click="goRecord">兑换记录</button>
				</view>
			</view>
			<!-- 搜索 -->
			<view class="search_bor fui-flex text-dl">
				<image class="margin-lr-sm" style="width: 28rpx"
					src="https://hotelapi.ddicms.cn/wxapphttps://oss.ddicms.cn/member/search.png" mode="widthFix">
				</image>
				<input placeholder="搜索相关商品" placeholder-class="text-dl" type="text" v-model="keyword"
					@input="inputSearch" />
			</view>
			<!-- 推荐商品 -->
			<view class="shop-title">
				<u-tabs :list="tab" lineColor="#00AFC7" :current="currentTab" :activeStyle="activeStyle" lineHeight='2'
					lineWidth='28' @change="tabsChange" :scrollable="tab.length > 3 ? true : false"></u-tabs>
			</view>
			<view class="fui-flex justify-between flex-wrap margin-lr">
				<view class="product_list" v-for="(item,index) in exchangeList" :key="index">
					<image :src="item.thumb" mode="" style="width: 100%;height: 224rpx;"
						@click="goDetail(item.goods_id)"></image>
					<view class="margin-top-sm text-dl">{{item.goods_name}}</view>
					<view class="fui-flex margin-top-xs">
						<view class="">
							<iconfont className="icon-a-0-01" :size="18" color="#FF9500"></iconfont>
						</view>
						<view class="font_size_16 text-bold" style="color: #FF9500;">{{item.goods_integral}}</view>
					</view>
					<view class="">
						<button class="change-btn" @click="exchangeBtn(item)">立即兑换</button>
					</view>

				</view>
			</view>
		</view>
		<tab-bar :currentindex="currentTabIndex"></tab-bar>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				currentTabIndex: 1,
				currentTab: 0,
				activeStyle: {
					color: '#00AFC7',
				},
				tab: [],
				query: {
					page: 1,
					pageSize: 10,
				},
				keyword: '', //关键词
				timer: null,
				user_integral: undefined, //积分
				exchangeList: [], //商品列表
			};
		},
		onLoad() {
			let that = this
			that.getPoints() //积分
			that.getCategoryList()
			that.getShopList(that.currentTab)
		},
		methods: {
			// 积分
			getPoints() {
				let that = this
				that.$api.integralMemberInfo().then(res => {
					that.user_integral = res.data.user_integral
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			tabsChange(e) {
				let that = this
				that.currentTab = e.index;
				that.getShopList(that.tab[e.index].category_id)
			},
			// 商品分类
			getCategoryList() {
				let that = this
				that.$api.integralCategoryList().then(res => {
					that.tab = res.data
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			// 商品列表
			getShopList(category_pid) {
				let that = this
				that.$api.integralGoodsLists(that.query.page, that.query.pageSize, category_pid, 'desc', 'desc', '').then(
					res => {
						that.exchangeList = res.data
					}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			inputSearch(e) {
				let that = this
				clearTimeout(that.timer)
				that.timer = setTimeout(() => {
					that.keyword = e.target.value

					if (that.keyword === '') {
						that.exchangeList = []
						return
					}
					that.$api.integralGoodsSearch(page, pageSize, keywords).then(res => {
						that.exchangeList = res.data
						// that.getShopList()
					}).catch(res => {
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
					})
				}, 500)
			},
			goRecord() {
				let that = this;
				that.$Router.push({
					name: 'myrecords'
				});
			},
			goDetail(goods_id) {
				let that = this;
				that.$Router.push({
					name: 'shopdetail',
					params: {
						goods_id: goods_id
					}
				});
			},
			exchangeBtn(item) {
				let that = this
				if (that.user_integral >= item.goods_integral) {
					that.$Router.push({
						name: 'verifyOrder',
						params: {
							goods_id: item.goods_id
						}
					})
				} else {
					uni.showToast({
						title: '需要兑换的商品积分不够噢',
						icon: 'none'
					})
				}
			}
		}
	};
</script>

<style>
	.shop-top {
		width: 100%;
		height: 242rpx;
		background-color: #00AFC7;
	}

	.shop-top-jf {
		margin: 40rpx 32rpx 48rpx;
		color: #fff;
		font-size: 24rpx;
	}

	.serve-btn {
		width: 160rpx;
		height: 56rpx;
		line-height: 56rpx;
		border-radius: 28rpx;
		font-size: 24rpx;
		color: #00AFC7;
	}

	.shop-top-jf_num {
		font-size: 64rpx;
		font-weight: 600;
		margin-top: 3rpx;
	}

	.search_bor {
		height: 88rpx;
		background: #ffffff;
		box-shadow: 0rpx 3rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
		border-radius: 42rpx;
		margin: 0 32rpx;
	}

	.shop-title {
		margin-bottom: 20rpx;
	}

	.product_list {
		width: 328rpx;
		height: 460rpx;
		background-color: #fff;
		border-radius: 20rpx;
		box-shadow: 0rpx 3rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);
		padding: 32rpx 26rpx;
		color: #1d1d1d;
		margin-bottom: 26rpx;
		box-sizing: border-box;
	}

	.product_list:last-child {
		margin-bottom: 120rpx;
	}

	/* .product_list:nth-last-child(2) {
		margin-bottom: 120rpx;
	} */

	.change-btn {
		width: 100%;
		height: 56rpx;
		line-height: 56rpx;
		background: rgba(0, 175, 199, .1);
		color: #00AFC7;
		font-size: 24rpx;
		margin-top: 25rpx;
	}
</style>