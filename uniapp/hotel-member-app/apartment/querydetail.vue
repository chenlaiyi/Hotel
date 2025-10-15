<template>
	<view class="container">
		<!-- 公寓详情 -->
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="公寓详情" color="#000000" :border="false" fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="swiper-top">
			<swiper :autoplay="true" :interval="3000" :duration="1000" autoplay circular style="height: 520rpx;"
				@change="changeCur">
				<swiper-item v-for="(item,index) in wholeSwiperList" :key="index">
					<image :src="item" mode="aspectFill" style="width: 100%;height: 520rpx;">
					</image>
				</swiper-item>
			</swiper>
			<!-- <view class="swiper-top_icon">
				<hx-navbar :back="true" backgroundColor="transparent" color="#ffffff">
					<template slot="right">
						<view class="fui-flex">
							<view class="margin-right-sm" @click="loveSwipeBtn">
								<iconfont :className="showLoveSwipe ? 'icon-full-love' : 'icon-love'" :size="25"
									:color="showLoveSwipe ? '#FB5B32' : '#ffffff' "></iconfont>
							</view>
							<view class="">
								<iconfont className="icon-upload" :size="25" color="#ffffff"></iconfont>
							</view>
						</view>
					</template>
				</hx-navbar>
			</view> -->
			<view class="swiper-top_assess">
				<view class="fui-flex swiper-top_assess_num">
					<view class="font_size_20 color6 text-bold">
						{{(+querydetailObj.level).toFixed(1)}}
					</view>
					<view class="swiper-top_assess_num_line"></view>
					<view class="color14 font_size_10" @click="toChat">
						{{querydetailObj.comment_num || 0}}条评价
					</view>
				</view>
			</view>
			<view class="swiper-top_page color14">
				{{current}}/{{wholeSwiperList.length}}
			</view>
		</view>
		<view class="whole">
			<view class="font_size_18 text-bold margin-tb-sm">
				{{querydetailObj.name}}
			</view>
			<view class="whole-appraise fui-flex">
				<view class="whole_bg bg_gray" v-for="(item,index) in querydetailObj.device" :key="index">
					{{item}}
				</view>
			</view>
			<view class="whole-serve fui-flex">
				<view class="fui-flex margin-right-xs" v-for="(item,index) in querydetailObj.service" :key="index">
					<view class="">
						<image :src="item.thumb" mode="" style="width: 24rpx;height: 24rpx;border-radius: 50%;"></image>
					</view>
					<view class="whole-serve_txt">
						{{item.title}}
					</view>
				</view>
			</view>
			<!-- 地址 -->
			<view class="whole_address fui-flex justify-between">
				<view class="font_size_14 address">
					{{querydetailObj.address}}
				</view>
				<view class="fui-flex">
					<view class="font_size_12 color4">
						地图导航
					</view>
					<view class="" style="margin-bottom: 6rpx;">
						<iconfont className="icon-youjiantou1" color="#999999" :size="6"></iconfont>
					</view>
				</view>
			</view>
			<!-- 优惠券 -->
			<view class="whole-coupons fui-flex justify-between">
				<view class="fui-flex">
					<view class="coupon coupon_bg">
						优惠券
					</view>
					<view class="coupon coupon_bor">
						95折最高减35
					</view>
					<view class="coupon coupon_bor">
						新人专享券
					</view>
				</view>
				<view class="fui-flex" @click="receiveBtn('bottom')">
					<view class="font_size_12">
						领取
					</view>
					<view class="" style="margin-bottom: 5rpx;">
						<iconfont className="icon-youjiantou1" :size="6" color="#999999"></iconfont>
					</view>
				</view>
			</view>
			<!-- 入住日期 -->
			<uni-datetime-picker type='daterange' :start="todayTime" v-model="single1" @change="changeTime">
				<view class="whole-date fui-flex justify-between">
					<view class="font_size_12 color2">
						入住<text class="margin-left-xs font_size_16 text-bold color4">{{orderTime.startTimeStr}}</text>
					</view>
					<view class="whole-date_night font_size_10 color13">
						{{orderTime.nightTime}}晚
					</view>
					<view class="font_size_12 color2">
						离店<text class="margin-left-xs font_size_16 text-bold color4">{{orderTime.endTimeStr}}</text>
					</view>
				</view>
			</uni-datetime-picker>
			<!-- 其他房型 -->
			<view class="whole-room">
				<view class="margin-top-lg">
					<!-- navbar-->
					<view class="fui-tabs">
						<scroll-view id="tab-bar" scroll-with-animation class="fui-scroll-h" :scroll-x="true"
							:show-scrollbar="false" :scroll-into-view="scrollInto">
							<view v-for="(tab, index) in tabBars" :key="index" class="fui-tab-item" :id="tab.id"
								:data-current="index" @click="tabClick">

								<view class="fui-tab-item-title"
									:class="{ 'fui-tab-item-title-active': tabIndex == index }">{{ tab.text }}</view>
							</view>
						</scroll-view>
					</view>
					<!-- 房类型 -->
					<view class="whole-room_other fui-flex justify-between" v-for="(item,ins) in room_list[identify]"
						:key="ins">
						<view class="fui-flex flex-certer" @click="detailBtn(item)">
							<view class="whole-room_other_img">
								<image :src="item.thumb" mode="widthFix"
									style="width: 100%;height: auto;border-radius: 10rpx;">
								</image>
							</view>
							<view class="">
								<view class="whole-room_other_title font_size_16 text-bold">
									{{item.title}}
								</view>
								<view class="fui-flex whole-room_other_txt">
									<view class="txt_every">
										{{item.room_num}}室
									</view>
									<view class="txt_every">
										{{item.bed}}床
									</view>
									<view class="txt_every">
										{{item.persons}}人
									</view>
									<view class="txt_every">
										{{item.area}}㎡
									</view>
								</view>
								<view class="font_size_12 color4">
									入住当天{{item.cancel_end}}前可免费取消
								</view>
								<view class="fui-flex">
									<view class="margin-right-sm text-center" v-for="(server,sindex) in item.server"
										:key="sindex">
										<view class="whole-room_other_bor ">
											{{server.title}}
										</view>
									</view>
								</view>
								<view class="font_size_12 color6 text-bold margin-top-sm">
									¥<text class="font_size_18">{{item.cprice}}</text>
								</view>
							</view>
						</view>
						<view class="whole-room_other_order fui-flex flex-direction justify-center"
							@click="reserveBtn(item)">
							<view class="font_size_16 margin-bottom-xs color14 text-bold">
								订
							</view>
							<view class="whole-room_other_orde_whrite color4">
								<view>在线付</view>
							</view>
						</view>
					</view>
				</view>
			</view>
			<!-- 用户评价 -->
			<view class="whole-room whole-facility">
				<view class="whole-room_title fui-flex justify-between" @click="assessBtn">
					<view class="text-bold">
						用户评价
					</view>
					<view class="fui-flex">
						<view class="font_size_11 color13" style="font-weight: 300; ">
							233评价
						</view>
						<view class="">
							<iconfont className="icon-youjiantou1" :size="8" color="#999999"
								style="font-weight: 300;margin-bottom: 3rpx;"></iconfont>
						</view>
					</view>
				</view>
				<view class="whole-room_good fui-flex">
					<view class="txt" v-for="(item,index) in querydetailObj.comment_labels" :key="index">
						{{item}}
					</view>
				</view>
				<view class="">
					<view class="fui-flex justify-between margin-tb-sm">
						<view class="fui-flex">
							<view class="margin-right-xs">
								<image :src="logo" mode="" style="width: 80rpx;height: 80rpx;border-radius: 50%;">
								</image>
							</view>
							<view class="">
								<view class="font_size_14 text-bold">
									{{lanRealname}}
								</view>
								<view class="font_size_10 color13 margin-top-xs">
									{{lanCreateTime}}
								</view>
							</view>
						</view>
						<view class="">
							<fui-rate :quantity="5" :current="3" :disabled="true" :size="16"
								active="#FF9500"></fui-rate>
						</view>
					</view>
					<view class="font_size_12 margin-bottom-sm">
						{{lanContent}}
					</view>
				</view>
			</view>
			<!-- 酒店设施 -->
			<view class="whole-room whole-facility">
				<view class="whole-room_title">
					<view class="text-bold">
						酒店设施
					</view>
				</view>
				<view class="fui-flex margin-tb-sm" style="flex-wrap: wrap">
					<view class="fui-col-3" v-for="(item,index) in querydetailObj.service" :key="index"
						style="text-align: center;">
						<view class="">
							<!-- <iconfont className="icon-a--02" :size="30"></iconfont> -->
							<image :src="item.thumb" mode="" style="width: 44rpx;height: 44rpx;"></image>
						</view>
						<view class="margin-bottom-xs font_size_12">
							免费停车
						</view>
					</view>
					<view class="fui-col-3 margin-tb-sm" style="text-align: center;">
						<view class="font_size_18 color4">
							+6
						</view>
						<view class="margin-bottom-xs font_size_12">
							全部
						</view>
					</view>
				</view>
			</view>
			<!-- 入住须知 -->
			<view class="whole-room whole-facility">
				<view class="whole-room_title">
					<view class="text-bold">
						入住须知
					</view>
				</view>
				<view class="">
					<view class="fui-flex margin-bottom-sm font_size_13"
						v-for="(item,index) in querydetailObj.instructions" :key="index">
						<view class="fui-col-3">
							{{item.title}}：
						</view>
						<view class="fui-col-9">
							{{item.content}}
						</view>
					</view>
				</view>
			</view>

			<!-- 周边景点 -->
			<view class="whole-room whole-facility" v-if="querydetailObj.rimList.length>0">
				<view class="whole-room_title">
					<view class="text-bold">
						周边景点
					</view>
				</view>
				<view class="">
					<scroll-view id="tab-bar" scroll-with-animation class="fui-scroll-h" :scroll-x="true"
						:show-scrollbar="false">
						<view v-for="(tab, index) in querydetailObj.rimList" :key="index" class="fui-tab-items"
							:data-current="index">
							<view class="fui-tab-item-swiper"
								:class="{ 'fui-tab-item-swiper-active': swiperIndex == index }">
								<view class="swiper_image">
									<image :src="tab.thumb" mode=""
										style="width: 100%;height: 100%;border-radius: 10rpx;">
									</image>
									<view class="swiper_assess color14">
										{{tab.distance}}km
									</view>
								</view>
								<view class="text-bold margin-top-xs font_size_12"
									style="width: 200rpx;overflow: hidden;text-overflow: ellipsis;">
									{{tab.title}}
								</view>
								<view class="fui-flex margin-top-xs font_size_10">
									<view class="color5 text-bold">
										{{tab.level_star}}星
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
			<!-- 猜您喜欢 -->
			<view class="whole-room whole-facility" v-if="querydetailObj.memberView.length>0">
				<view class="whole-room_title">
					<view class="text-bold">
						猜您喜欢
					</view>
				</view>
				<hotel-items :hotel-list="querydetailObj.memberView"></hotel-items>
			</view>
		</view>
		<!-- 底部 -->
		<view class="whole-bottom fui-flex justify-between" v-if="showBottom">
			<view class="fui-flex flex-direction">
				<view class="">
					<iconfont className="icon-message" :size="34"></iconfont>
				</view>
				<view class="font_size_12">
					联系房东
				</view>
			</view>
			<view class="fui-flex">
				<view class="fui-flex flex-direction">
					<view class="font_size_12 text-bold color4">
						￥<text class="font_size_20">{{(+roomTypeObj.cprice).toFixed(2)}}</text><text
							class="color3">/晚</text>
					</view>
					<!-- <view class="whole-bottom_detail fui-flex">
						<view class="">
							明细
						</view>
						<view class="">
							<iconfont className="icon-youjiantou1" :size="6" color="#FB5B32"></iconfont>
						</view>
					</view> -->
				</view>
				<view class="margin-left-sm">
					<button class="serve_btn font_size_14" @click="reserveBtn">预订</button>
				</view>
			</view>
		</view>

		<!-- 详情弹窗 -->
		<uni-popup ref="popup" type="bottom" style="z-index: 9;">
			<view class="" style="width: 100vw;height: 100vh;overflow-y: auto;">
				<view class="popup-content">
					<view class="popup-content_img">
						<image :src="roomTypeObj.thumb" mode=""
							style="width: 100%;height: 100%;border-radius: 20rpx 20rpx 0 0;"></image>
						<view class="popup-content_close" @click="closeBtn">
							<iconfont className="icon-quxiao" :size="14" color="#FFFFFF"></iconfont>
						</view>
					</view>
					<view class="whole">
						<view class="whole-room">
							<view class="whole-room_title">
								<view class="text-bold">
									{{roomTypeObj.title}}
								</view>
							</view>
							<view class="popup-content_introduce">
								<view class="fui-flex fui-col-6 font_size_12 margin-bottom-xs">
									<view class="margin-right-xs color13">
										床型
									</view>
									<view class="">
										1张大床1.8米
									</view>
								</view>
								<view class="fui-flex fui-col-6 font_size_12 margin-bottom-xs">
									<view class="margin-right-xs color13">
										面积
									</view>
									<view class="">
										{{roomTypeObj.area}}㎡
									</view>
								</view>
								<view class="fui-flex fui-col-6 font_size_12 margin-bottom-xs">
									<view class="margin-right-xs color13">
										楼层
									</view>
									<view class="">
										{{roomTypeObj.floor}}层
									</view>
								</view>
								<view class="fui-flex fui-col-6 font_size_12 margin-bottom-xs">
									<view class="margin-right-xs color13">
										几室
									</view>
									<view class="">
										{{roomTypeObj.room_num}}
									</view>
								</view>
								<view class="fui-flex fui-col-6 font_size_12 margin-bottom-xs">
									<view class="margin-right-xs color13">
										几卫
									</view>
									<view class="">
										{{roomTypeObj.toilet_num}}
									</view>
								</view>
								<view class="fui-flex fui-col-6 font_size_12 margin-bottom-xs">
									<view class="margin-right-xs color13">
										吸烟
									</view>
									<view class="">
										可吸烟
									</view>
								</view>
								<view class="fui-flex fui-col-6 font_size_12 margin-bottom-xs">
									<view class="margin-right-xs color13">
										人数
									</view>
									<view class="">
										2人
									</view>
								</view>
								<view class="fui-flex fui-col-6 font_size_12 margin-bottom-xs">
									<view class="margin-right-xs color13">
										餐食
									</view>
									<view class="">
										<text v-if="roomTypeObj.breakfast===0">无早餐</text>
										<text v-if="roomTypeObj.breakfast===1">单份早餐</text>
										<text v-if="roomTypeObj.breakfast===2">双份早餐</text>
									</view>
								</view>
							</view>
							<view class="popup-content_flex margin-top-xs" v-if="unfoldShow">
								<view class="fui-flex margin-bottom-sm">
									<view class="popup-content_flex_self font_size_12 fui-col-2 color13">
										费用政策
									</view>
									<view class="fui-col-10 popup-content_flex_txt font_size_12">
										<view class="">
											清洁费：{{roomTypeObj.cleaning_fee}}
										</view>
										<!-- <view class="">
											加床：￥350/床/间夜
										</view> -->
										<view class="">
											服务费：{{roomTypeObj.server_fee}}
										</view>
									</view>
								</view>
								<view class="fui-flex margin-bottom-sm">
									<view class="popup-content_flex_self font_size_12 fui-col-2 color13">
										床位信息
									</view>
									<view class="fui-col-10 popup-content_flex_txt font_size_12">
										床位数：{{roomTypeObj.bed}};
										儿童床位数：{{roomTypeObj.bed_children}};成人床位数：{{roomTypeObj.bed_adult}};客人床位数：{{roomTypeObj.bed_guest}};最多容纳{{roomTypeObj.persons}}人
									</view>
								</view>
								<!-- <view class="fui-flex margin-bottom-sm">
									<view class="popup-content_flex_self font_size_12 fui-col-2 color13">
										食品饮品
									</view>
									<view class="fui-col-10 popup-content_flex_txt font_size_12">
										瓶装水免费，迷你吧，冰箱免费，电热水壶，茶艺工具，茶包，酒精饮料
									</view>
								</view> -->
								<!-- <view class="fui-flex margin-bottom-sm">
									<view class="popup-content_flex_self font_size_12 fui-col-2 color13">
										便利设施
									</view>
									<view class="fui-col-10 popup-content_flex_txt font_size_12">
										多种规格电源插座，110V电压插座，220V电压插座，遮光窗帘，手动窗帘，房内保险箱，书桌，办公用品（笔、便签），沙发，备用床具，床具:鸭绒被，床具:毯子或被子，电子秤，针线包，衣柜/衣橱，空调免费，暖气，房间内高速上网，客房WiFi免费，地毯，熨衣设备免费，衣架，茶几，一次性拖鞋，一次性漱口杯，一次性毛巾免费
									</view>
								</view> -->
								<!-- <view class="fui-flex margin-bottom-sm">
									<view class="popup-content_flex_self font_size_12 fui-col-2 color13">
										媒体科技
									</view>
									<view class="fui-col-10 popup-content_flex_txt font_size_12">
										电话，国际长途电话，液晶电视机，有线频道，卫星频道，电视机，智能门锁
									</view>
								</view> -->
								<!-- <view class="fui-flex margin-bottom-sm">
									<view class="popup-content_flex_self font_size_12 fui-col-2 color13">
										浴室配套
									</view>
									<view class="fui-col-10 popup-content_flex_txt font_size_12">
										独立卫生间，24小时热水，浴缸，吹风机，拖鞋，浴巾，浴衣免费，浴室化妆放大镜
									</view>
								</view> -->
								<!-- <view class="fui-flex margin-bottom-sm">
									<view class="popup-content_flex_self font_size_12 fui-col-2 color13">
										洗浴用品
									</view>
									<view class="fui-col-10 popup-content_flex_txt font_size_12">
										牙刷，牙膏，洗发水，沐浴露，护发素，浴帽，香皂，梳子，剃须刀，毛巾
									</view>
								</view> -->
							</view>
							<view class="" @click="unfoldBtn">
								<view class="fui-flex justify-center margin-tb-sm">
									<view class="font_size_12 color4">{{unfoldShow ? "收起" : "展开"}}</view>
									<view class="">
										<iconfont :className="unfoldShow ? 'icon-xiangshangjiantou' : 'icon-xiajiantou'"
											style="transform: scale(0.6);" color="#00AFC7"></iconfont>
									</view>
								</view>
							</view>
						</view>
						<!-- 政策服务 -->
						<view class="whole-room whole-facility">
							<view class="whole-room_title">
								<view class="text-bold">
									政策服务
								</view>
							</view>
							<view class="">
								<view class="popup-content_flex fui-flex padding-bottom-sm">
									<view class="popup-content_flex_self">
										<iconfont className="icon-dian" :size="20" color="#00AFC7"></iconfont>
									</view>
									<view class="">
										<view class="font_size_14">
											即时确认
										</view>
										<view class="font_size_12 color13">
											预订此房型后可快速确认订单。
										</view>
									</view>
								</view>
								<view class="popup-content_flex fui-flex padding-bottom-sm">
									<view class="popup-content_flex_self">
										<iconfont className="icon-dian" :size="20" color="#00AFC7"></iconfont>
									</view>
									<view class="">
										<view class="font_size_14">
											商家取消政策
										</view>
										<view class="font_size_12 color13 ">
											<view class="color4">
												限时取消
											</view>
											<view class="">
												【2023-04-2018:00】前可免费取消，逾期不可取消/变更，如未入住，酒店将扣除全额房费。
											</view>
										</view>
									</view>
								</view>
								<view class="popup-content_flex fui-flex padding-bottom-sm">
									<view class="popup-content_flex_self">
										<iconfont className="icon-dian" :size="20" color="#00AFC7"></iconfont>
									</view>
									<view class="">
										<view class="font_size_14">
											儿童及加床
										</view>
										<view class="font_size_12 color13">
											每间客房最多容纳1名儿童，和成人共用现有床铺。该房型不提供加床，不提供加婴儿床。
										</view>
									</view>
								</view>
							</view>
						</view>
						<!-- 安心服务 -->
						<view class="whole-room whole-facility">
							<view class="whole-room_title">
								<view class="text-bold">
									安心服务
								</view>
							</view>
							<view class="">
								<view class="popup-content_flex fui-flex padding-bottom-sm">
									<view class="popup-content_flex_self">
										<iconfont className="icon-dian" :size="20" color="#00AFC7"></iconfont>
									</view>
									<view class="">
										<view class="font_size_14">
											预约发票
										</view>
										<view class="font_size_12 color13">
											下单后可预约酒店开具发票，退房时直接在前台领取
										</view>
									</view>
								</view>
							</view>
						</view>
						<!-- 促销优惠 -->
						<view class="whole-room whole-facility">
							<view class="whole-room_title">
								<view class="text-bold">
									促销优惠
								</view>
							</view>
							<view class="fui-flex">
								<view class="popup-content_new margin-right-sm">
									新人优惠券
								</view>
								<view class="font_size_12">
									新客随机酒店预订优惠(仅限首次支付)
								</view>
							</view>
						</view>
						<!-- 费用明细 -->
						<view class="whole-room whole-facility">
							<view class="whole-room_title">
								<view class="text-bold">
									费用明细
								</view>
							</view>
							<view class="popup-content_detail">
								<view class="fui-flex justify-between padding-tb-sm text-bold">
									<view class="font_size_14">
										房费
									</view>
									<view class="font_size_16">
										¥{{(+roomTypeObj.cprice).toFixed(2)}}
									</view>
								</view>
								<!-- <view class="popup-content_detail_tb">
									<view class="fui-flex justify-between font_size_14 text-bold padding-tb-sm">
										<view class="">
											优惠
										</view>
										<view class="color6">
											-¥30
										</view>
									</view>
									<view class="fui-flex justify-between font_size_12 padding-bottom-sm">
										<view class="">
											新人首单价
										</view>
										<view class="">
											-¥20
										</view>
									</view>
									<view class="fui-flex justify-between font_size_12 padding-bottom-sm">
										<view class="">
											折扣券
										</view>
										<view class="">
											-¥10
										</view>
									</view>
								</view> -->
								<view class="fui-flex justify-end padding-tb-sm">
									<view class="font_size_12">
										每间每晚
									</view>
									<view class="font_size_20 text-bold color4 margin-left-xs">
										¥{{(+roomTypeObj.cprice).toFixed(2)}}
									</view>
								</view>
							</view>
						</view>
					</view>
				</view>
			</view>
		</uni-popup>


		<!-- 领取优惠券 -->
		<uni-popup type="bottom" ref="assess" style="z-index: 999;" v-if="tackCouponList.length > 0">
			<view class="assess">
				<view class="assess_pad fui-flex justify-end">
					<view class="font_size_16 text-bold" style="margin-right: 245rpx;">
						领取优惠券
					</view>
					<view class="" @click="closeLy">
						<iconfont className="icon-quxiao" :size="18" color="#999999"></iconfont>
					</view>
				</view>
				<view class="assess_cards" style="z-index: 70;">
					<view class="assess_cards-img fui-flex" v-for="(item,index) in tackCouponList" :key="index">
						<view class="assess_cards-img_left">
							<image src="https://oss.ddicms.cn/member/mys/cards_used.png" mode=""
								style="width: 100%;height: 100%;">
							</image>
							<view class="card-img_txt flex-direction">
								<view class="card-img_txt_money">
									<text class="text-40">¥</text>{{Math.round(+item.cash)}}
								</view>
								<view class="card-img_txt_used">
									满{{item.min_order_price}}可用
								</view>
							</view>
						</view>
						<view class="margin-left-lg card-img_full fui-flex-1 fui-flex justify-between">
							<view class="">
								<view class="font_size_16 text-bold" v-if="item.type === 1">
									代金券
								</view>
								<view class="font_size_16 text-bold" v-if="item.type === 2">
									优惠券
								</view>
								<view class="font_size_12 color13 margin-top-sm margin-bottom-xs">
									{{item.enable_store}}
								</view>
								<view class="font_size_12 color13">
									{{(item.enable_end).slice(0,10)}}到期
								</view>
							</view>
							<view class="fui-col-1 color4 font_size_12 margin-right-lg" v-if="item.is_get===false"
								@click="useCouponBtn(item)">
								立即使用
							</view>
							<view class="color4 font_size_12" v-else>
								<image src="https://oss.ddicms.cn/member/mys/bg_used.png" mode=""
									style="width: 144rpx;height: 120rpx;margin: 60rpx 32rpx 0 0;"></image>
							</view>
						</view>
					</view>

					<!-- <view class="assess_cards-img fui-flex">
						<view class="assess_cards-img_left">
							<image src="https://oss.ddicms.cn/member/mys/cards_expird.png" mode="" style="width: 100%;height: 100%;">
							</image>
							<view class="card-img_txt">
								<view class="fui-flex flex-direction justify-center align-center">
									<view class="card-img_txt_money">
										9<text class="text-40">折</text>
									</view>
									<view class="card-img_txt_used">
										最高减100元
									</view>
								</view>
							</view>
						</view>
						<view class="margin-left-lg card-img_full fui-flex-1 fui-flex justify-between">
							<view class="">
								<view class="font_size_16 text-bold">
									折扣券
								</view>
								<view class="font_size_12 color13 margin-top-sm margin-bottom-xs">
									活动通用，部分酒店除外
								</view>
								<view class="font_size_12 color13">
									2023年3月31日到期
								</view>
							</view>
							<view class="color4 font_size_12">
								<image src="https://oss.ddicms.cn/member/mys/icon_used.png" mode=""
									style="width: 144rpx;height: 120rpx;margin: 60rpx 32rpx 0 0;"></image>
							</view>
						</view>
					</view> -->
				</view>
			</view>
		</uni-popup>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				hotel_id: 0,
				logo:'',
				collectLove: true,
				unfoldShow: false,
				showLoveSwipe: false,
				showBottom: false, //底部显示
				single1: '', // 入住时间
				current: 1,
				wholeSwiperList: [],
				tabIndex: 0,
				tabBars: [],
				scrollInto: '',
				swiperIndex: 0,
				// swiperList: [{
				// 		name: '热门',
				// 		id: 'hot'
				// 	},
				// 	{
				// 		name: '娱乐',
				// 		id: 'yule'
				// 	},
				// 	{
				// 		name: '体育',
				// 		id: 'sports'
				// 	},
				// 	{
				// 		name: '国内',
				// 		id: 'domestic'
				// 	},
				// 	{
				// 		name: '财经',
				// 		id: 'finance'
				// 	},
				// 	{
				// 		name: '科技',
				// 		id: 'keji'
				// 	},
				// 	{
				// 		name: '教育',
				// 		id: 'education'
				// 	},
				// 	{
				// 		name: '汽车',
				// 		id: 'car'
				// 	}
				// ],
				querydetailObj: {
					rimList:[],
					memberView:[]
				}, //data全部数据
				room_list: {}, //房型选择
				roomTypeObj: {}, //房间详情
				identify: '',
				lanAvatar: '',
				lanCreateTime: '',
				lanRealname: '',
				lanContent: '',
				tackCouponList: [], //领取优惠券
				todayTime: '',
				orderTime: {
					startTime: '',
					endTime: '',
					nightTime: '',
					personNum: {

					},
					startTimeStr: '',
					endTimeStr: ''
				}
			};
		},
		watch: {
			orderTime: {
				handler(newVal, oldVal) {
					if (newVal) {
						console.log('orderTime-change', newVal)
						// 本地存储
						uni.setStorageSync('orderTime', newVal)
					}
				},
				deep: true //对象中任一属性值发生变化，都会触发handler方法
			}
		},
		onShow() {
			let that = this
			that.todayTime = that.fui.iglobal.formatDate(new Date())
			that.initTimeStr()
		},
		onLoad(options) {
			let that = this
			const hotel_id = that.$Route.query.hotel_id
			that.hotel_id = hotel_id
			that.getHotelDetail(hotel_id)
		},
		methods: {
			initTimeStr() {
				let that = this
				const orderTime = uni.getStorageSync('orderTime')
				console.log('initTimeStr-deta', orderTime)
				if (orderTime) {
					that.orderTime = orderTime
				} else {
					let today = new Date();
					// 获取明天的日期
					let tomorrow = new Date(today.getTime() + 86400000); // 减去一天的毫秒数
					that.orderTime = {
						startTime: that.fui.iglobal.formatDate(today),
						endTime: that.fui.iglobal.formatDate(tomorrow),
						nightTime: 1,
						personNum: {
							adult: 1,
							child: 0
						},
						startTimeStr: that.fui.iglobal.formatDate(today, '{m}月{d}日'),
						endTimeStr: that.fui.iglobal.formatDate(tomorrow, '{m}月{d}日')
					}
				}

			},
			toChat() {
				this.$Router.push({
					name: 'chat'
				})
			},
			getHotelDetail(hotel_id) {
				let that = this
				that.$api.hotelMobileHotelDetail(hotel_id).then(res => {
					that.room_list = res.data.room_list
					that.tackCouponList = res.data.coupon
					console.log(that.tackCouponList, '55555555555')
					that.identify = Object.keys(that.room_list)[0]
					that.querydetailObj = res.data
					const landlord = res.data.landlord
					that.lanAvatar = res.data.landlord?.avatar
					that.lanRealname = res.data.landlord?.realname
					that.lanCreateTime = res.data.landlord?.member?.create_time
					that.lanContent = res.data.landlord?.content
					that.wholeSwiperList = res.data.thumbs
					that.logo =  res.data.store?.logo
					that.tabBars = res.data.room_type
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			// 房间优惠券
			// couponList(hotel_id) {
			// 	let that = this
			// 	that.fui
			// 		.request('/diandi_hotel/coupon/list', 'POST', {
			// 			hotel_id: hotel_id,
			// 			room_id: 0,
			// 			page: 1,
			// 			pageSize: 10,
			// 		}, false)
			// 		.then(res => {
			// 			that.tackCouponList = res.data.list
			// 			console.log(that.tackCouponList,'0000')
			// 		}).catch(res => {
			// 			uni.showToast({
			// 				title: res.message,
			// 				icon: 'none'
			// 			})
			// 		})
			// },
			// 使用优惠券
			useCouponBtn(item) {
				let that = this
				that.$api.hotelCouponGetcoupon(item.id).then(res => {
					if (res.code === 200) {
						that.$refs.assess.close()
					}
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			changeTime(e) {
				let that = this
				that.orderTime = {
					startTime: e[0],
					endTime: e[1],
					nightTime: (+new Date(e[1]) - +new Date(e[0])) / 1000 / 3600 / 24,
					personNum: that.personNum,
					startTimeStr: that.fui.iglobal.formatDate(e[0], '{m}月{d}日'),
					endTimeStr: that.fui.iglobal.formatDate(e[1], '{m}月{d}日')
				}
			},
			loveSwipeBtn() {
				let that = this
				that.showLoveSwipe = !that.showLoveSwipe
			},
			tabClick(e) {
				let index = e.target.dataset.current || e.currentTarget.dataset.current;
				this.switchTab(index);
				this.identify = e.currentTarget.id.replace('po', '')
			},
			switchTab(index) {
				if (this.tabIndex === index) return;
				this.tabIndex = index;
				let scrollIndex = index - 1 < 0 ? 0 : index - 1;
				this.scrollInto = this.tabBars[scrollIndex].id;
			},
			changeCur(e) {
				let that = this
				that.current = e.target.current + 1
			},
			// 明细按钮
			detailBtn(item) {
				let that = this
				that.$refs.popup.open()
				that.showBottom = true
				that.roomTypeObj = item
			},
			closeBtn() {
				let that = this
				that.$refs.popup.close()
				that.showBottom = false
			},
			// 返回按钮
			backBtn() {
				let that = this
				that.$Router.back()
			},
			// 点击收藏
			collectBtn() {
				let that = this
				that.collectLove = !that.collectLove
			},
			receiveBtn(type) {
				let that = this
				that.type = type
				that.$refs.assess.open(type)
			},
			closeLy() {
				let that = this
				that.$refs.assess.close()
			},
			// 展开收起
			unfoldBtn() {
				let that = this
				that.unfoldShow = !that.unfoldShow
			},
			// 评价
			assessBtn() {
				let that = this
				that.$Router.push({
					name: "appraise",
					params: {
						hotel_id: that.querydetailObj.id
					}
				})
			},
			// 预定
			reserveBtn(item) {
				let that = this
				that.$Router.push({
					name: 'submitorder',
					params: {
						hotel_id: item.hotel_id,
						room_id: item.id,
						cprice: item.cprice
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.container {
		padding-bottom: 0;

		.flex-certer {
			justify-content: center;
		}

		.swiper-top {
			position: relative;

			.swiper-top_icon {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
			}

			.swiper-top_assess {
				position: absolute;
				left: 24rpx;
				bottom: 24rpx;

				.swiper-top_assess_num {
					padding: 14rpx 16rpx;
					background-color: rgba(0, 0, 0, .5);
					border-radius: 10rpx;
					color: #FF9500;

					.swiper-top_assess_num_line {
						width: 1px;
						height: 32rpx;
						background-color: rgba(255, 255, 255, .3);
						margin: 0 16rpx;
					}
				}
			}

			.swiper-top_page {
				position: absolute;
				right: 24rpx;
				bottom: 24rpx;
				padding: 4rpx 13rpx;
				background-color: rgba(0, 0, 0, .5);
				border-radius: 20rpx;
				font-size: 20rpx;
			}
		}

		.whole {
			margin: 0 32rpx 0;
			box-sizing: border-box;

			.whole-appraise {
				margin: 12rpx 0;

				.whole_bg {
					padding: 4rpx 12rpx;
					border-radius: 5rpx;
					margin-right: 10rpx;
					font-size: 22rpx;
				}

				.bg_gray {
					background-color: #F9F9F9;
					color: #999999;
				}
			}

			.whole-serve {
				border-bottom: 2rpx solid #F5F5F5;
				padding-bottom: 32rpx;

				.whole-serve_txt {
					font-size: 24rpx;
					// -webkit-transform: scale(0.9);
					margin: 5rpx 10rpx 0 0;
				}
			}

			.whole_address {
				padding: 32rpx 0;
				border-bottom: 2rpx solid #F5F5F5;

				.address {
					width: 490rpx;

				}
			}

			.whole-coupons {
				margin: 32rpx 0;

				.coupon {
					border-radius: 5rpx;
					margin-right: 16rpx;
					font-size: 24rpx;
				}

				.coupon_bg {
					padding: 4rpx 12rpx;
					background-color: #FB5B32;
					color: #ffffff;
				}

				.coupon_bor {
					padding: 4rpx 16rpx;
					border: 1rpx solid #FDCDC0;
					color: #FB5B32;
				}
			}

			.whole-date {
				width: 100%;
				height: 88rpx;
				background-color: #EBF9FB;
				border-radius: 10rpx;
				padding: 0 32rpx;
				box-sizing: border-box;

				.whole-date_night {
					padding: 2rpx 20rpx;
					border: 1rpx solid #D1D1D1;
					border-radius: 32rpx;
				}
			}

			.whole-room {
				.whole-room_title {
					padding: 32rpx 0 24rpx;
					font-size: 30rpx;
				}

				.whole-room_other {
					margin-bottom: 32rpx;

					.whole-room_other_title {
						width: 400rpx;
						overflow: hidden;
						white-space: nowrap;
						text-overflow: ellipsis;
					}

					.whole-room_other_img {
						width: 160rpx;
						margin-right: 24rpx;
					}

					.whole-room_other_txt {
						margin: 10rpx 0;

						.txt_every {
							position: relative;
							margin-right: 24rpx;
							font-size: 26rpx;
							color: #666666;
						}

						.txt_every:last-child::after {
							content: "";
							background-color: transparent;
						}

						.txt_every::after {
							content: "";
							position: absolute;
							top: 50%;
							right: -12rpx;
							transform: translateY(-50%);
							width: 2rpx;
							height: 20rpx;
							background-color: #D1D1D1;
						}
					}

					.whole-room_other_bor {
						width: 96rpx;
						height: 32rpx;
						padding: 4rpx 6rpx;
						border: 2rpx solid #EEEEEE;
						font-size: 24rpx;
						margin: 12rpx 0;
						color: #999999;
					}

					.whole-room_other_order {
						width: 88rpx;
						height: 98rpx;
						background-color: #00AFC7;
						border-radius: 10rpx;

						.whole-room_other_orde_whrite {
							width: 84rpx;
							height: 32rpx;
							line-height: 32rpx;
							background-color: #ffffff;
							border-radius: 8rpx;
							text-align: center;

							view {
								-webkit-transform: scale(0.8);
								font-size: 20rpx;
							}
						}
					}
				}

				.fui-tab-items {
					display: inline-block;
					flex-wrap: nowrap;
					padding-right: 32rpx;
				}

				.fui-tab-items:last-child {
					padding-right: 60rpx;
				}

				.fui-tab-item-swiper {
					flex-wrap: nowrap;
					white-space: nowrap;

					.swiper_image {
						position: relative;
						width: 200rpx;
						height: 200rpx;
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

					// .swiper_line {
					// 	width: 1px;
					// 	height: 20rpx;
					// 	background-color: #CCCCCC;
					// }
				}

				.whole-appraise {
					padding-bottom: 11rpx;
					border-bottom: none;

				}

				.fui-scroll-h {
					width: 750rpx;
					white-space: nowrap;
					margin-bottom: 24rpx;
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
					padding: 12rpx 36rpx;
					background-color: #F9F9F9;
					color: #191B5C;
					font-size: 24rpx;
					flex-wrap: nowrap;
					white-space: nowrap;
				}

				.fui-tab-item-title-active {
					position: relative;
					font-size: 24rpx;
					text-align: center;
					background-color: #EBF9FB;
					color: #00AFC7;
					border-radius: 10rpx;
				}

				.fui-tab-item-title-active::after {
					content: "";
					position: absolute;
					bottom: 0;
					right: 0;
					width: 20rpx;
					height: 20rpx;
					background: url('https://oss.ddicms.cn/member/apartment/Selected.png') no-repeat center center;
					background-size: 100%;
				}

				.whole-text_more {
					height: 105rpx;
					overflow: hidden;
					margin: 32rpx 0;

					.more {
						line-height: 36rpx;
					}
				}

				.whole-room_good {
					flex-wrap: wrap;

					.txt {
						padding: 9rpx 21rpx;
						background-color: #F9F9F9;
						font-size: 22rpx;
						color: #999999;
						border-radius: 4rpx;
						margin-right: 14rpx;
						margin-bottom: 10rpx;
					}
				}

				.whole-room_good:last-child {
					margin-right: 0;
				}

				.room-scorll-list {
					margin-bottom: 32rpx;

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
						margin-left: 10rpx;
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

			.whole-facility {
				border-top: 2rpx solid #F5F5F5;
			}
		}

		.whole-bottom {
			position: fixed;
			bottom: 0;
			left: 0;
			padding: 0 32rpx;
			width: 100%;
			height: 120rpx;
			background-color: #ffffff;
			border-top: 1px solid #EEEEEE;
			box-sizing: border-box;
			z-index: 10;

			.whole-bottom_detail {
				align-self: flex-end;
				padding: 2rpx 10rpx;
				background: #FFEEEA;
				border-radius: 5rpx 5rpx 5rpx 5rpx;
				font-size: 20rpx;
				color: #FB5B32;
				text-align: center;
				margin-top: 5rpx;
			}

			.serve_btn {
				width: 280rpx;
				height: 80rpx;
				background-color: #00AFC7;
				color: #ffffff;
			}
		}

		.popup-content {
			width: 100%;
			border-radius: 20rpx 20rpx 0 0;
			background-color: #ffffff;
			margin-bottom: 120rpx;
			margin-top: 280rpx;
			padding-bottom: 32rpx;

			.popup-content_img {
				position: relative;
				width: 100%;
				height: 400rpx;

				.popup-content_close {
					position: absolute;
					top: 32rpx;
					right: 32rpx;
					width: 56rpx;
					height: 56rpx;
					line-height: 56rpx;
					border-radius: 50%;
					background-color: rgba(1, 1, 1, 0.5);
					text-align: center;
				}
			}

			.popup-content_introduce {
				display: flex;
				flex-wrap: wrap;
			}

			.popup-content_flex {
				width: 100%;

				.popup-content_flex_self {
					align-self: flex-start;
				}
			}

			.popup-content_new {
				padding: 4rpx 16rpx;
				border: 1px solid #FDCDC0;
				border-radius: 5rpx;
				font-size: 24rpx;
				color: #FB5B32;
			}

			.popup-content_detail {
				padding: 0 40rpx 0 24rpx;
				background-color: #F9F9F9;

				.popup-content_detail_tb {
					border-top: 2rpx solid #EEEEEE;
					border-bottom: 2rpx solid #EEEEEE;
				}
			}
		}

		.assess {
			width: 100%;
			background-color: #FFFFFF;
			border-radius: 40rpx 40rpx 0 0;
			box-sizing: border-box;
			padding-bottom: 50rpx;

			.assess_pad {
				padding: 32rpx;
			}

			.assess_cards {

				.assess_cards-img {
					position: relative;
					width: 100%;
					background: url('https://oss.ddicms.cn/member/mys/cards_bg.png') no-repeat center center;
					background-size: 100%;
					margin-top: 32rpx;

					.card-img_txt {
						position: absolute;
						left: 32rpx;
						top: 23rpx;
						width: 184rpx;
						text-align: center;
						color: #FFFFFF;

						.card-img_txt_money {
							font-size: 72rpx;
						}

						.card-img_txt_used {
							font-size: 24rpx;
						}
					}

					.assess_cards-img_left {
						width: 184rpx;
						height: 180rpx;
						margin-left: 32rpx;
					}

				}
			}
		}


	}

	::v-deep .uni-calendar__content {
		z-index: 99;
	}
</style>