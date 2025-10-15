<?php

namespace common\plugins\diandi_auth\components;

use common\plugins\diandi_auth\models\MemberList;
use common\plugins\diandi_auth\services\MemberService;
use common\helpers\loggingHelper;
use yii\base\BaseObject;

class Member extends BaseObject
{
    public $member_id;

    public function init()
    {
        parent::init();
    }

    /**
     * 获取会员信息
     * @param $member_id
     * @return array
     */
    public function info($member_id): array
    {
        $member = MemberService::getMemberInfo($member_id);
        return $member??[];
    }

    //    infoByUid
    public function infoByUid($uid): array
    {
        $member = MemberService::getMemberInfoByUid($uid);
        return $member??[];
    }

    /**
     * 编辑会员信息
     */
    public function editInfo($member_id, array $data = []): array
    {
        loggingHelper::writeLog('diandi_auth', 'editInfo','编辑会员资料', [
            'member_id' => $member_id,
            'data' => $data
        ]);
        return MemberService::editInfo($member_id, $data);
    }

    /**
     * 编辑会员信息
     */
    public function editInfoByUid($user_id, array $data = []): array
    {
        loggingHelper::writeLog('diandi_auth', 'editInfo','编辑会员资料', [
            'user_id' => $user_id,
            'data' => $data
        ]);
        return MemberService::editInfoByUid($user_id, $data);
    }

    //getMemberIdByAdminId
    public function getMemberIdByAdminId($admin_id): bool|int|string
    {
        $member_id = MemberList::find()->where(['user_id' => $admin_id])->select('member_id')->scalar();
        return $member_id??0;
    }


}