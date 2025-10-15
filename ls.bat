@echo off
REM 生成模型类
php ./yii gii/model --tableName=dd_weih_exhibition_service_provider --modelClass=WeihExhibitionServiceProvider --useTablePrefix=1 --ns=addons\weih_exhibition\models --queryNs=addons\weih_exhibition\models --generateLabelsFromComments=1

REM 生成后台接口与vue页面
php ./yii gii/adminapi --controllerClass=addons\bea_cloud\admin\red\FreeController --modelClass=addons\bea_cloud\models\red\BeaFree --searchModelClass=addons\bea_cloud\models\searchs\red\BeaFree

REM 生成uniapp项目的api文件
php ./yii addons/createapi --addons=diandi_hotel

REM 生成其他模型类
php ./yii gii/model --tableName=dd_diandi_sy_activity --modelClass=SyActivity --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_activity_apply --modelClass=SyActivityApply --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_activity_apply_record --modelClass=SyActivityApplyRecord --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_activity_order --modelClass=SyActivityOrder --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_activity_refund --modelClass=SyActivityRefund --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_album --modelClass=SyAlbum --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_album_config --modelClass=SyAlbumConfig --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_area --modelClass=SyArea --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_article --modelClass=SyArticle --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_article_cat --modelClass=SyArticleCat --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_banner --modelClass=SyBanner --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_business --modelClass=SyBusiness --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_business_association --modelClass=SyBusinessAssociation --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_business_category --modelClass=SyBusinessCategory --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_business_config --modelClass=SyBusinessConfig --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_config --modelClass=SyConfig --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_delivery_postage_rules --modelClass=SyDeliveryPostageRules --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_demand --modelClass=SyDemand --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_diy_page --modelClass=SyDiyPage --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_faq --modelClass=SyFaq --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_home_set --modelClass=SyHomeSet --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_jielong --modelClass=SyJielong --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_jielong_feedback --modelClass=SyJielongFeedback --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_link --modelClass=SyLink --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_mall_banner --modelClass=SyMallBanner --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_mall_express --modelClass=SyMallExpress --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_mall_freight_rules --modelClass=SyMallFreightRules --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_mall_goods --modelClass=SyMallGoods --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_mall_link --modelClass=SyMallLink --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_mall_order --modelClass=SyMallOrder --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_mall_order_logistics --modelClass=SyMallOrderLogistics --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_mall_order_payinfo --modelClass=SyMallOrderPayinfo --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_mall_order_refund --modelClass=SyMallOrderRefund --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_mall_order_refund_log --modelClass=SyMallOrderRefundLog --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_mall_user_address --modelClass=SyMallUserAddress --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_member --modelClass=SyMember --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_member_apply --modelClass=SyMemberApply --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_member_auth_config --modelClass=SyMemberAuthConfig --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_member_cert --modelClass=SyMemberCert --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_member_expire_message --modelClass=SyMemberExpireMessage --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_member_fees_config --modelClass=SyMemberFeesConfig --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_member_industry_category --modelClass=SyMemberIndustryCategory --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_member_join_config --modelClass=SyMemberJoinConfig --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_member_level --modelClass=SyMemberLevel --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_member_pay --modelClass=SyMemberPay --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_member_promotion --modelClass=SyMemberPromotion --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_member_subscribe --modelClass=SyMemberSubscribe --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_member_visitor --modelClass=SyMemberVisitor --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_pc_banner --modelClass=SyPcBanner --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_pc_business_association --modelClass=SyPcBusinessAssociation --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_questionnaire --modelClass=SyQuestionnaire --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_questionnaire_render --modelClass=SyQuestionnaireRender --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_questionnaire_topic --modelClass=SyQuestionnaireTopic --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_quickmenu --modelClass=SyQuickmenu --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_single_category --modelClass=SySingleCategory --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_tabbar --modelClass=SyTabbar --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_user_wechat --modelClass=SyUserWechat --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1
php ./yii gii/model --tableName=dd_diandi_sy_willbrand --modelClass=SyWillbrand --useTablePrefix=1 --ns=addons\diandi_sy\models --queryNs=addons\diandi_sy\models --generateLabelsFromComments=1

REM 结束
echo 所有代码生成完成
pause
