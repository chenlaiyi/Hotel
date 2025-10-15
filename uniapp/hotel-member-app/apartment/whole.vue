<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="整租详情" color="#000000" :border="false"  fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<!-- 整租详情 -->
		<view class="swiper-top">
			<swiper :autoplay="true" :interval="3000" :duration="1000" autoplay circular style="height: 520rpx;"
				@change="changeCur">
				<swiper-item v-for="(item,index) in wholeSwiperList" :key="index">
					<image :src="item" mode="aspectFill" style="width: 100%;height: 520rpx;">
					</image>
				</swiper-item>
			</swiper>
			<!-- <view class="swiper-top_icon fui-flex justify-between">
				<view class="margin-left-xl" @tap="backBtn">
					<iconfont className="icon-zuojiantou" :size="22" color="#ffffff"></iconfont>
				</view>
				<view class="margin-right-lg fui-flex">
					<view class="margin-right-sm">
						<iconfont className="icon-love" :size="25" color="#ffffff"></iconfont>
					</view>
					<view class="">
						<iconfont className="icon-upload" :size="25" color="#ffffff"></iconfont>
					</view>
				</view>
			</view> -->

			<view class="swiper-top_assess">
				<view class="fui-flex swiper-top_assess_num">
					<view class="font_size_20 color6 text-bold">
						{{(+querydetailObj.comment_start).toFixed(1)}}
					</view>
					<view class="swiper-top_assess_num_line"></view>
					<view class="color14 font_size_10">
						{{querydetailObj.comment_num}}条评论
					</view>
				</view>
			</view>

			<view class="swiper-top_page color14">
				{{current}}/{{wholeSwiperList.length}}
			</view>
		</view>
		<view class="whole">
			<view class="font_size_18 text-bold">
				{{querydetailObj.name}}
			</view>
			<view class="fui-flex margin-tb-xs">
				<view class="font_size_13 color4 text-bold">
					整租
				</view>
				<view class="whole_line"></view>
				<view class="font_size_13 color2">
					{{querydetailObj.room_num}}个房间
				</view>
				<view class="whole_line"></view>
				<view class="font_size_13 color2">
					{{querydetailObj.bed}}床
				</view>
				<view class="whole_line"></view>
				<view class="font_size_13 color2">
					{{querydetailObj.persons}}人
				</view>
			</view>
			<view class="whole-appraise fui-flex">
				<view :class="index===0 ? 'bg_orange' : 'bg_gray'" class="whole_bg"
					v-for="(item,index) in querydetailObj.server" :key="item.id">
					{{item.title}}
				</view>
			</view>
			<!-- 地址 -->
			<view class="whole_address fui-flex justify-between">
				<view class="font_size_14 address">
					南三环与丈八六路交叉口西南角融城东海A座1楼(近丈八六路地铁站）
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
						周一入住<text class="margin-left-xs font_size_16 text-bold color4">{{orderTime.startTimeStr}}</text>
					</view>
					<view class="whole-date_night font_size_10 color13">
						{{orderTime.nightTime}}晚
					</view>
					<view class="font_size_12 color2">
						周二离店<text class="margin-left-xs font_size_16 text-bold color4">{{orderTime.endTimeStr}}</text>
					</view>
				</view>
			</uni-datetime-picker>
			<!-- 房屋图片 -->
			<view class="whole-room">
				<view class="whole-room_title">
					房屋图片
				</view>
				<view class="">
					<view class="fui-tabs">
						<scroll-view id="tab-bar" scroll-with-animation class="fui-scroll-h" :scroll-x="true"
							:show-scrollbar="false" :scroll-into-view="scrollInto">
							<view v-for="(tab, index) in roomSlide" :key="tab.id" class="fui-tab-item" :id="tab.id"
								:data-current="index" @click="tabClick">
								<view class="fui-tab-item-title"
									:class="{ 'fui-tab-item-title-active': tabIndex == index }">{{ tab.title }}</view>
							</view>
						</scroll-view>
					</view>
					<view class="" v-for="(item,index) in roomSlide" :key="index">
						<view class="whole-room_flex" v-if="tabIndex == index">
							<view class="whole-room_flex_img" v-for="(slide,k) in item.slide" :key="k">
								<image :src="slide" mode="" style="width: 100%;height: 100%;">
								</image>
								<view class="whole-room_flex_txt">
									{{item.title}}
								</view>
							</view>
						</view>
					
					</view>
				</view>
			</view>
			<!-- 房屋设施 -->
			<view class="whole-room whole-facility">
				<view class="whole-room_title">
					房屋设施
				</view>
				<view class="fui-flex align-start margin-bottom-sm" v-for="(item,index) in facilityList" :key="index">
					<!-- <view class="font_size_12 text-bold margin-right-sm margin-top-xs" style="align-self: flex-start;">
						{{item.facilityTitle}}
					</view> -->
					<view class="fui-flex whole-room_con">
						<view class="fui-flex fui-col-4" v-for="(item1,index1) in item.childList" :key="index1">
							<view class="margin-right-xs">
								<iconfont className="icon-duigou2" :size="8" color="#00AFC7"></iconfont>
							</view>
							<view class="font_size_12">
								{{item1.txt}}
							</view>
						</view>
					</view>
				</view>
			</view>
			<!-- 房屋评价 -->
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
			<!-- 房源位置 -->
			<view class="whole-room whole-facility">
				<view class="whole-room_title">
					房源位置
				</view>
				<!-- #ifdef APP-PLUS || H5 -->
				<!-- <map id="map" style="margin: 0 auto;height: 320rpx; width: 100%;margin-bottom: 32rpx;"
					:latitude="latitude" :longitude="longitude"></map> -->
				<!-- #endif -->
			</view>
			<!-- 房东详情 -->
			<view class="whole-room whole-facility">
				<view class="whole-room_title">
					房东详情
				</view>
				<view class="fui-flex justify-between margin-tb-sm">
					<view class="fui-flex">
						<view class="margin-right-xs">
							<image src="https://oss.ddicms.cn/member/apartment/img07.png" mode=""
								style="width: 80rpx;height: 80rpx;border-radius: 50%;"></image>
						</view>
						<view class="">
							<view class="font_size_14 text-bold">
								ghja
							</view>
							<view class="font_size_10 color13 margin-top-xs">
								2023-03-17
							</view>
						</view>
					</view>
					<view class="whole-room_contact fui-flex justify-center">
						<view class="margin-right-xs">
							<iconfont className="icon-lianxi" :size="16"></iconfont>
						</view>
						<view class="font_size_12">
							联系房东
						</view>
					</view>
				</view>
				<!-- <view class="whole-room_column">
					<view class="fui-flex flex-direction">
						<view class="font_size_12 color13">
							平均分
						</view>
						<view class="margin-tb-xs font_size_12 ">
							<text class="font_size_20 text-bold">3.7</text>分
						</view>
						<view class="font_size_10">
							3条评价
						</view>
					</view>
					<view class="whole-room_column_line"></view>
					<view class="fui-flex flex-direction">
						<view class="font_size_12 color13">
							24小时回复率
						</view>
						<view class="margin-tb-xs font_size_20 text-bold">
							50%
						</view>
						<view class="font_size_10">
							评价1天回复
						</view>
					</view>
					<view class="whole-room_column_line"></view>
					<view class="fui-flex flex-direction">
						<view class="font_size_12 color13">
							订单确认回复率
						</view>
						<view class="margin-tb-xs font_size_20 text-bold">
							100%
						</view>
						<view class="font_size_10">
							最近已接1单
						</view>
					</view>
				</view> -->
				<!-- 更多 -->
				<view class="font_size_12 whole-text_more">
					<view class="more">
						The Herce Property is located on the coastof Llucmajor in Majorca and its finearchitectural
						build offers a truely uniqueexperience. A private infinity pool and ...The Herce Property is
						located on the coastof Llucmajor in Majorca and its finearchitectural build offers a truely
						uniqueexperience. A private infinity pool and ... <text class="color4">更多</text>
					</view>
				</view>

			</view>
			<!-- 预订须知 -->
			<view class="whole-room whole-facility">
				<view class="whole-room_title">
					预订须知
				</view>
				<view class="fui-flex align-start margin-bottom-sm" v-for="(item,index) in querydetailObj.instructions"
					:key="index">
					<view class="fui-col-2 font_size_12 margin-right-sm" style="align-self: flex-start;">
						{{item.title}}
					</view>
					<view class="fui-col-10 whole-room_con">
						<view class="font_size_12">
							{{item.content}}
						</view>
					</view>
				</view>
				<!-- <view class="fui-flex align-start margin-bottom-sm">
					<view class="font_size_12 margin-right-sm" style="align-self: flex-start;">
						押金：
					</view>
					<view class="fui-col-10 whole-room_con">
						<view class="font_size_12">
							押金100元，线下支付给房东
						</view>
					</view>
				</view>
				<view class="fui-flex align-start margin-bottom-sm">
					<view class="font_size_12 margin-right-sm" style="align-self: flex-start;">
						确认：
					</view>
					<view class="fui-col-10">
						<view class="font_size_14 color4 text-bold">
							立即确认
						</view>
						<view class="text-24 color13">
							下单即有房，无需等待
						</view>
					</view>
				</view>
				<view class="fui-flex align-start margin-bottom-sm">
					<view class="font_size_12 margin-right-sm" style="align-self: flex-start;">
						取消：
					</view>
					<view class="fui-col-10">
						<view class="font_size_14 color4 text-bold">
							30分钟内免费取消
						</view>
						<view class="text-24 color13">
							预订成功后15分钟内可免费取消
						</view>
					</view>
				</view> -->
			</view>
			<!-- 入住须知 -->
			<view class="whole-room whole-facility">
				<view class="whole-room_title">
					入住须知
				</view>
				<view class="fui-flex align-start margin-bottom-sm">
					<view class="font_size_12 margin-right-sm" style="align-self: flex-start;">
						入住：
					</view>
					<view class="fui-col-10">
						<view class="font_size_14 color4 text-bold">
							自助入住
						</view>
						<view class="text-24 color13">
							全程自助办理入住，无需与房东接触，密码钥匙将在入住当天由房东提供
						</view>
					</view>
				</view>
				<view class="fui-flex align-start margin-bottom-sm">
					<view class="font_size_12 margin-right-sm" style="align-self: flex-start;">
						登记：
					</view>
					<view class="fui-col-10">
						<view class="font_size_14 color4 text-bold">
							实名登记
						</view>
						<view class="text-24 color13">
							携带身份证，方便配合房东办理入住登记
						</view>
					</view>
				</view>
				<view class="fui-flex align-start margin-bottom-sm">
					<view class="font_size_12 margin-right-sm" style="align-self: flex-start;">
						发票：
					</view>
					<view class="fui-col-10">
						<view class="font_size_14 color4 text-bold">
							平台开票
						</view>
						<view class="text-24 color13">
							房东委托平台开具发票
						</view>
					</view>
				</view>
				<view class="fui-flex align-start margin-bottom-sm">
					<view class="font_size_12 margin-right-sm margin-top-xs" style="align-self: flex-start;">
						要求：
					</view>
					<view class="fui-flex whole-room_con">
						<view class="fui-flex fui-col-6">
							<view class="margin-right-xs">
								<iconfont className="icon-duigou2" :size="8" color="#00AFC7"></iconfont>
							</view>
							<view class="font_size_12">
								房源没有设施缺陷
							</view>
						</view>
						<view class="fui-flex fui-col-6">
							<view class="margin-right-xs">
								<iconfont className="icon-duigou2" :size="8" color="#00AFC7"></iconfont>
							</view>
							<view class="font_size_12">
								允许聚会
							</view>
						</view>
						<view class="fui-flex fui-col-6">
							<view class="margin-right-xs">
								<iconfont className="icon-duigou2" :size="8" color="#00AFC7"></iconfont>
							</view>
							<view class="font_size_12">
								房源没有噪音
							</view>
						</view>
						<view class="fui-flex fui-col-6">
							<view class="margin-right-xs">
								<iconfont className="icon-duigou2" :size="8" color="#00AFC7"></iconfont>
							</view>
							<view class="font_size_12">
								允许抽烟
							</view>
						</view>
						<view class="fui-flex fui-col-6">
							<view class="margin-right-xs">
								<iconfont className="icon-duigou2" :size="8" color="#00AFC7"></iconfont>
							</view>
							<view class="font_size_12">
								适合老人
							</view>
						</view>
						<view class="fui-flex fui-col-6">
							<view class="margin-right-xs">
								<iconfont className="icon-jian" :size="8" color="#FB5B32"></iconfont>
							</view>
							<view class="font_size_12 color6">
								不保障设施使用正常
							</view>
						</view>
						<view class="fui-flex fui-col-6">
							<view class="margin-right-xs">
								<iconfont className="icon-jian" :size="8" color="#FB5B32"></iconfont>
							</view>
							<view class="font_size_12 color6">
								不允许携带宠物
							</view>
						</view>
						<view class="fui-flex fui-col-6">
							<view class="margin-right-xs">
								<iconfont className="icon-jian" :size="8" color="#FB5B32"></iconfont>
							</view>
							<view class="font_size_12 color6">
								需爬楼梯
							</view>
						</view>
						<view class="fui-flex fui-col-6">
							<view class="margin-right-xs">
								<iconfont className="icon-jian" :size="8" color="#FB5B32"></iconfont>
							</view>
							<view class="font_size_12 color6">
								不提供设施使用指导
							</view>
						</view>
						<view class="fui-flex fui-col-6">
							<view class="margin-right-xs">
								<iconfont className="icon-jian" :size="8" color="#FB5B32"></iconfont>
							</view>
							<view class="font_size_12 color6">
								不允许做饭
							</view>
						</view>
					</view>
				</view>
				<view class="fui-flex align-start margin-bottom-sm">
					<view class="font_size_12 margin-right-sm margin-top-xs" style="align-self: flex-start;">
						儿童：
					</view>
					<view class="fui-flex whole-room_con">
						<view class="fui-flex fui-col-6">
							<view class="margin-right-xs">
								<iconfont className="icon-duigou2" :size="8" color="#00AFC7"></iconfont>
							</view>
							<view class="font_size_12">
								适合婴幼儿
							</view>
						</view>
						<view class="fui-flex fui-col-6">
							<view class="margin-right-xs">
								<iconfont className="icon-duigou2" :size="8" color="#00AFC7"></iconfont>
							</view>
							<view class="font_size_12">
								适合儿童
							</view>
						</view>
						<view class="fui-flex fui-col-6">
							<view class="margin-right-xs">
								<iconfont className="icon-duigou2" :size="8" color="#00AFC7"></iconfont>
							</view>
							<view class="font_size_12">
								与成人共享现有床铺
							</view>
						</view>

					</view>
				</view>
			</view>
			<!-- 猜您喜欢 -->
			<view class="whole-room whole-facility">
				<view class="whole-room_title">
					猜您喜欢
				</view>
				<view class="">
					<scroll-view id="tab-bar" scroll-with-animation class="fui-scroll-h" :scroll-x="true"
						:show-scrollbar="false">
						<view v-for="(tab, index) in swiperList" :key="tab.id" class="fui-tab-items"
							:data-current="index">
							<view class="fui-tab-item-swiper"
								:class="{ 'fui-tab-item-swiper-active': swiperIndex == index }">
								<view class="swiper_image">
									<image :src="tab.thumb" mode=""
										style="width: 520rpx;height: 272rpx;border-radius: 10rpx;">
									</image>
									<view class="swiper_like">
										<iconfont className="icon-love" :size="24" color="#ffffff"></iconfont>
									</view>
									<view class="swiper_assess fui-flex justify-center">
										<view class="">
											<fui-rate :quantity="1" :size="14" :disabled="true" active="#FF9500"
												:current="1"></fui-rate>
										</view>
										<view class="text-bold">
											{{(+tab.comment_start).toFixed(1)}}分
										</view>
									</view>
								</view>
								<view class="text-bold margin-top-xs font_size_16">
									{{tab.title}}
								</view>
								<view class="fui-flex swiper_txt margin-tb-xs">
									<!-- <view class="swiper_txt_bor font_size_11 color2">
										独立一间<text class="padding-lr-xs">|</text>
									</view> -->
									<view class="swiper_txt_bor font_size_11 color2">
										{{tab.room_num}}居室<text class="padding-lr-xs">|</text>
									</view>
									<view class="swiper_txt_bor font_size_11 color2">
										可住{{tab.persons}}人<text class="padding-lr-xs">|</text>
									</view>
									<view class="swiper_txt_bor font_size_11 color2">
										距您{{tab.distance}}km
									</view>

								</view>
								<view class="whole-appraise fui-flex">
									<view class="whole_bg bg_orange">
										超赞房东
									</view>
									<view class="whole_bg bg_gray">
										长租优惠
									</view>
									<view class="whole_bg bg_gray">
										很干净
									</view>
									<view class="whole_bg bg_gray">
										可带宠物
									</view>
								</view>
								<view class="font_size_12 color6">
									￥<text class="font_size_20">{{(+tab.cprice).toFixed(2)}}</text><text
										class="color3">起</text>
								</view>
							</view>
						</view>
					</scroll-view>
				</view>
			</view>
		</view>
		<!-- 底部 -->
		<view class="whole-bottom fui-flex justify-between">
			<view class="fui-flex flex-direction">
				<view class="">
					<iconfont className="icon-message" :size="34"></iconfont>
				</view>
				<view class="font_size_12">
					联系房东
				</view>
			</view>
			<view class="fui-flex">
				<view class="font_size_12 text-bold color4">
					￥<text class="font_size_20">{{querydetailObj.price}}</text><text class="color3">/{{querydetailObj.time_length}}</text>
				</view>
				<view class="margin-left-sm">
					<button class="serve_btn font_size_14" @tap="reserveBtn">预订</button>
				</view>
			</view>
		</view>
		<!-- 领取优惠券 -->
		<uni-popup type="bottom" ref="assess" style="z-index: 999;">
			<view class="assess">
				<view class="assess_pad fui-flex justify-end">
					<view class="font_size_16 text-bold" style="margin-right: 289rpx;">
						领取优惠券
					</view>
					<view class="" @click="closeLy">
						<iconfont className="icon-quxiao" :size="18" color="#999999"></iconfont>
					</view>
				</view>
				<view class="assess_cards" style="z-index: 70;">
					<block v-for="(item,index) in tackCouponList" :key="index">
						<view class="assess_cards-img fui-flex" @click="useCouponBtn(item)">
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
									<view class="font_size_16 text-bold">
										{{item.name}}
									</view>
									<view class="font_size_12 color13 margin-top-sm margin-bottom-xs">
										{{item.enable_store}}
									</view>
									<view class="font_size_12 color13">
										{{item.enable_end}}到期
									</view>
								</view>
								<view class="fui-col-1 color4 font_size_12 margin-right-lg">
									立即使用
								</view>
							</view>
						</view>
					</block>
				</view>
			</view>
		</uni-popup>

	</view>
</template>

<script>
	export default {
		data() {
			return {
				hotel_id:0,
				//房东
				logo:'',
				lanAvatar: '',
				lanCreateTime: '',
				lanRealname: '',
				lanContent: '',
				//入住时间
				todayTime: '',
				orderTime: {
					startTime: '',
					endTime: '',
					nightTime: '',
					personNum: {
				
					},
					startTimeStr: '',
					endTimeStr: ''
				},
				querydetailObj:{},
				// 房间相册
				roomSlide:[],
				latitude: 34.23053,
				longitude: 108.93425,
				single1: undefined, // 入住时间
				nightTime: 1, //当前日期组件每晚时间
				startTime: '', //当前日期组件选中开始时间
				endTime: '', ////当前日期组件选中结束时间
				indexStartTime: '', //首页传递开始时间
				indexEndTime: '', //首页传递结束时间
				indexNightTime: undefined, //首页传递进来的每晚
				defaultObj: uni.getStorageSync('defaultTime'), //首页默认日期
				getTime: uni.getStorageSync('orderTime'), //首页修改后的日期
				current: 1,
				wholeSwiperList: [],
				tabIndex: 0,
				tabBars: [
					// {
					// 	name: '大房',
					// 	id: 'hot'
					// },
					// {
					// 	name: '中房',
					// 	id: 'yule'
					// },
					// {
					// 	name: '小房',
					// 	id: 'sports'
					// },
					// {
					// 	name: '卫生间',
					// 	id: 'domestic'
					// },
					// {
					// 	name: '厨房',
					// 	id: 'finance'
					// },
					// {
					// 	name: '卧室',
					// 	id: 'keji'
					// }
				],
				scrollInto: '',
				suggestImg: [{
						img: "https://oss.ddicms.cn/member/apartment/img02.png",
						intorTxt: '大房一览'
					},
					{
						img: "https://oss.ddicms.cn/member/apartment/img03.png",
						intorTxt: '户外风光'
					},
					{
						img: "https://oss.ddicms.cn/member/apartment/img04.png",
						intorTxt: '大房一览'
					},
					{
						img: "https://oss.ddicms.cn/member/apartment/img02.png",
						intorTxt: '一米阳光'
					},
				],
				facilityList: [{
						facilityTitle: '服务',
						childList: [{
								txt: '自助入住',
							},
							{
								txt: '免费停车',
							},
							{
								txt: '可长租',
							}
						]
					},
					{
						facilityTitle: '居家',
						childList: [{
								txt: 'WIFI',
							},
							{
								txt: '冷暖空调',
							},
							{
								txt: '电视',
							},
							{
								txt: 'WIFI',
							},
							{
								txt: '冷暖空调',
							},
							{
								txt: '电视',
							}
						]
					},
					{
						facilityTitle: '卫浴',
						childList: [{
								txt: 'WIFI',
							},
							{
								txt: '冷暖空调',
							},
							{
								txt: '电视',
							},
							{
								txt: 'WIFI',
							},
							{
								txt: '冷暖空调',
							},
							{
								txt: '电视',
							}
						]
					},
					{
						facilityTitle: '餐厨',
						childList: [{
								txt: 'WIFI',
							},
							{
								txt: '冷暖空调',
							},
							{
								txt: '电视',
							},
							{
								txt: 'WIFI',
							},
							{
								txt: '冷暖空调',
							},
							{
								txt: '电视',
							}
						]
					},
					{
						facilityTitle: '建筑',
						childList: [{
								txt: 'WIFI',
							},
							{
								txt: '冷暖空调',
							},
							{
								txt: '电视',
							},
							{
								txt: 'WIFI',
							},
							{
								txt: '冷暖空调',
							},
							{
								txt: '电视',
							}
						]
					}
				],
				swiperIndex: 0,
				swiperList: [],
				room_list: {}, //类型图片
				slide: '',
				tackCouponList: [], //领取优惠券
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
			that.indexStartTime = that.getTime.startTime ? that.getTime.startTime.replace('-', '月').concat('日') : that
				.defaultObj.startDeTime.replace('-', '月').concat('日')
			that.indexEndTime = that.getTime.endTime ? that.getTime.endTime.replace('-', '月').concat('日') : that
				.defaultObj.endDeTime.replace('-', '月').concat('日')
			that.indexNightTime = that.getTime.nightTime ? that.getTime.nightTime : that.defaultObj.nightTime
			that.hotel_id = that.$Route.query.hotel_id
			that.getHotelDetail(that.hotel_id)
			that.couponList()
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
			getHotelDetail(hotel_id) {
				let that = this
				that.$api.hotelMobileHotelHzDetail(hotel_id).then(res => {
					that.room_list = res.data.room_list
					that.tackCouponList = res.data.coupon
					console.log(that.tackCouponList, '55555555555')
					that.identify = Object.keys(that.room_list)[0]
					that.querydetailObj = res.data
					const landlord = res.data.landlord
					that.lanAvatar = landlord?.avatar
					that.lanRealname = landlord?.realname
					that.lanCreateTime = landlord?.member.create_time
					that.lanContent = landlord?.content
					that.wholeSwiperList = res.data.thumbs
					that.logo =  res.data.store?.logo
					that.tabBars = res.data.room_type
					that.roomSlide = res.data.slide
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			},
			// 整租详情
			getWholeList(hotel_id) {
				let that = this
				that.fui
					.request('/diandi_hotel/mobile/room/homestaydetail', 'GET', {
						hotel_id: hotel_id
					}, false)
					.then(res => {
						that.querydetailObj = res.data
						that.wholeSwiperList = res.data.thumbs
						that.swiperList = res.data.memberView
						that.room_list = res.data.slides.list
						// console.log(res.data.slides.list,'000000000');
						that.slide = Object.keys(that.room_list)[0]
						// console.log(that.slide,'999999999')
						that.tabBars = res.data.slides.type.map((item, index) => {
							return {
								name: item.value,
								id: 'po' + item.key
							}
						})
						// console.log(that.querydetailObj)
					}).catch(res => {
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
					})
			},
			// 房间优惠券
			couponList() {
				let that = this
				that.fui
					.request('/diandi_hotel/coupon/list', 'POST', {
						hotel_id: that.querydetailObj.hotel_id,
						room_id: that.querydetailObj.id,
						page: 1,
						pageSize: 10,
					}, false)
					.then(res => {
						that.tackCouponList = res.data.list
						// console.log(that.tackCouponList)
					}).catch(res => {
						uni.showToast({
							title: res.message,
							icon: 'none'
						})
					})
			},
			useCouponBtn(item) {
				let that = this
				that.fui
					.request('/diandi_hotel/coupon/getcoupon', 'POST', {
						coupon_id: item.hotel_id,
					}, false)
					.then(res => {
						// console.log(res, '1111111111')
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
			backBtn() {
				let that = this
				that.$Router.back()
			},
			assessBtn(querydetailObj) {
				let that = this
				that.$Router.push({
					name: 'appraise',
					params: {
						hotel_id: querydetailObj.hotel_id,
						room_id: querydetailObj.id
					}
				})
			},
			tabClick(e) {
				let index = e.target.dataset.current || e.currentTarget.dataset.current;
				this.switchTab(index);
				this.slide = e.currentTarget.id.replace('po', '')
				// console.log(e,'bbbbbbbbbbbbbbbbbbbbbb')
				// console.log(this.slide,'ooooooo')
				
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
			// 领取优惠券
			receiveBtn(type) {
				let that = this
				that.type = type
				that.$refs.assess.open(type)
				if(that.tackCouponList.length <= 0) {
					that.$refs.assess.close()
					uni.showToast({
						title:'暂无可以领取的优惠券',
						icon:'none'
					})
				}
			},
			closeLy() {
				let that = this
				that.$refs.assess.close()
			},
			// 预定
			reserveBtn() {
				// let that = this
				// that.$Router.push({
				// 	name: 'authentication'
				// })
				let that = this
				that.$Router.push({
					name: 'submitorder',
					params: {
						hotel_id: that.querydetailObj.id,
						room_id: 0,
						lease_type:that.querydetailObj.lease_type,
						cprice: that.querydetailObj.price
					}
				})
			}

		}
	}
</script>

<style lang="scss" scoped>
	.container {
		.swiper-top {
			position: relative;

			.swiper-top_icon {
				position: absolute;
				top: 80rpx;
				left: 0;
				width: 100%;
				height: 88rpx;
				z-index: 999;
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
			margin: 32rpx 32rpx 0;

			.whole_line {
				margin: 0 12rpx;
				width: 1rpx;
				height: 20rpx;
				background-color: #D1D1D1;
			}

			.whole-appraise {
				padding-bottom: 32rpx;
				border-bottom: 2rpx solid #F5F5F5;

				.whole_bg {
					padding: 4rpx 12rpx;
					border-radius: 6rpx;
					margin-right: 10rpx;
					font-size: 24rpx;
				}

				.bg_orange {
					background-color: #FFEEEA;
					color: #FB5B32;
				}

				.bg_gray {
					background-color: #F9F9F9;
					color: #999999;
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
					margin: 32rpx 0 24rpx;
					font-size: 30rpx;
					font-weight: bold;
				}

				.fui-tab-items {
					display: inline-block;
					flex-wrap: nowrap;
					padding-right: 32rpx;
				}

				.fui-tab-item-swiper {
					flex-wrap: nowrap;
					white-space: nowrap;

					.swiper_image {
						position: relative;

						.swiper_like {
							position: absolute;
							top: 24rpx;
							right: 24rpx;
						}

						.swiper_assess {
							position: absolute;
							bottom: 26rpx;
							left: 16rpx;
							width: 104rpx;
							height: 44rpx;
							background-color: rgba(255, 255, 255, .8);
							border-radius: 5rpx;
							font-size: 20rpx;
						}
					}
				}

				.whole-appraise {
					padding-bottom: 11rpx;
					border-bottom: none;

				}

				.whole-room_con {
					flex: 1;
					flex-wrap: wrap;
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

				.whole-room_contact {
					width: 184rpx;
					height: 56rpx;
					border: 2rpx solid #EEEEEE;
					border-radius: 28rpx;
				}

				.whole-room_column {
					display: flex;
					justify-content: space-evenly;
					align-items: center;
					height: 200rpx;
					background-color: #F9F9F9;
					border-radius: 10rpx;

					.whole-room_column_line {
						width: 2rpx;
						height: 136rpx;
						background: linear-gradient(244deg, rgba(209, 209, 209, 0) 0%, rgba(209, 209, 209, 1) 50%, rgba(209, 209, 209, 0) 100%);

					}

				}

				.whole-text_more {
					height: 105rpx;
					overflow: hidden;
					margin: 32rpx 0;

					.more {
						line-height: 36rpx;
					}
				}

				.whole-room_flex {
					display: flex;
					flex-wrap: wrap;

					.whole-room_flex_img {
						position: relative;
						width: 328rpx;
						height: 228rpx;
						border-radius: 10rpx;
					}

					.whole-room_flex_img:nth-child(2n+1) {
						margin: 0 30rpx 32rpx 0;
					}

					.whole-room_flex_txt {
						position: absolute;
						bottom: 0;
						left: 0;
						width: 100%;
						height: 44rpx;
						line-height: 44rpx;
						background-color: rgba(43, 48, 59, .3);
						text-align: center;
						color: #ffffff;
						font-size: 20rpx;
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


			.serve_btn {
				width: 280rpx;
				height: 80rpx;
				background-color: #00AFC7;
				color: #ffffff;
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
</style>