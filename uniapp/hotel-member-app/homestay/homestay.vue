<template>
	<!-- 民宿查询 -->
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="民宿" color="#000000" :border="false"  fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<!-- <hx-navbar :back="searchBack" :fixed="true" :backgroundColor="[255,255,255]">
			<template slot="right">
				<view class="room-search fui-flex justify-center">
					<view class="room-search_add fui-flex">
						<view class="fui-flex padding-left-xs font_size_12 text-bold">
							<view class="" style="min-width: 50rpx;">
								西安
							</view>
							<view class="">
								<iconfont className="icon-xiajiantou1" :size="16"></iconfont>
							</view>
						</view>
						<view class="room-search_add_input fui-flex fui-align-between">
							<view class="padding-lr-xs">
								<iconfont className="icon--fangdajing" :size="20" color="#D1D1D1"></iconfont>
							</view>
							<input class="fui-input" placeholder="景点/地址/房源名" placeholder-class="fui-phcolor" />
						</view>
						<view class="padding-lr-sm">
							<uni-datetime-picker type='daterange' v-model="single1" @change="changeTime">
								<view class="fui-flex" style="height: 30rpx;">
									<view class="room-search_scale color13">入</view>
									<view class="room-search_scale color4">{{startTime ? startTime : indexStartTime}}
									</view>
								</view>
								<view class="fui-flex" style="height: 30rpx;">
									<view class="room-search_scale color13">离</view>
									<view class="room-search_scale color4">{{endTime ? endTime : indexEndTime}}</view>
								</view>
							</uni-datetime-picker>
						</view>
					</view>
					<view class="" style="margin: 12rpx 62rpx 0 0;">
						<iconfont className="icon-map" :size="40"></iconfont>
					</view>
				</view>
			</template>
		</hx-navbar> -->
		
		<!-- 搜索 -->
		<view class="apartment_fixed">
			<!-- 筛选 -->
			<view class="room-sift fui-flex justify-around">
				<view class="fui-flex" @click="sortBtn">
					<view class="font_size_14" :style="{'color':showSort ? '#00AFC7' : '#000000'}">
						综合排序
					</view>
					<view class="">
						<iconfont :className="showSort ? 'icon-shangjiantou' : 'icon-xiajiantou1'" :size="18"
							:color="showSort ? '#00AFC7' : '#000000' ">
						</iconfont>
					</view>
				</view>
				<view class="fui-flex" @tap="numberBtn">
					<view class="font_size_14" :style="{'color':showNumber ? '#00AFC7' : '#000000'}">
						人数
					</view>
					<view class="">
						<iconfont :className="showNumber ? 'icon-shangjiantou' : 'icon-xiajiantou1'" :size="18"
							:color="showNumber ? '#00AFC7' : '#000000'">
						</iconfont>
					</view>
				</view>
				<view class="fui-flex" @tap="locationBtn">
					<view class="font_size_14" :style="{'color':showLocation ? '#00AFC7' : '#000000'}">
						位置
					</view>
					<view class="">
						<iconfont :className="showLocation ? 'icon-shangjiantou' : 'icon-xiajiantou1'" :size="18"
							:color="showLocation ? '#00AFC7' : '#000000' ">
						</iconfont>
					</view>
				</view>
				<view class="fui-flex" @tap="priceBtn">
					<view class="font_size_14" :style="{'color':showPrices ? '#00AFC7' : '#000000'}">
						价格
					</view>
					<view class="">
						<iconfont :className="showPrices ? 'icon-shangjiantou' : 'icon-xiajiantou1'" :size="18"
							:color="showPrices ? '#00AFC7' : '#000000' "></iconfont>
					</view>
				</view>
				<view class="sift" @tap="funnelBtn">
					<iconfont className="icon-sift" :size="30" :color="showFunnel===true ? '#00AFC7' : '#000000'">
					</iconfont>
				</view>

				<!-- 弹出层综合排序 -->
				<view class="popup-content" v-if="showSort">
					<view class="popup-content_flex">
						<view class="popup-content_txt fui-flex justify-between" v-for="(item,index) in sortObj"
							:key="index" @click="selectSort(item)">
							<view class="font_size_14"
								:style="{'color':sSelect.indexOf(item) !== -1 ? '#00AFC7' : '#000000'}">
								{{item}}
							</view>
							<view class="" v-if="sSelect.indexOf(item) !== -1">
								<iconfont className="icon-duigou2" color="#00AFC7" :size="18"></iconfont>
							</view>
						</view>
					</view>
				</view>
				<!-- 弹出层人数 -->
				<view class="popup-content" v-if="showNumber">
					<view class="popup-content_flex">
						<view class="popup-content_txt  fui-flex justify-between" v-for="(item,index) in persionTypeObj"
							:key="index">
							<view class="font_size_14">
								<!-- 成人 -->
								{{item}}
							</view>
							<view class="" v-if="item==='成人'">
								<fui-numberbox backgroundColor="#FFFFFF" color="#000" iconColor="#00AFC7"
									:value="adultVal" @change="adultValNum"></fui-numberbox>
							</view>
							<view class="" v-if="item==='儿童'">
								<fui-numberbox backgroundColor="#FFFFFF" color="#000" iconColor="#00AFC7"
									:value="childVal" @change="childValNum"></fui-numberbox>
							</view>
							<view class="" v-if="item==='客人'">
								<fui-numberbox backgroundColor="#FFFFFF" color="#000" iconColor="#00AFC7"
									:value="patronVal" @change="patronValNum"></fui-numberbox>
							</view>
						</view>
					</view>
					<view class="popup-price_bottom bottom_btn fui-flex font_size_14">
						<view class="reset">
							重置
						</view>
						<view class="check color14">
							查看
						</view>
					</view>
				</view>
				<!-- 弹出层位置 -->
				<view class="popup-content" v-if="showLocation">
					<view class="popup-content_flex popup-location fui-flex">
						<view class="">
							<scroll-view scroll-y scroll-with-animation class="tab-view"
								:scroll-into-view="scrollViewId" :style="{ height: height + 'px', top: top + 'px' }">
								<view :id="`id_${index}`" v-for="(item, index) in tabbar" :key="index"
									class="tab-bar-item" :class="[currentTab == index ? 'active' : '']"
									:data-current="index" @tap.stop="swichNav">
									<text>{{ item.title }}</text>
								</view>
							</scroll-view>
						</view>
						<view class="location_self">
							<!-- 直线距离 -->
							<view class="" v-for="(item,index) in tabbar" :key="index">
								<scroll-view scroll-y="true" v-if="currentTab === index">
									<view class="" v-for="(item1,ins) in item.list" :key="ins">
										<view class="location_self_item fui-flex justify-between">
											<view class="">
												{{item1.title}}
											</view>
											<view class="">
												<iconfont className="icon-duigou2 text-bold" :size="18" color="#00AFC7">
												</iconfont>
											</view>
										</view>
									</view>
								</scroll-view>
							</view>
						</view>
					</view>

					<view class="popup-price_bottom bottom_btn fui-flex font_size_14">
						<view class="reset">
							重置
						</view>
						<view class="check color14">
							查看
						</view>
					</view>
				</view>

				<!-- 弹出层价格 -->
				<view class="popup-content" v-if="showPrices">
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
								<cjSlider v-model="priceValue" activeColor="#00AFC7" :blockWidth="56" moveHeight='100'
									@start="blockStart" @end="blockEnd"></cjSlider>
							</view>
							<view class="fui-flex justify-around padding-top-lg">
								<view class="fui-flex flex-direction" v-for="(item,index) in moneyList" :key="index">
									<view class="popup-price_line"></view>
									<view class="popup-price_num">
										￥{{item.moneyNum}}
									</view>
								</view>
							</view>
						</view>
						<!-- 选择价格 -->
						<view class="popup-price_lr_list fui-flex">
							<view class="popup-price_lr_bg color2" v-for="(item,index) in intervalList" :key="index">
								<view class="">
									{{item.intervalNum}}
								</view>
							</view>
						</view>
					</view>
					<view class="popup-price_bottom fui-flex font_size_14">
						<view class="reset">
							重置
						</view>
						<view class="check color14">
							查看
						</view>
					</view>
				</view>
				<!-- 弹出层漏斗 -->
				<view class="popup-content" v-if="showFunnel">
					<view class="popup-content_flex popup-location fui-flex">
						<view class="">
							<scroll-view scroll-y scroll-with-animation class="tab-view"
								:scroll-into-view="scrollViewId" :style="{ height: height + 'px', top: top + 'px' }">
								<view :id="`id_${index}`" v-for="(item, index) in funnelList" :key="index"
									class="tab-bar-item" :class="[currentTab == index ? 'active' : '']"
									:data-current="index" @tap.stop="swichNav">
									<text>{{ item.title }}</text>
								</view>
							</scroll-view>
						</view>
						<view class="location_self">
							<!-- 推荐筛选 -->
							<scroll-view scroll-y :style="{height:height + 'px', top:top + 'px'}"
								:scroll-with-animation="true" :scroll-into-view="clickId" @scroll="scroll"
								:cancelable="true" @scrolltolower="scrolltolower">
								<!-- 推荐筛选 -->
								<view class="funnel_item" v-for="(item,index) in funnelList" :key="index">
									<view class="funnel_item_title" :id="'po'+index">
										{{item.title}}
									</view>
									<view class="funnel_item_wrap fui-flex">
										<view class="funnel_item_con funnel_item_only"
											v-for="(item1,index1) in item.list" :key="index1"
											@click="selectFunnelBtn(item1)"
											:class="{'funnel_item_bg':fSelect.indexOf(item1) !== -1}">
											{{item1.value}}
											<view v-if="fSelect.indexOf(item1) !== -1" class="funnel_item_bg_icon">
												<iconfont className="icon-duigou2" color="#FFFFFF" :size="4">
												</iconfont>
											</view>
										</view>
									</view>
								</view>
							</scroll-view>
						</view>
					</view>
					<view class="popup-price_bottom bottom_btn fui-flex font_size_14">
						<view class="reset">
							重置
						</view>
						<view class="check color14">
							查看
						</view>
					</view>
				</view>
			</view>
		</view>

		<view class="room-scorll">
			<!-- 滚动 -->
			<view class="fui-tabs">
				<scroll-view id="tab-bar" scroll-with-animation class="fui-scroll-h" :scroll-x="true"
					:show-scrollbar="false" :scroll-into-view="scrollInto">
					<view v-for="(tab, index) in tabBars" :key="tab.id" class="fui-tab-item" :id="tab.id"
						:data-current="index" @click="tabClick">

						<view class="fui-tab-item-title" :class="{ 'fui-tab-item-title-active': tabIndex == index }">
							{{ tab.name }}
						</view>
					</view>
				</scroll-view>
			</view>
			<!-- 列表 -->
			<view class="">
				<view class="room-scorll-list" v-for="(item,index) in apartmentList" :key="index">
					<view class="room-scorll-list_img">
						<image :src="item.thumb" mode="" style="width: 100%;height: 100%;border-radius: 10rpx;"
							@click="apartmentDetail(item)"></image>
						<view class="room-scorll-list_icon" @click="collectBtn(item)">
							<iconfont :className="item.is_like ? 'icon-full-love' : 'icon-love'" :size="26"
								:color="item.is_like ? '#FB5B32' : '#ffffff'"></iconfont>
						</view>
						<view class="room-scorll-list_assess fui-flex">
							<view class="margin-bottom-xs">
								<fui-rate :quantity="1" :current="1" active="#FF9500" :size="12"></fui-rate>
							</view>
							<view class="font_size_12 text-bold">
								{{(+item.comment_start).toFixed(1)}}分
							</view>
						</view>
					</view>
					<view class="font_size_18 text-bold margin-top-sm">
						{{item.title}}
					</view>
					<view class="room-scorll-list_txt fui-flex margin-tb-xs font_size_13 color2">
						<view class="txt color4 text-bold"
							:style="{color:item.lease_type_str==='整租' ? greColor : yelColor}">{{item.lease_type_str}}
						</view>
						<view class="txt">{{item.room_num}}居室</view>
						<view class="txt">{{item.bed}}床</view>
						<view class="txt">{{item.persons}}人</view>
					</view>
					<view class="room-scorll-list_bor fui-flex">
						<view class="bor_con bor_red" v-for="(item1,ins) in item.service" :key="ins">
							{{item1}}
						</view>
						<!-- <view class="bor_con bor_gray">
							长租优惠
						</view>
						<view class="bor_con bor_gray">
							很干净
						</view>
						<view class="bor_con bor_gray">
							可带宠物
						</view> -->
					</view>
					<view class="font_size_12 margin-top-xs">
						<text class="color6 text-bold">￥</text><text
							class="font_size_20 color6 text-bold">{{(+item.cprice).toFixed(2)}}</text>/晚
					</view>
				</view>
			</view>
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
				searchBack:true,
				showSort: false,
				showPrices: false,
				showNumber: false, //人数
				showLocation: false, //位置
				showFunnel: false, //漏斗
				showModal: false, //收藏
				single1: undefined, // 入住时间
				startTime: '', //当前日期组件选中开始时间
				endTime: '', //当前日期组件选中结束时间
				indexStartTime: '', //首页传递开始时间
				indexEndTime: '', //首页传递结束时间
				defaultObj: uni.getStorageSync('defaultTime'), //首页默认日期
				getTime: uni.getStorageSync('orderTime'), //首页修改后的日期
				tabIndex: 0,
				tabBars: [{
						name: '新房特惠',
						id: 'hot'
					},
					{
						name: '酒店套餐',
						id: 'yule'
					},
					{
						name: '大床房',
						id: 'sports'
					},
					{
						name: '家庭房',
						id: 'domestic'
					},
					{
						name: '免费停车',
						id: 'finance'
					},
					{
						name: '标准房',
						id: 'keji'
					},
					{
						name: '亲子房',
						id: 'qinzi'
					}
				],
				scrollInto: '',
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
				adultVal: 0, //数字输入框
				childVal: 0,
				patronVal: 0,
				// 位置
				tabbar: [],
				height: 0, //scroll-view高度
				top: 0,
				currentTab: 0, //预设当前项的值
				scrollViewId: "id_0",
				// 漏斗筛选
				funnelList: [],
				apartmentList: [],
				greColor: '#00AFC7',
				yelColor: '#FF9500',
				persionTypeObj: {}, //人数
				sSelect: [], // 综合排序
				clickId: '', //漏斗选中的id
				isLeftClick: false,
				topList: [],
				fSelect: [], //漏斗选中值
				foundWishTxt: '', //添加心愿单
				wishList: [], //心愿列表
				hotelId:undefined,
			};
		},
		onLoad(options) {
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
			that.getHotelInitialize() //民宿列表初始化
			that.getApartList()
		},
		computed: {
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
		methods: {
			// 民宿初始化
			getHotelInitialize() {
				let that = this
				that.fui
					.request('/diandi_hotel/mobile/hotel/listinit', 'GET', {}, false)
					.then(res => {
						that.sortObj = res.data.HotelOrderBy
						that.persionTypeObj = res.data.PersionType
						// 位置
						const localkeys = Object.keys(res.data.rimList.RimType)
						localkeys.forEach(key => {
							that.tabbar.push(res.data.rimList.RimType[key])
						})
						that.tabbar = that.tabbar.map((item, index) => {
							return {
								title: item,
								list: Object.values(res.data.rimList.list)[index]
							}
						})
						// 综合搜索
						const funnelkeys = Object.keys(res.data.searchList.searchCate)
						funnelkeys.forEach(key => {
							that.funnelList.push(res.data.searchList.searchCate[key])
						})
						that.funnelList = that.funnelList.map((item, index) => {
							return {
								title: item,
								list: Object.values(res.data.searchList.list)[index]
							}
						})
					}).catch(res => {
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
					})
			},
			// 民宿列表
			getApartList() {
				let that = this
				that.fui
					.request('/diandi_hotel/mobile/room/list', 'GET', {}, false)
					.then(res => {
						console.log(res)
						that.apartmentList = res.data
					}).catch(res => {
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
					})
			},
			changeTime(e) {
				let that = this
				// console.log(e)
				that.startTime = e[0].split('-').splice(1, 2).join('.')
				that.endTime = e[1].split('-').splice(1, 2).join('.')
				// console.log(that.startTime, that.endTime)
			},
			tabClick(e) {
				let index = e.target.dataset.current || e.currentTarget.dataset.current;
				this.switchTab(index);
			},
			switchTab(index) {
				if (this.tabIndex === index) return;
				this.tabIndex = index;
				let scrollIndex = index - 1 < 0 ? 0 : index - 1;
				this.scrollInto = this.tabBars[scrollIndex].id;
			},
			// 酒店心愿开始
			cancelModal() {
				this.showModal = false
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
			selectWish(item) {
				let that = this
				that.fui
					.request('/diandi_hotel/wish/addwish', 'POST', {
						hotel_id: that.hotelId,
						room_id: that.roomId, // 酒店固定是0
						cate_id: item.id
					}, false)
					.then(res => {
						if (res.code === 200) {
							that.getApartList()
							that.showModal = false
						}
					}).catch(res => {
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
					})
			},
			collectBtn(item) {
				let that = this
				that.hotelId = item.hotel_id
				that.roomId = item.id
				if (!item.is_like) {
					that.getWishList()
					that.showModal = true
				} else {
					that.showModal = false
					that.fui
						.request('/diandi_hotel/wish/delwish', 'POST', {
							hotel_id: item.hotel_id,
							room_id: item.id, // 酒店固定是0
						}, false)
						.then(res => {
							that.getApartList()
						}).catch(res => {
							uni.showToast({
								title: res.message,
								icon: 'none'
							})
						})
				}
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
			// 酒店心愿结束
			// 点击收藏
			// collectApart(item) {
			// 	let that = this
			// 	if (!item.is_like) {
			// 		that.fui.request('/diandi_hotel/wish/addwish', 'POST', {
			// 			hotel_id: item.hotel_id,
			// 			room_id: item.id
			// 		}, false).then(res => {
			// 			that.getApartList()
			// 		}).catch(res => {
			// 			uni.showToast({
			// 				title: res.message,
			// 				icon: 'none'
			// 			})
			// 		})
			// 	} else {
			// 		that.fui.request('/diandi_hotel/wish/delwish', 'POST', {
			// 			hotel_id: item.hotel_id,
			// 			room_id: item.id
			// 		}, false).then(res => {
			// 			that.getApartList()
			// 		}).catch(res => {
			// 			uni.showToast({
			// 				title: res.message,
			// 				icon: 'none'
			// 			})
			// 		})
			// 	}
			// },
			// 点击筛选
			sortBtn() {
				let that = this
				that.showSort = !that.showSort
				that.showPrices = false
				that.showNumber = false
				that.showLocation = false
				that.showFunnel = false
			},
			// 选中状态
			selectSort(item) {
				let that = this
				if (that.sSelect.indexOf(item) === -1) {
					that.sSelect.push(item) //选中添加到数组中
				} else {
					that.sSelect.splice(that.sSelect.indexOf(item), 1) //取消
				}
			},
			priceBtn() {
				let that = this
				that.showPrices = !that.showPrices
				that.showSort = false
				that.showNumber = false
				that.showLocation = false
				that.showFunnel = false
			},
			numberBtn() {
				let that = this
				that.showNumber = !that.showNumber
				that.showSort = false
				that.showPrices = false
				that.showLocation = false
				that.showFunnel = false
			},
			locationBtn() {
				let that = this
				that.showLocation = !that.showLocation
				that.showSort = false
				that.showPrices = false
				that.showNumber = false
				that.showFunnel = false
			},
			funnelBtn() {
				let that = this
				that.showFunnel = !that.showFunnel
				that.showSort = false
				that.showPrices = false
				that.showNumber = false
				that.showLocation = false
			},
			selectFunnelBtn(item) {
				let that = this
				if (that.fSelect.indexOf(item) === -1) {
					that.fSelect.push(item) //选中添加到数组中
				} else {
					that.fSelect.splice(that.fSelect.indexOf(item), 1) //取消
				}
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
			hotelDetail() {
				let that = this
				that.$Router.push({
					name: "apartmentQuerydetail"
				})
			},
			// 成人
			adultValNum(e) {
				let that = this
				that.adultVal = e.value
			},
			childValNum(e) {
				let that = this
				that.childVal = e.value
			},
			patronValNum(e) {
				let that = this
				that.patronVal = e.value
			},
			// 点击跳转到详情页面
			apartmentDetail(item) {
				let that = this
				// if (item.lease_type_str === '整租') {
				that.$Router.push({
					name: 'whole',
					params: {
						room_id: item.id
					}
				})
				console.log(item.id)
				// } else {
				// that.$Router.push({
				// 	name: 'combine',
				// 	params:{
				// 		room_id:item.id
				// 	}
				// })
				// }
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
	}
</script>

<style lang="scss" scoped>
	.container {
		padding: 84rpx 0 30rpx;

		.apartment_fixed {
			width: 100%;
			position: fixed;
			left: 0;
			//	#ifdef APP-PLUS
			top: 146rpx;
			//	#endif
			//	#ifdef H5
			top: 88rpx;
			//	#endif
			//	#ifdef MP-WEIXIN
			top: 228rpx;
			//	#endif
			background-color: #FFFFFF;
			z-index: 98;
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

			// 弹出层
			.popup-content {
				width: 750rpx;
				position: absolute;
				top: 88rpx;
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

				.bottom_btn {
					padding-top: 0;
				}

			}

			.popup-content::before {
				content: "";
				position: absolute;
				top: 0;
				left: 0;
				width: 750rpx;
				height: 100vh;
				background-color: rgba(0, 0, 0, .3);
				z-index: -999;
			}

		}

		.room-scorll {
			margin: 32rpx 32rpx 0;
			box-sizing: border-box;

			.fui-scroll-h {
				width: 750rpx;
				white-space: nowrap;
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
			}

			.room-scorll-list {
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

					.txt::after {
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
</style>