<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="整租详情" color="#000000" :border="false"  fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<!-- 整租详情 -->
		<view class="swiper-top">
			<swiper :autoplay="true" :interval="3000" :duration="1000" style="height: 520rpx;" @change="changeCur">
				<swiper-item v-for="(item,index) in wholeSwiperList" :key="index">
					<image :src="item.img" mode="widthFix" style="width: 100%;height: 520rpx;">
					</image>
				</swiper-item>
			</swiper>
			<view class="swiper-top_icon fui-flex justify-between">
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
			</view>

			<view class="swiper-top_assess">
				<view class="fui-flex swiper-top_assess_num">
					<view class="font_size_20 color6 text-bold">
						5.0
					</view>
					<view class="swiper-top_assess_num_line"></view>
					<view class="color14 font_size_10">
						123条评论
					</view>
				</view>
			</view>

			<view class="swiper-top_page color14">
				{{current}}/{{wholeSwiperList.length}}
			</view>
		</view>
		<view class="whole">
			<view class="font_size_18 text-bold">
				轻住·卡尔顿精品酒店(高新国际店）
			</view>
			<view class="fui-flex margin-tb-xs">
				<view class="font_size_13 color5 text-bold">
					分租
				</view>
				<view class="whole_line"></view>
				<view class="font_size_13 color2">
					9居室
				</view>
				<view class="whole_line"></view>
				<view class="font_size_13 color2">
					16床
				</view>
				<view class="whole_line"></view>
				<view class="font_size_13 color2">
					30人
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
			<view class="whole-date fui-flex justify-between">
				<view class="font_size_12 color2">
					周一入住<text class="margin-left-xs font_size_16 text-bold color4">4月20日</text>
				</view>
				<view class="whole-date_night font_size_10 color13">
					1晚
				</view>
				<view class="font_size_12 color2">
					周二离店<text class="margin-left-xs font_size_16 text-bold color4">4月21日</text>
				</view>
			</view>
			
			<!-- 其他房型 -->
			<view class="whole-room">
				<view class="whole-room_title">
					<view class="text-bold">
						其他房型
					</view>
				</view>
				<view class="whole-room_other fui-flex justify-between" v-for="(item,index) in 3" :key="index">
					<view class="fui-flex">
						<view class="whole-room_other_img">
							<image src="https://oss.ddicms.cn/member/apartment/combine2.png" mode=""
								style="width: 100%;height: 100%;"></image>
						</view>
						<view class="">
							<view class="font_size_18 text-bold">
								大床房
							</view>
							<view class="fui-flex whole-room_other_txt">
								<view class="txt_every">
									分租
								</view>
								<view class="txt_every">
									7居室
								</view>
								<view class="txt_every">
									1床
								</view>
								<view class="txt_every">
									2人
								</view>
								<view class="txt_every">
									40㎡
								</view>
							</view>
							<view class="font_size_12 color4">
								入住当天18:00前可免费取消
							</view>
							<view class="whole-room_other_bor">
								可开发票
							</view>
							<view class="font_size_12 color6 text-bold">
								¥<text class="font_size_20">228</text>
							</view>
						</view>
					</view>
					<view class="whole-room_other_order fui-flex flex-direction justify-center" @tap="reserveBtn">
						<view class="font_size_16 margin-bottom-xs color14 text-bold">
							订
						</view>
						<view class="whole-room_other_orde_whrite color4">
							<view>在线付</view>
						</view>
					</view>
				</view>
			</view>

			<!-- 房屋图片 -->
			<view class="whole-room whole-facility">
				<view class="whole-room_title">
					房屋图片
				</view>
				<view class="whole-room_flex">
					<view class="whole-room_flex_img" v-for="(item,index) in suggestImg" :key="index">
						<image :src="item.img" mode="" style="width: 100%;height: 100%;">
						</image>
						<view class="whole-room_flex_txt">
							{{item.intorTxt}}
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
				<view class="whole-room_title fui-flex justify-between">
					<view class="">
						房屋评价
					</view>
					<view class="fui-flex" @tap="assessBtn">
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
					<view class="txt">
						服务周到5
					</view>
					<view class="txt">
						房东热情3
					</view>
					<view class="txt">
						早餐服务2
					</view>
					<view class="txt">
						入住体验好2
					</view>
				</view>
				<view class="fui-flex justify-between margin-tb-sm">
					<view class="fui-flex">
						<view class="margin-right-xs">
							<image src="https://oss.ddicms.cn/member/apartment/img06.png" mode=""
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
					<view class="">
						<fui-rate :quantity="5" :current="5" :disabled="true" :size="16" active="#FF9500"></fui-rate>
					</view>
				</view>
				<view class="font_size_12 margin-bottom-sm">
					Lovely place to find inner peace.The guys at the lobby were superattentive,friendly and with such...
				</view>
			</view>
			<!-- 房源位置 -->
			<view class="whole-room whole-facility">
				<view class="whole-room_title">
					房源位置
				</view>
				<!-- <map id="map" style="margin: 0 auto;height: 320rpx; width: 100%;margin-bottom: 32rpx;" :latitude="latitude"
					:longitude="longitude"></map> -->
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
				<view class="fui-flex align-start margin-bottom-sm">
					<view class="font_size_12 margin-right-sm" style="align-self: flex-start;">
						入离：
					</view>
					<view class="fui-col-10 whole-room_con">
						<view class="font_size_12">
							14:00后入住，12:00前退房
						</view>
					</view>
				</view>
				<view class="fui-flex align-start margin-bottom-sm">
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
				</view>
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
					<scroll-view scroll-with-animation class="fui-scroll-h" :scroll-x="true" :show-scrollbar="false">
						<view v-for="(tab, index) in swiperList" :key="tab.id" class="fui-tab-items" :id="tab.id"
							:data-current="index">
							<view class="fui-tab-item-swiper"
								:class="{ 'fui-tab-item-swiper-active': swiperIndex == index }">
								<view class="swiper_image">
									<image src="https://oss.ddicms.cn/member/apartment/img04.png" mode=""
										style="width: 520rpx;height: 272rpx;">
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
											5.0分
										</view>
									</view>
								</view>
								<view class="text-bold margin-top-xs font_size_16">
									王府井 新中式雅致胡同风格美屋
								</view>
								<view class="fui-flex swiper_txt margin-tb-xs">
									<view class="swiper_txt_bor font_size_11 color2">
										独立一间<text class="padding-lr-xs">|</text>
									</view>
									<view class="swiper_txt_bor font_size_11 color2">
										2居室<text class="padding-lr-xs">|</text>
									</view>
									<view class="swiper_txt_bor font_size_11 color2">
										可住2-4人<text class="padding-lr-xs">|</text>
									</view>
									<view class="swiper_txt_bor font_size_11 color2">
										距您2.3km 西安
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
									￥<text class="font_size_20">200</text><text class="color3">起</text>
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
					￥<text class="font_size_20">228</text><text class="color3">/晚</text>
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
					<view class="assess_cards-img fui-flex">
						<view class="assess_cards-img_left">
							<image src="https://oss.ddicms.cn/member/mys/cards_used.png" mode="" style="width: 100%;height: 100%;">
							</image>
							<view class="card-img_txt flex-direction">
								<view class="card-img_txt_money">
									<text class="text-40">¥</text>20
								</view>
								<view class="card-img_txt_used">
									满500可用
								</view>
							</view>
						</view>
						<view class="margin-left-lg card-img_full fui-flex-1 fui-flex justify-between">
							<view class="">
								<view class="font_size_16 text-bold">
									满减券
								</view>
								<view class="font_size_12 color13 margin-top-sm margin-bottom-xs">
									活动通用，部分酒店除外
								</view>
								<view class="font_size_12 color13">
									2023年3月31日到期
								</view>
							</view>
							<view class="fui-col-1 color4 font_size_12 margin-right-lg">
								立即使用
							</view>
						</view>
					</view>

					<view class="assess_cards-img fui-flex">
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
					</view>
				</view>
			</view>
		</uni-popup>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				latitude: 34.23053,
				longitude: 108.93425,
				current: 1,
				wholeSwiperList: [{
						img: 'https://oss.ddicms.cn/member/apartment/combine1.png'
					},
					{
						img: 'https://oss.ddicms.cn/member/apartment/img02.png'
					},
					{
						img: 'https://oss.ddicms.cn/member/apartment/img01.png'
					},
				],
				// tabIndex: 0,
				// tabBars: [{
				// 		name: '大房',
				// 		id: 'hot'
				// 	},
				// 	{
				// 		name: '中房',
				// 		id: 'yule'
				// 	},
				// 	{
				// 		name: '小房',
				// 		id: 'sports'
				// 	},
				// 	{
				// 		name: '卫生间',
				// 		id: 'domestic'
				// 	},
				// 	{
				// 		name: '厨房',
				// 		id: 'finance'
				// 	},
				// 	{
				// 		name: '卧室',
				// 		id: 'keji'
				// 	}
				// ],
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
				swiperList: [{
						name: '热门',
						id: 'hot'
					},
					{
						name: '娱乐',
						id: 'yule'
					},
					{
						name: '体育',
						id: 'sports'
					},
					{
						name: '国内',
						id: 'domestic'
					},
					{
						name: '财经',
						id: 'finance'
					},
					{
						name: '科技',
						id: 'keji'
					},
					{
						name: '教育',
						id: 'education'
					},
					{
						name: '汽车',
						id: 'car'
					}
				],
			};
		},
		onLoad(options) {
			let that = this
			console.log(options)
			that.getCombineList(options.room_id)
		},
		methods: {
			getCombineList() {
				let that = this
				that.fui
					.request('/diandi_hotel/mobile/room/homestayhzdetail','POST',{},false)
					.then(res=>{
						console.log(res)
					}).catch(res=>{
						uni.showToast({
							title:res.title,
							icon:'none'
						})
					})
			},
			backBtn() {
				let that = this
				that.$Router.back()
			},
			assessBtn() {
				let that = this
				that.$Router.push({
					name: 'appraise'
				})
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
			},
			closeLy() {
				let that = this
				that.$refs.assess.close()
			},
			// 预定
			reserveBtn() {
				let that = this
				that.$Router.push({
					name: 'authentication'
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
					border-radius: 5rpx;
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

				.whole-room_other {
					margin-bottom: 32rpx;

					.whole-room_other_img {
						width: 160rpx;
						height: 240rpx;
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
						align-self: flex-end;
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