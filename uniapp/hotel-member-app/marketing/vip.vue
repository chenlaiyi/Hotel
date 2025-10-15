<template>
	<view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="会员中心" color="#000000" :border="false" :auto-back="true"></u-navbar>
		<!-- #endif -->
		<view class="vip_top">
			<view class="info_person fui-flex bg-white">
				<image class="head_img" :src="member.avatar" mode="aspectFill"></image>
				<view class="margin-left-sm">
					<view class="fui-flex">
						<view class="text-df">{{member.realname}}</view>
						<view class="perdon_type color4 fui-center">
							<image src="https://oss.ddicms.cn/member/Vip.png" mode=""></image>
							普通用户
						</view>
					</view>
					<view class="person_id">ID:{{member.member_id}}</view>
				</view>
			</view>
		</view>
		<view class="rw_title text-xl text-bold">我的任务</view>
		<view class="card_list">
			<view class="fui-flex justify-between">
				<view class="fui-col-6" @click="href(1)">
					<image class="card_img" src="https://oss.ddicms.cn/member/vip1.png" mode="aspectFill"></image>
				</view>
				<view class="fui-col-6 margin-left-sm" @click="href(2)">
					<image class="card_img" src="https://oss.ddicms.cn/member/vip2.png" mode="aspectFill"></image>
				</view>
			</view>
			<view class="fui-flex justify-between">
				<view class="fui-col-6" @click="href(3)">
					<image class="card_img" src="https://oss.ddicms.cn/member/vip3.png" mode="aspectFill"></image>
				</view>
				<view class="fui-col-6 margin-left-sm" @click="href(4)">
					<image class="card_img" src="https://oss.ddicms.cn/member/vip4.png" mode="aspectFill"></image>
				</view>
			</view>
		</view>
		<!-- <tab-bar :currentindex="currentTabIndex"></tab-bar> -->
	</view>
</template>

<script>
	export default {
		data() {
			return {
				currentTabIndex: 2,
				member: {}
			};
		},
		onLoad() {
			const that = this
			that.memberInfo()
		},
		methods: {
			href(index) {
				let that = this;
				let name = '';
				if (index === 1) {
					name = 'myoupon';
				} else if (index === 2) {
					name = 'myintegral';
				} else if (index === 3) {
					name = 'myrecharge';
				} else if (index === 4) {
					name = 'myshop';
				}
				that.$Router.push({
					name: name
				});
			},
			memberInfo() {
				let that = this
				that.$api.hotelMemberInfo('', that.form.userName, that.form.userMobile, that.form.icard_code, '', '', that.form.id).then(res => {
					console.log(res)
					that.member = res.data
				}).catch(res => {
					uni.showToast({
						title: res.message,
						icon: 'none'
					})
				})
			}
		}
	};
</script>

<style>
	.vip_top {
		height: 198rpx;
		background: linear-gradient(180deg, #00afc7 0%, #ffffff 100%);
	}

	.info_person {
		margin: 0 32rpx;
		height: 248rpx;
		box-shadow: 0px 1px 12rpx 1px rgba(0, 0, 0, 0.16);
		border-radius: 16rpx;
		position: absolute;
		top: 132rpx;
		z-index: 2;
		width: 91%;
	}

	.text-df {
		font-size: 36rpx;
		font-family: PingFang SC-Regular, PingFang SC;
		font-weight: 400;
		color: #000000;
	}

	.head_img {
		width: 112rpx;
		height: 112rpx;
		margin: 58rpx 42rpx;
		border-radius: 100%;
	}

	.perdon_type {
		/* width: 112rpx; */
		padding: 4rpx 12rpx;
		background: rgb(0, 175, 199, 0.24);
		border-radius: 8rpx;
		margin-left: 16rpx;
		font-size: 16rpx;

	}

	.perdon_type image {
		width: 17rpx;
		height: 15rpx;
		margin-right: 7rpx;
	}

	.person_id {
		margin-top: 16rpx;
		color: #6b6b6b;
		font-size: 24rpx;
	}

	.rw_title {
		margin: 165rpx 0 22rpx 42rpx;
	}

	.card_list {
		margin: 0 34rpx;
	}

	.card_img {
		width: 100%;
		height: 170rpx;
		border-radius: 16rpx;
	}
</style>