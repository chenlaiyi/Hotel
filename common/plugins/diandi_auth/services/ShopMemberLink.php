<?php

namespace common\plugins\diandi_auth\services;

use common\plugins\diandi_auth\models\MemberList;
use common\plugins\diandi_auth\models\ZyjMemberLink;
use addons\zyj_wash\models\store\ZyjWashStore;
use common\helpers\ErrorsHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\services\BaseService;

/**
 * ZyjMemberLink ģ�͵����ݲ�����
 */
class ShopMemberLink extends BaseService
{
    //ZyjMemberLink

    /**
     * ������������
     * @throws \Throwable
     */
    public static function add($username, array $shop_tags = [])
    {
        /**
         * ������������
         */
        $nameCount = MemberList::find()->where(['username' => $username])->select('member_id')->count();
        if ($nameCount == 0) {
            return ResultHelper::json(422, '�û�������');
        }
        if ($nameCount > 1) {
            return ResultHelper::json(422, '�û����ظ�');
        }
        $member_id = MemberList::find()->where(['username' => $username])->select('member_id')->scalar();
        try {
            $model = new ZyjMemberLink();
            $member_store_ids = ZyjWashStore::find()->select('id')->indexBy('shop_tag')->select('id')->column();
            foreach ($shop_tags as $shop_tag) {
                $_model = clone $model;
                /**
                 * ��ѯ�Ƿ���ڣ���������£�������������
                 */
                $have = ZyjMemberLink::find()->where(['username' => $username, 'shop_tag' => $shop_tag])->one();
                if ($have) {
                    $have->setAttributes([
                        'member_store_id' => $member_store_ids[$shop_tag],
                        'shop_tag' => $shop_tag,
                        'member_id' => $member_id,
                        'username' => $username
                    ]);
                    if (!$have->update()) {
                        $msg = ErrorsHelper::getModelError($have);
                        return ResultHelper::json(422, $msg);
                    }
                } else {
                    $_model->setAttributes([
                        'member_store_id' => $member_store_ids[$shop_tag],
                        'shop_tag' => $shop_tag,
                        'member_id' => $member_id,
                        'username' => $username
                    ]);
                    if (!$_model->save()) {
                        $msg = ErrorsHelper::getModelError($_model);
                        return ResultHelper::json(422, $msg);
                    }
                }

            }
            return ResultHelper::json(200, '�����ɹ�');

        } catch (\Exception $e) {
            return ResultHelper::json(422, $e->getMessage());
        }
    }

    /**
     * �����ŵ�id��ӹ�������
     * @param $username
     * @param array $member_stores
     * @return array|object[]|string[]
     * @throws \Throwable
     */
    public static function addByStoreId($username, array $member_stores = [])
    {
        $member_store_ids = ZyjWashStore::find()->where(['store_id' => $member_stores])->asArray()->all();
        $model = new ZyjMemberLink();
        loggingHelper::writeLog('diandi_auth', 'diandi_auth_link', 'addByStoreId', [
            'member_stores' => $member_stores,
        ]);
        //            ɾ��ԭ�е�
        ZyjMemberLink::deleteAll(['username' => $username]);
        $member_id = MemberList::find()->where(['username' => $username])->select('member_id')->scalar();
        foreach ($member_store_ids as $item) {
            $_model = clone $model;

            $_model->setAttributes([
                'member_store_id' => $item['store_id'],
                'shop_tag' => $item['shop_tag'],
                'member_id' => $member_id??0, //��ʱ��֪���û�ID
                'username' => $username
            ]);
            if (!$_model->save()) {
                $msg = ErrorsHelper::getModelError($_model);
                loggingHelper::writeLog('diandi_auth', 'diandi_auth_link', 'addByStoreId', [
                    'msg' => $msg,
                ]);
                throw new \Exception($msg);
            }

        }

        return ResultHelper::json(200, '�����ɹ�');

    }

    public static function del($username, array $shop_tags = [])
    {
        try {
            ZyjMemberLink::deleteAll(['shop_tags' => $shop_tags, 'username' => $username]);
            return ResultHelper::json(200, '�����ɹ�');
        } catch (\Exception $e) {
            return ResultHelper::json(422, $e->getMessage());
        }
    }

    /**
     * �����û����޸Ļ�ԱID
     * @param $member_id
     * @param $username
     * @return array
     */
    public static function editMemberId($member_id, $username)
    {
        try {
            ZyjMemberLink::updateAll(['member_id' => $member_id], ['username' => $username]);
            return ResultHelper::json(200, '�����ɹ�');
        } catch (\Exception $e) {
            return ResultHelper::json(422, $e->getMessage());
        }
    }

    public static function getList($member_id): array
    {
        $list = ZyjMemberLink::find()->alias('z')->where(['z.member_id' => $member_id])->joinWith('store')->asArray()->all();
        foreach ($list as &$item) {
            $item['store_name'] = $item['store']['name'];
            $item['address'] = $item['store']['store']['address'];

        }
        return $list;
    }

    public static function getListByUsername($username): array
    {
        return ZyjMemberLink::find()->alias('z')->where(['z.username' => $username])->joinWith('store')->asArray()->all();
    }

    public static function getCloudShop(array $store_ids = [])
    {
        $pids = ZyjWashStore::find()->where(['store_id' => $store_ids])->select('id')->column();
        $list = ZyjWashStore::find()->where(['pid' => $pids])->asArray()->all();
        return $list??[];
    }

}