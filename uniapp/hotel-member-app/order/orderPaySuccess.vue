<template>
    <view class="container">
		<!-- #ifndef APP-PLUS -->
		<u-navbar title="支付成功" fixed placeholder  fixed placeholder :auto-back="true" color="#fff" :fixed="true" ></u-navbar>
		<!-- #endif -->
        <view class="card">
            <view class="success_pic">
                <image src="https://oss.ddicms.cn/member/success.png" mode=""></image>
            </view>
            <view class="middle_title">
                支付成功
            </view>
            <view class="success_msg">
                您可以在订单中心查看服务记录和状态提醒
            </view>
            <view class="fui-flex justify-around" style="margin-top: 72rpx;">
                <view class="success_btn" @click="indexBtn">
                    返回首页
                </view>
                <view class="success_btn green" @click="detailBtn">
                    查看订单
                </view>
            </view>
        </view>
    </view>
</template>

<script>
    export default {
        data() {
            return {
                
            }
        },
        methods: {
            indexBtn() {
				let that = this
				that.$Router.replaceAll({
					name:'indexphone'
				})
			},
			detailBtn() {
				let that = this
                that.$store.state.currentindex = 2
				that.$Router.replaceAll({
					name:'myorder'
				})
			}
        }
    }
</script>

<style>
.success_pic{
    width: 188rpx;
    height: 123rpx;
    margin: 0 auto;
    margin-top: 72rpx;
}
.success_pic image{
    width: 100%;
    height: 100%;
}
.middle_title{
    font-size: 32rpx;
    font-weight: 600;
    color: #000000;
    margin-top: 27rpx;
    text-align: center;
}
.success_msg{
    font-size: 24rpx;
    font-weight: 400;
    color: #999999;
    text-align: center;
    margin-top: 10rpx;
}
.success_btn{
    width: 200rpx;
    height: 64rpx;
    line-height: 64rpx;
    text-align: center;
    background: #FFFFFF;
    border-radius: 35rpx;
    border: 2rpx solid #EEEEEE;
}
.green{
    border: 2rpx solid #00AFC7;
    color: #00AFC7;
}
</style>
