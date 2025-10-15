<template>
	<!-- 民宿查询 -->
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="民宿查询" color="#000000" fixed :border="false" placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<u-sticky :offsetTop="offsetTop">
			<view class="apartment_fixed">
				<!-- 筛选 -->
				<view class="room-sift fui-flex justify-around">
					<view class="fui-flex" @click="showPoupFunc('showSort')">
						<view class="font_size_14" :style="{'color':showPoupItem.showSort ? '#00AFC7' : '#000000'}">
							综合排序
						</view>
						<view class="">
							<iconfont :className="showPoupItem.showSort ? 'icon-shangjiantou' : 'icon-xiajiantou1'"
								:size="18" :color="showPoupItem.showSort ? '#00AFC7' : '#000000' ">
							</iconfont>
						</view>
					</view>
					<view class="fui-flex" @tap="showPoupFunc('showRoomType')">
						<view class="font_size_14" :style="{'color':showPoupItem.showRoomType ? '#00AFC7' : '#000000'}">
							房间类型
						</view>
						<view class="">
							<iconfont :className="showPoupItem.showRoomType ? 'icon-shangjiantou' : 'icon-xiajiantou1'"
								:size="18" :color="showPoupItem.showRoomType ? '#00AFC7' : '#000000' ">
							</iconfont>
						</view>
					</view>
					<view class="fui-flex" @tap="showPoupFunc('showLocation')">
						<view class="font_size_14" :style="{'color':showPoupItem.showLocation ? '#00AFC7' : '#000000'}">
							位置
						</view>
						<view class="">
							<iconfont :className="showPoupItem.showLocation ? 'icon-shangjiantou' : 'icon-xiajiantou1'"
								:size="18" :color="showPoupItem.showLocation ? '#00AFC7' : '#000000' ">
							</iconfont>
						</view>
					</view>
					<view class="fui-flex" @tap="showPoupFunc('showPrices')">
						<view class="font_size_14" :style="{'color':showPoupItem.showPrices ? '#00AFC7' : '#000000'}">
							价格
						</view>
						<view class="">
							<iconfont :className="showPoupItem.showPrices ? 'icon-shangjiantou' : 'icon-xiajiantou1'"
								:size="18" :color="showPoupItem.showPrices ? '#00AFC7' : '#000000' ">
							</iconfont>
						</view>
					</view>
					<view class="sift" @tap="showPoupFunc('showFunnel')">
						<iconfont className="icon-sift" :size="30"
							:color="showPoupItem.showFunnel===true ? '#00AFC7' : '#000000'">
						</iconfont>
					</view>
					<view class="popup" v-if="showPoup">
						<!-- 弹出层综合排序 -->
						<view class="popup-content" v-if="showPoupItem.showSort">
							<view class="popup-content_flex">
								<view class="popup-content_txt fui-flex justify-between"
									v-for="(item,index) in HotelOrderBy" :key="index" @click="selectSort(index)">
									<view class="font_size_14"
										:style="{'color':searchWhere.HotelOrderBy === index  ? '#00AFC7' : '#000000'}">
										{{item}}
									</view>
									<view class="" v-if="searchWhere.HotelOrderBy === index ">
										<iconfont className="icon-duigou2" color="#00AFC7" :size="18"></iconfont>
									</view>
								</view>
							</view>
						</view>
						<!-- 房间类型 -->
						<view class="popup-content" v-if="showPoupItem.showRoomType">
							<view class="popup-content_flex">
								<view class="popup-content_txt fui-flex justify-between"
									v-for="(item,index) in roomTypeObj" :key="index" @click="selectType(index)">
									<view class="font_size_14"
										:style="{'color':searchWhere.RoomType === index ? '#00AFC7' : '#000000'}">
										{{item}}
									</view>
									<view class="" v-if="searchWhere.RoomType === index ">
										<iconfont className="icon-duigou2" color="#00AFC7" :size="18"></iconfont>
									</view>
								</view>
							</view>
						</view>
						<!-- 弹出层位置 -->
						<view class="popup-content" v-if="showPoupItem.showLocation">
							<view class="popup-content_flex popup-location fui-flex">
								<view class="">
									<scroll-view scroll-y scroll-with-animation class="tab-view"
										:scroll-into-view="scrollViewId" :style="{ height: '300px', top: top + 'px' }">
										<view :id="`id_${index}`" v-for="(item, index) in rimList" :key="index"
											class="tab-bar-item" :class="[currentTab == index ? 'active' : '']"
											:data-current="index" @tap.stop="swichNav">
											<text>{{ item.title }}</text>
										</view>
									</scroll-view>
								</view>
								<view class="location_self">
									<!-- 直线距离 -->
									<view class="" v-for="(item,index) in rimList" :key="index">
										<scroll-view scroll-y="true" v-if="currentTab === index">
											<view class="" v-for="(item1,ins) in item.list" :key="ins"
												@click="selectRimType(item.field,item1.id)">
												<view class="location_self_item fui-flex justify-between">
													<view class="">
														{{item1.title}}
													</view>
													<view class="">
														<iconfont className="icon-duigou2 text-bold" :size="18"
															:color="searchWhere.rimType[item.field] == item1.id?'#00AFC7':''">
														</iconfont>
													</view>
												</view>
											</view>
										</scroll-view>
									</view>
								</view>
							</view>
						</view>
						<!-- 弹出层价格 -->
						<view class="popup-content" v-if="showPoupItem.showPrices">
							<view class="popup-content_flex">
								<view class="popup-content_txt padding-tb-sm fui-flex justify-between">
									<view class="font_size_14 text-bold">
										价格区间
									</view>
									<view class="font_size_12">
										¥0~¥不限
									</view>
								</view>
								<!-- 价格区间 -->
								<view class="">
									<view class="margin-lr-sm margin-tb-sm">
										<cjSlider v-model="priceValue" activeColor="#00AFC7" :blockWidth="56"
											moveHeight='100' @start="blockStart" @end="blockEnd"></cjSlider>
									</view>
									<view class="fui-flex justify-around padding-top-lg">
										<view class="fui-flex flex-direction" v-for="(item,index) in moneyList"
											:key="index">
											<view class="popup-price_line"></view>
											<view class="popup-price_num">
												￥{{item.moneyNum}}
											</view>
										</view>
									</view>
								</view>
								<!-- 选择价格 -->
								<view class="popup-price_lr_list fui-flex">
									<view class="popup-price_lr_bg color2" v-for="(item,index) in intervalList"
										:key="index">
										<view class="">
											{{item.intervalNum}}
										</view>
									</view>
								</view>
							</view>
						</view>
						<!-- 弹出层漏斗 -->
						<view class="popup-content" v-if="showPoupItem.showFunnel">
							<view class="popup-content_flex popup-location fui-flex">
								<view class="">
									<scroll-view scroll-y scroll-with-animation class="tab-view"
										:scroll-into-view="scrollViewId" :style="{ height: '300px', top: top + 'px' }">
										<view :id="`id_${index}`" v-for="(item, index) in funnelList" :key="index"
											class="tab-bar-item" :class="[currentTab == index ? 'active' : '']"
											:data-current="index" @tap.stop="swichNav">
											<text>{{ item.title }}</text>
										</view>
									</scroll-view>
								</view>
								<view class="location_self">
									<!-- 推荐筛选 -->
									<scroll-view scroll-y :style="{height:'300px', top:top + 'px'}"
										:scroll-with-animation="true" :scroll-into-view="clickId" @scroll="scroll"
										:cancelable="true" @scrolltolower="scrolltolower">
										<!-- 推荐筛选 -->
										<view class="funnel_item" v-for="(item,index) in funnelList" :key="index"
											v-show="currentTab === index">
											<view class="funnel_item_title" :id="'po'+index">
												{{item.title}}
											</view>
											<view class="funnel_item_wrap fui-flex">
												<view class="funnel_item_con funnel_item_only"
													v-for="(item1,index1) in item.list" :key="index1"
													@click="selectFunnelBtn(item.field,item1.key)"
													:class="{'funnel_item_bg':searchWhere.searchList[item.field] == item1.key}">
													{{item1.value}}
													<view v-if="checkSearchList(item.field,item1.key)"
														class="funnel_item_bg_icon">
														<iconfont className="icon-duigou2" color="#FFFFFF" :size="4">
														</iconfont>
													</view>
												</view>
											</view>
										</view>
									</scroll-view>
								</view>
							</view>
						</view>
						<view class="popup-price_bottom bottom_btn fui-flex font_size_14">
							<view class="reset" @click="reSetSearchWhere">
								重置
							</view>
							<view class="check color14" @click="getHotelList">
								查看
							</view>
						</view>
					</view>

				</view>

				<!-- 滚动 -->
				<view class="fui-tabs">
					<scroll-view id="tab-bar" scroll-with-animation class="fui-scroll-h" :scroll-x="true"
						:show-scrollbar="false">
						<view v-for="(tab, index) in tabBars" :key="tab.id" class="fui-tab-item" :id="tab.id"
							:data-current="index" @click="tabClick">
							<view class="fui-tab-item-title"
								:class="{ 'fui-tab-item-title-active': label_id == tab.id }">
								{{ tab.name }}
							</view>
						</view>
					</scroll-view>
				</view>
			</view>
		</u-sticky>


		<view class="room-scorll">
			<!-- 列表 -->
			<hotel-items :hotel-list="hotelList" @getHotels="getHotelList()"></hotel-items>
		</view>

		<!-- 点击收藏模态框 -->
		<fui-modal :show="showModal" padding="32rpx 32rpx" :custom='true' @cancel='cancelModal'>
			<view class="fui-flex justify-end">
				<view class="font_size_18 text-bold">
					选择心愿单
				</view>
				<view class="" style="margin-left: 179rpx;" @click="cancelModal">
					<iconfont className="icon-quxiao" color="#999999"></iconfont>
				</view>
			</view>
			<view class="found_input">
				<input type="text" v-model="foundWishTxt" placeholder="添加心愿单">
				<view class="found_input_sure" v-if="foundWishTxt !== ''" @click="foundWishBtn">
					确认
				</view>
			</view>
			<view class="modal_txt" v-for="item in wishList" :key="item.id" @click="selectWish(item)">
				{{item.title}}<text class="margin-left-xs font_size_14 color13">{{item.total}}</text>
			</view>
		</fui-modal>

	</view>
</template>

<script>
	import cjSlider from '@/components/cj-slider/cj-slider.vue'
	export default {
		components: {
			cjSlider
		},
		data() {
			return {
				CustomBarNum: 0,
				searchBack: true,
				showPoup: false,
				showModal: false, //收藏
				single1: undefined, // 入住时间
				startTime: '', //当前日期组件选中开始时间
				endTime: '', //当前日期组件选中结束时间
				indexStartTime: '', //首页传递开始时间
				indexEndTime: '', //首页传递结束时间
				defaultObj: uni.getStorageSync('defaultTime'), //首页默认日期
				getTime: uni.getStorageSync('orderTime'), //首页修改后的日期
				tabIndex: 0,
				tabBars: [],
				label_id: '',
				roomTypeObj: {},
				priceValue: [0, 9000000], // 可以指定默认值
				moneyList: [{
						moneyNum: '0'
					},
					{
						moneyNum: '200'
					},
					{
						moneyNum: '400'
					},
					{
						moneyNum: '600'
					},
					{
						moneyNum: '800'
					},
					{
						moneyNum: '1000'
					},
					{
						moneyNum: '不限'
					}
				],
				intervalList: [{
						intervalNum: '100以下'
					},
					{
						intervalNum: '100-200'
					},
					{
						intervalNum: '200-300'
					},
					{
						intervalNum: '300-400'
					},
					{
						intervalNum: '400-600'
					},
					{
						intervalNum: '600-800'
					},
					{
						intervalNum: '800-1000'
					},
					{
						intervalNum: '1000以上'
					}
				],
				// 位置
				rimList: [],
				height: 0, //scroll-view高度
				top: 0,
				currentTab: 0, //预设当前项的值
				scrollViewId: "id_0",
				// 漏斗筛选
				funnelList: [],
				// 下拉刷新开始
				refresh: {
					page: 1,
					pageSize: 10
				},
				total: 0,
				// 下拉刷新结束
				HotelOrderBy: {},
				hotelList: [], //酒店列表
				showPoupItem: {
					'showSort': false,
					'showPrices': false,
					'showRoomType': false,
					'showLocation': false,
					'showFunnel': false
				},
				searchWhere: {
					HotelOrderBy: 0, // 综合排序
					RoomType: 0.,
					rimType: [],
					searchList: {}
				},
				tSelect: [], //房间类型
				clickId: '', //漏斗选中的id
				isLeftClick: false,
				topList: [],
				fSelect: [], //漏斗数据
				foundWishTxt: '', //添加心愿单
				wishList: [], //心愿列表
				hotelId: undefined,
				offsetTop:0,
			};
		},
		onLoad() {
			let that = this
			that.indexStartTime = that.getTime.startTime ? that.getTime.startTime.replace('-', '.') : that
				.defaultObj.startDeTime.replace('-', '.')
			that.indexEndTime = that.getTime.endTime ? that.getTime.endTime.replace('-', '.') : that
				.defaultObj.endDeTime.replace('-', '.')
			// #ifdef MP-WEIXIN
			that.searchBack = false
			// #endif
			setTimeout(() => {
				uni.getSystemInfo({
					success: res => {
						let header = 0;
						let top = 0;
						//#ifdef H5
						top = 44;
						header = 800;
						//#endif
						//#ifdef APP
						top = 20;
						header = 900
						//#endif
						this.height = res.windowHeight - uni.upx2px(header);
						this.top = top + uni.upx2px(header);
					}
				});
			}, 50);
			that.offsetTop	= that.CustomBar
			console.log('CustomBar', this.CustomBar)
			that.getHotelInitialize() //酒店列表初始化
			that.getHotelList() //酒店列表
			this.CustomBarNum = this.CustomBar
		},
		computed: {
			checkSearchList() {
				return function(key, id) {
					let that = this
					console.log('checkSearchList', this.searchWhere, key, id);
					if (this.searchWhere.searchList[key] == id) {
						return true
					} else {
						return false
					}
				};
			},
			checkRimType(){
				return function(key, id) {
					let that = this
					console.log('checkRimType', this.searchWhere, key, id);
					if (this.searchWhere.rimType[key] == id) {
						return true
					} else {
						return false
					}
				};
			},			 
			// 入住
			startTime1() {
				return this.single1 ? this.single1[0].split('-')[1] : String(new Date().getMonth() + 1).padStart(2, 0)
			},
			endTime1() {
				return this.single1 ? this.single1[0].split('-')[2] : new Date().getDate()
			},
			// 离店
			startTime2() {
				return this.single1 ? this.single1[1].split('-')[1] : String(new Date().getMonth() + 1).padStart(2, 0)
			},
			endTime2() {
				return this.single1 ? this.single1[1].split('-')[2] : new Date().getDate() + 1
			},
		},
		mounted() {
			console.log('this.CustomBarNum', this.CustomBarNum)
		},
		methods: {
			// 酒店列表初始化
			getHotelInitialize() {
				let that = this
				that.fui.getLocation().then(async (location) => {
					console.log('经纬度获取', location);

					const res = await that.$api.hotelMobileHotelListinit(location.latitude, location.longitude)

					that.HotelOrderBy = res.data.HotelOrderBy
					that.roomTypeObj = res.data.RoomType
					that.tabBars = res.data.labelList

					// 位置
					const localkeys = Object.keys(res.data.rimList.RimType)
					localkeys.forEach((key, index) => {
						that.rimList.push({
							'title': res.data.rimList.RimType[key],
							'field': key,
							'list': Object.values(res.data.rimList.list)[index]
						})
					})
					// 综合搜索
					const funnelkeys = Object.keys(res.data.searchList.searchCate)
					funnelkeys.forEach((key, index) => {
						that.funnelList.push({
							'title': res.data.searchList.searchCate[key],
							'field': key,
							'list': Object.values(res.data.searchList.list)[index]
						})
					})
				}).catch(err => {
					console.log('经纬度获取-err', err);
				})
			},
			/**
			 * 重置检索
			 */
			reSetSearchWhere() {
				let that = this
				that.showPoup = false
			},
			// 酒店列表
			getHotelList() {
				let that = this
				that.showPoup = false
				that.$api.hotelMobileHotelList(that.refresh.page, that.refresh.pageSize).then(res => {
					if(res.data.list.length === that.refresh.pageSize){
						that.refresh.page++
					}
					
					// 判断新数据和现有数据是否有相同项
					const duplicateItems = res.data.list.filter(item => that.hotelList.findIndex(hotel => hotel.id === item.id) >= 0)
					if (duplicateItems.length === 0) {
					  that.hotelList = that.hotelList.concat(res.data.list)
					}
					
					that.total = res.data.total
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			changeTime(e) {
				let that = this
				console.log(e)
				that.startTime = e[0].split('-').splice(1, 2).join('.')
				that.endTime = e[1].split('-').splice(1, 2).join('.')
				console.log(that.startTime, that.endTime)
			},
			tabClick(e) {
				let index = e.target.dataset.current || e.currentTarget.dataset.current;
				this.switchTab(index);
			},
			switchTab(index) {
				if (this.tabIndex === index) return;
				this.tabIndex = index;
				this.label_id = this.tabBars[index].id;
			},
			backBtn() {
				let that = this
				that.$Router.back()
			},
			cancelModal() {
				this.showModal = false
			},
			// 心愿单列表
			getWishList() {
				let that = this
				that.$api.hotelWishList().then(res => {
					that.wishList = res.data
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			selectWish(item) {
				let that = this

				// 酒店固定是room_id0
				that.$api.hotelWishAddwish(that.hotelId, 0, item.id).then(res => {
					if (res.code === 200) {
						that.getHotelList()
						that.showModal = false
					}
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			// 点击收藏
			collectBtn(item) {
				let that = this
				that.hotelId = item.id
				if (!item.is_like) {
					that.getWishList()
					that.showModal = true
				} else {
					that.showModal = false
					that.$api.hotelWishDelwish(item.id, 0).then(res => {
						that.getHotelList()
					}).catch(res => {
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
					})
				}
			},
			// 添加心愿单
			foundWishBtn() {
				let that = this
				if (that.foundWishTxt !== '') {
					that.$api.hotelWishAdd(that.foundWishTxt).then(res => {
						if (res.code === 200) {
							uni.showToast({
								title: "心愿单创建成功",
								icon: 'none'
							})
							that.getWishList()
							that.foundWishTxt = ''
						}
					}).catch(res => {
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
			},
			/**
			 * 综合排序
			 * @param {Object} index
			 */
			selectSort(index) {
				let that = this
				this.$set(this.searchWhere, 'HotelOrderBy', index)
			},
			showPoupFunc(key) {
				let that = this
				this.$set(that.showPoupItem, key, true)
				const arr = [
					'showSort', 'showPrices', 'showRoomType', 'showLocation', 'showFunnel'
				]
				that.showPoup = true
				arr.forEach((item, index) => {
					if (key != item) {
						that.$set(that.showPoupItem, item, false)
					}
				})
				console.log('showPoupFunc', that.showPoupItem);
			},
			/**
			 * 房屋类型
			 * @param {Object} index
			 */
			selectType(index) {
				let that = this
				this.$set(this.searchWhere, 'RoomType', index)
			},
			/**
			 * 综合检索
			 * @param {Object} key
			 * @param {Object} id
			 */
			selectFunnelBtn(key, id) {
				let that = this
				const obj = {
					[`${key}`]: id
				}
				const searchList = Object.assign(this.searchWhere.searchList, obj)
				this.$set(this.searchWhere, 'searchList', searchList)
				console.log(this.searchWhere, searchList);
				that.$forceUpdate()
			},
			/**
			 * 周边类型-位置
			 * @param {Object} id
			 */
			selectRimType(key, id) {
				let that = this
				const obj = {
					[`${key}`]: id
				}
				const rimType = Object.assign(this.searchWhere.rimType, obj)
				this.$set(this.searchWhere, 'rimType', rimType)
				console.log(this.searchWhere, rimType);
				that.$forceUpdate()
			},
			// 滑动开始
			blockStart() {
				console.log('滑动触发')
			},
			// 活动结束
			blockEnd() {
				console.log('滑动结束')
			},
			// 详情页面
			hotelDetail(item) {
				let that = this
				that.$Router.push({
					name: "querydetail",
					params: {
						hotel_id: item.id
					}
				})
			},
			// 点击标题切换当前页时改变样式
			swichNav: function(e) {
				let cur = e.currentTarget.dataset.current;
				this.clickId = 'po' + cur
				if (this.currentTab == cur) {
					return false;
				} else {
					this.currentTab = cur;
					this.checkCor();
				}
				this.isLeftClick = true
			},
			//判断当前滚动超过一屏时，设置tab标题滚动条。
			checkCor: function() {
				if (this.currentTab > 2) {
					this.scrollViewId = `id_${this.currentTab - 2}`;
				} else {
					this.scrollViewId = `id_0`;
				}
			},
			scroll(e) {
				let that = this
				const query = uni.createSelectorQuery().in(this);
				query.selectAll(".funnel_item_title").boundingClientRect().exec(res => {
					let nodes = res[0];
					let arr = [];
					nodes.map(item => {
						arr.push(item.top);
					})
					this.topList = arr;
				})
				if (that.isLeftClick) {
					that.isLeftClick = false
					return
				}
				let scrollTop = e.target.scrollTop
				for (let i = 0; i < that.topList.length; i++) {
					let h1 = that.topList[i];
					let h2 = that.topList[i + 1];
					if (scrollTop > h1 && scrollTop < h2) {
						that.currentTab = i;
					}
				}
			},
			// 滚动到底部
			scrolltolower() {
				setTimeout(() => {
					this.currentTab = this.funnelList.length - 1;
				}, 100)
			},
		},
		// 触底触发的函数
		onReachBottom() {
			let that = this
			if ((that.refresh.page * that.refresh.pageSize) <= that.total) {
				that.refresh.page++
				that.getHotelList()
			} else {
				uni.showToast({
					title: "加载完毕",
					icon: 'none'
				})
			}
		},
	}
</script>

<style lang="scss" scoped>
	.container {
		// padding: 84rpx 0 0;

		.apartment_fixed {
			width: 100%;
			background-color: #FFFFFF;
			// z-index: 98;
			height: 180rpx;

			.fui-scroll-h {
				width: 750rpx;
				white-space: nowrap;
			}

			.fui-tabs {
				padding-left: 20rpx;
				padding-right: 20rpx;
			}

			.fui-tab-item {
				display: inline-block;
				flex-wrap: nowrap;
				padding-right: 20rpx;
			}

			.fui-tab-item:last-child {
				padding-right: 60rpx;
			}

			.fui-tab-item-title {
				padding: 6rpx 16rpx;
				border: 1px solid #D8D8D8;
				border-radius: 10rpx;
				color: #666666;
				font-size: 26rpx;
				flex-wrap: nowrap;
				white-space: nowrap;

				&-active {
					color: #00AFC7;
					border: 1px solid #00AFC7;
				}
			}

		}

		.room-search {
			width: 600rpx;
			min-height: 88rpx;
			box-sizing: border-box;
			margin-right: 200rpx;

			.room-search_add {
				width: 600rpx;
				height: 72rpx;
				background-color: #F9F9F9;
				border-radius: 20rpx;
				box-sizing: border-box;
				margin: 0 20rpx;

				.room-search_add_input {
					position: relative;
					width: 330rpx;

					.fui-input {
						font-size: 24rpx;
					}

					.fui-phcolor {
						font-size: 24rpx;
					}
				}

				.room-search_add_input::after {
					content: "";
					position: absolute;
					left: 0;
					top: 50%;
					transform: translateY(-50%);
					width: 1px;
					height: 32rpx;
					background-color: #D1D1D1;
				}

				.room-search_add_input::before {
					content: "";
					position: absolute;
					right: 0;
					top: 50%;
					transform: translateY(-50%);
					width: 1px;
					height: 32rpx;
					background-color: #D1D1D1;
				}

				.room-search_scale {
					font-size: 22rpx;
					-webkit-transform: scale(0.8);
				}
			}
		}

		.room-sift {
			position: relative;
			height: 88rpx;
			box-shadow: 0 5px 20px -5px rgba(209, 209, 209, 0.3);

			.sift {
				position: relative;
			}

			.sift::before {
				content: "";
				position: absolute;
				left: -25rpx;
				top: 50%;
				transform: translateY(-50%);
				width: 1px;
				height: 32rpx;
				background-color: #D1D1D1;
			}

			.popup {
				position: absolute;
				top: 160rpx;
				left: 0;
				width: 750rpx;
				height: 100vh;
				background-color: rgba(0, 0, 0, .3);
				z-index: -999;

				// 弹出层
				.popup-content {
					width: 750rpx;
					top: 0;
					left: 0;
					background-color: #FFFFFF;
					z-index: 333;

					.popup-content_flex {
						padding: 0 32rpx;
						box-sizing: border-box;
						background-color: #FFFFFF;


						.tab-view {
							width: 200rpx;
						}

						.tab-bar-item {
							width: 200rpx;
							height: 100rpx;
							background: #F9F9F9;
							box-sizing: border-box;
							display: flex;
							align-items: center;
							justify-content: center;
							font-size: 28rpx;
							color: #666666;
						}

						.active {
							color: #00AFC7;
							background: #fff;
						}

						.location_self {
							width: 100%;
							align-self: flex-start;
							padding: 0 32rpx;
						}

						.location_self_item {
							padding: 30rpx;
							border-bottom: 2rpx solid #EEEEEE;
							font-size: 28rpx;
						}


						.popup-content_txt {
							padding: 30rpx 0;
							border-top: 2rpx solid #EEEEEE;
						}

						.popup-price_line {
							width: 2rpx;
							height: 12rpx;
							background-color: #999999;
						}

						.popup-price_num {
							font-size: 20rpx;
							transform: scale(0.8);
						}

						.popup-price_lr_list {
							padding: 28rpx 0 0;
							flex-wrap: wrap;

							.popup-price_lr_bg {
								width: 158rpx;
								height: 56rpx;
								line-height: 56rpx;
								text-align: center;
								background-color: #F9F9F9;
								border-radius: 10rpx;
								margin: 16rpx 18rpx 0 0;
								box-sizing: border-box;

								view {
									font-size: 20rpx;
									transform: scale(0.8);
								}
							}

							.popup-price_lr_bg:nth-child(4n) {
								margin-right: 0;
							}
						}

						.funnel_item {
							.funnel_item_title {
								padding: 32rpx 0 24rpx;
								font-size: 28rpx;
								font-weight: 600;
							}

							.funnel_item_wrap {
								flex-wrap: wrap;


								.funnel_item_con {
									width: 150rpx;
									border-radius: 10rpx;
									font-size: 26rpx;
									padding: 20rpx 12rpx;
									margin: 0 18rpx 16rpx 0;
									box-sizing: border-box;
									text-align: center;
								}

								.funnel_item_only {
									border: 2rpx solid #EEEEEE;
									color: #000000;
								}

								.funnel_item_bg {
									position: relative;
									color: #00AFC7;
									border-color: #EBF9FB;
									background-color: #EBF9FB;
									overflow: hidden;

									.funnel_item_bg_icon {
										position: absolute;
										right: -21rpx;
										bottom: -21rpx;
										width: 42rpx;
										height: 42rpx;
										line-height: 23rpx;
										text-align: left;
										background-color: #00AFC7;
										border-radius: 50%;
									}
								}

								.funnel_item_con:nth-child(3n) {
									margin-right: 0;
								}
							}


						}
					}

					.popup-location {
						padding: 0;
						border-top: 2rpx solid #EEEEEE;
					}



					.bottom_btn {
						padding-top: 0;
					}
				}

				.popup-price_bottom {
					height: 88rpx;
					line-height: 88rpx;
					padding-top: 48rpx;
					background-color: #FFFFFF;

					.reset {
						width: 200rpx;
						height: 100%;
						background-color: #FFFFFF;
						text-align: center;
						border-top: 2rpx solid #EEEEEE;
						box-sizing: border-box;
					}

					.check {
						width: 550rpx;
						height: 100%;
						background-color: #00AFC7;
						text-align: center;
					}
				}
			}
		}

		.room-scorll {
			margin: 32rpx 32rpx 0;
			box-sizing: border-box;


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
					// -webkit-transform: scale(0.9);
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
					padding: 3rpx 8rpx;
					background-color: #F9F9F9;
					border-radius: 5rpx;
					margin-right: 10rpx;
					font-size: 24rpx;
				}

				.room-scorll-list_coupon {
					padding: 2rpx 8rpx;
					border: 1px solid rgba(251, 91, 50, .3);
					border-radius: 5rpx;
					color: #FB5B32;
					font-size: 24rpx;
				}
			}
		}

		.found_input {
			position: relative;
			padding: 14rpx 94rpx 14rpx 24rpx;
			background-color: #F9F9F9;
			border-radius: 20rpx;
			margin: 32rpx 0 20rpx;

			.found_input_sure {
				position: absolute;
				right: 24rpx;
				top: 14rpx;
				color: #00AFC7;
			}
		}

		.modal_txt {
			font-size: 32rpx;
			padding: 22rpx 0;
		}


	}

	/* 修改数字框样式 */
	::v-deep .test .uni-numbox__minus {
		border-radius: 50% !important;
		border: 1px solid #00AFC7;
		border-color: #00AFC7 !important;
	}

	::v-deep .test .uni-numbox__minus text {
		color: #00AFC7 !important;
	}

	::v-deep .test .uni-numbox__plus {
		border-radius: 50% !important;
		border: 1px solid #00AFC7;
		border-color: #00AFC7 !important;
	}

	::v-deep .test .uni-numbox__plus text {
		color: #00AFC7 !important;
	}

	::v-deep .uni-numbox__value {
		background-color: #fff !important;
	}

	::v-deep .fui-numbox-icon {
		padding: 8rpx !important;
		background-color: #fff !important;
		border: 1rpx solid #00AFC7 !important;
	}

	::v-deep .fui-num-input {
		border: 1rpx solid #fff !important;
	}

	::v-deep .fui-modal-mask {
		z-index: 996;
	}

	::v-deep .fui-modal-box {
		z-index: 998;
	}
</style>