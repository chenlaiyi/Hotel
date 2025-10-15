<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="景点关联房源" color="#000000" :border="false"  :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="tabs">
			<fui-tabs2 ref="tab" :tabs="tab" color="#999999" sliderBgColor="#00AFC7" selectedColor="#141414" :size="30"
				:sliderWidth="32" :unlined="true" :sliderHeight="4" :currentTab='currentTab' @change="tabsChange"
				itemWidth="33.333333%">
			</fui-tabs2>
		</view>
		<view class="circum_rel">
			<block v-if="currentTab === 0">
				<!-- 列表 -->
				<view class="room-scorll-list fui-flex" v-for="(item,index) in 5" :key="index">
					<view class="room-scorll-list_img margin-right-sm">
						<image src="/https://oss.ddicms.cn/member/apartment/combine2.png" mode=""
							style="width: 100%;height: 100%;border-radius: 10rpx;"></image>
						<view class="room-scorll-list_img_icon" @click="collectBtn">
							<iconfont v-if="collectLove" className="icon-love" :size="20" color="#ffffff"></iconfont>
							<iconfont v-else className="icon-full-love" :size="20" color="#FB5B32"></iconfont>
						</view>

					</view>
					<view class="">
						<view class="room-scorll-list_title font_size_16 text-bold">
							轻住·卡尔顿精品酒店(高新国...
						</view>
						<view class="fui-flex margin-top-sm">
							<view class="room-scorll-list_num font_size_12 text-bold color14">
								5.0
							</view>
							<view class="room-scorll-list_txt color13">
								123条评论
							</view>
						</view>
						<view class="fui-flex margin-tb-xs">
							<view class="">
								<iconfont className="icon-map" :size="20"></iconfont>
							</view>
							<view class="room-scorll-list_km">
								距您2.3km
							</view>
							<view class="room-scorll-list_km">
								西安高新区高新路8号
							</view>
						</view>
						<view class="fui-flex margin-bottom-sm">
							<view class="room-scorll-list_assess">
								<view class="">
									夜景好看
								</view>
							</view>
							<view class="room-scorll-list_assess">
								<view class="">
									长租优惠
								</view>
							</view>
							<view class="room-scorll-list_assess">
								<view class="">
									很干净
								</view>
							</view>
							<view class="room-scorll-list_assess">
								<view class="">
									可带宠物
								</view>
							</view>
						</view>
						<view class="fui-flex justify-between">
							<view class="room-scorll-list_coupon">
								可用券
							</view>
							<view class="fui-flex">
								<view class="margin-top-xs font_size_12 color13" style="text-decoration: line-through;">
									¥228
								</view>
								<view class="margin-left-xs font_size_12">
									<text class="color6 text-bold">¥</text><text
										class="font_size_20 color6 text-bold">200</text>起
								</view>
							</view>
						</view>
					</view>
				</view>
			</block>
			<block v-if="currentTab=== 1">
				<view class="room-scorll-item" v-for="(item,index) in apartmentList" :key="index">
					<view class="room-scorll-list_img">
						<image src="/https://oss.ddicms.cn/member/apartment/img01.png" mode=""
							style="width: 100%;height: 100%;border-radius: 10rpx;"></image>
						<view class="room-scorll-list_icon" @tap="collectBtn">
							<iconfont :className="collectLove ? 'icon-full-love' : 'icon-love'" :size="26"
								:color="collectLove ? '#FB5B32' : '#ffffff'"></iconfont>
						</view>
						<view class="room-scorll-list_assess fui-flex">
							<view class="margin-bottom-xs">
								<fui-rate :quantity="1" :current="1" active="#FF9500" :size="12"></fui-rate>
							</view>
							<view class="font_size_12 text-bold">
								5.0分
							</view>
						</view>
						<view class="room-scorll-list_avatar">
							<image src="/https://oss.ddicms.cn/member/apartment/img06.png" mode=""></image>
						</view>
					</view>
					<view class="font_size_16 text-bold margin-top-sm">
						王府井 新中式雅致胡同风格美屋
					</view>
					<view class="room-scorll-list_txt fui-flex margin-tb-xs font_size_11 color2">
						<view class="txt color4 text-bold"
							:style="{color:item.apartmentName==='整租' ? greColor : yelColor}">{{item.apartmentName}}
						</view>
						<view class="txt">9居室</view>
						<view class="txt">16床</view>
						<view class="txt">30人</view>
					</view>
					<view class="room-scorll-list_bor fui-flex">
						<view class="bor_con bor_red">
							<view class="txt_size">
								超赞房东
							</view>
						</view>
						<view class="bor_con bor_gray">
							<view class="txt_size">
								长租优惠
							</view>
						</view>
						<view class="bor_con bor_gray">
							<view class="txt_size">
								很干净
							</view>
						</view>
						<view class="bor_con bor_gray">
							<view class="txt_size">
								可带宠物
							</view>
						</view>
					</view>
					<view class="font_size_12 margin-top-xs">
						<text class="color6 text-bold">￥</text><text class="font_size_20 color6 text-bold">200</text>/晚
					</view>
				</view>
			</block>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				collectLove: true,
				currentTab: 0, // tab当前激活
				tab: [{
						name: '酒店'
					},
					{
						name: '公寓'
					},
					{
						name: '民宿'
					}
				],
				apartmentList: [{
						apartmentName: '整租',
					},
					{
						apartmentName: '合租',
					}
				],
				greColor: '#00AFC7',
				yelColor: '#FF9500',
			};
		},
		methods: {
			tabsChange(e) {
				let that = this
				that.currentTab = e.index;
			},
			// 点击收藏
			collectBtn() {
				let that = this
				that.collectLove = !that.collectLove
			},
		}
	}
</script>

<style lang="scss">
	.container {
		.circum_rel {
			margin: 0 32rpx;

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
					font-size: 20rpx;
					-webkit-transform: scale(0.8);
				}

				.room-scorll-list_km {
					position: relative;
					font-size: 22rpx;
					-webkit-transform: scale(0.9);
					color: #666666;
				}

				.room-scorll-list_km::before {
					content: "";
					position: absolute;
					right: -10rpx;
					top: 50%;
					transform: translateY(-50%);
					width: 1px;
					height: 20rpx;
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

					view {
						font-size: 18rpx;
						-webkit-transform: scale(0.8);
						color: #999999;
					}
				}

				.room-scorll-list_coupon {
					padding: 4rpx 14rpx;
					border: 1px solid rgba(251, 91, 50, .3);
					border-radius: 5rpx;
					color: #FB5B32;
					font-size: 20rpx;
					-webkit-transform: scale(0.8);
				}
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

					.room-scorll-list_avatar {
						position: absolute;
						right: 32rpx;
						bottom: -54rpx;

						image {
							width: 100rpx;
							height: 100rpx;
							border-radius: 50%;
							border: 4rpx solid #ffffff;
						}
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

						.txt_size {
							font-size: 18rpx;
							-webkit-transform: scale(0.8);
						}
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
	}
</style>