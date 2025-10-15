<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="帮助中心" color="#000000" :border="false"  fixed placeholder :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="help_top">
			<view class="">
				<view class="font_size_24 text-bold">
					紧急事项
				</view>
				<view class="font_size_14 help_top_num">
					致电服务热线：400-1234-4568
				</view>
				<view class="help_top_phone fui-flex justify-center">
					<view class="margin-right-sm">
						<iconfont className="icon-a-_huaban1-01" :size="22" color="#FFFFFF"></iconfont>
					</view>
					<view class="font_size_14 color14">
						联系客服
					</view>
				</view>
			</view>
		</view>
		<view class="help_question fui-flex justify-between">
			<view class="font_size_14 text-bold">
				常见问题
			</view>
			<view class="fui-flex" @click="questionBtn">
				<view class="font_size_12 color4">
					{{showQuestion ? '收起' : '展开'}}
				</view>
				<iconfont :className="showQuestion ? 'icon-xiajiantou' : 'icon-youjiantou1'" :size="10" color="#00AFC7">
				</iconfont>
			</view>
		</view>
		<block v-if="showQuestion">
			<view class="card" v-for="(item,index) in helpList" :key="index">
				<view class="card_title fui-flex justify-between">
					<view class="fui-flex fui-col-11">
						<view class="card_title_q font_size_12 color14">
							Q
						</view>
						<view class="margin-left-sm font_size_14">
							{{item.title}}
						</view>
					</view>
					<view class="" @click="openTxt(index)">
						<iconfont :className="item.showTxt===true ? 'icon-xiajiantou':'icon-youjiantou1'" :size="14">
						</iconfont>
					</view>
				</view>
				<view class="card_txt font_size_12 color2" v-if="item.showTxt">
					时租房您可以通过APP客户端预订，长包房您可以直接联系酒店前台商谈，如需帮助欢迎致电客服中心。
				</view>
			</view>
		</block>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				showTxt: false,
				showQuestion: false,
				helpList: [{
						title: '我想要预订时租房或长包房，是否可以？',
						showTxt: false

					},
					{
						title: '我使用会员卡预订多间房间是否可以？全部享受会员待遇，并累计积分么？',
						showTxt: false

					},
					{
						title: '到酒店前台查询不到我的预订怎么办？',
						showTxt: false
					},
					{
						title: '我要使用我的会员卡为我朋友订房间，是否可以？能享受会员待遇么？',
						showTxt: false
					}
				]
			};
		},
		methods: {
			openTxt(index) {
				let that = this
				that.helpList[index].showTxt = !that.helpList[index].showTxt
			},
			questionBtn() {
				let that = this
				that.showQuestion = !that.showQuestion
			}
		}
	};
</script>

<style lang="scss" scoped>
	.container {
		.help_top {
			width: 100%;
			height: 262rpx;
			background: url('https://oss.ddicms.cn/member/person/bg.png') no-repeat center;
			background-size: 100%;
			margin-bottom: 48rpx;
			padding: 32rpx 0 0 32rpx;
			box-sizing: border-box;

			.help_top_num {
				padding: 11rpx 0 32rpx;
			}

			.help_top_phone {
				width: 344rpx;
				height: 80rpx;
				background-color: #00AFC7;
				border-radius: 40rpx;
			}
		}

		.help_question {
			margin: 0 32rpx;
			border-top: 2rpx solid #F5F5F5;
			padding: 48rpx 0 32rpx;
		}

		.card {
			padding: 0 32rpx;
			box-shadow: 0rpx 3rpx 20rpx 1rpx rgba(209, 209, 209, 0.3);

			.card_title {
				padding: 32rpx 0;

				.card_title_q {
					align-self: flex-start;
					padding: 5rpx 10rpx;
					background-color: #00AFC7;
					border-radius: 10rpx;
				}
			}

			.card_txt {
				padding: 25rpx 0;
				border-top: 2rpx solid #F5F5F5;
				line-height: 40rpx;
			}
		}
	}
</style>